# Hosting Guide: Nginx + PHP-FPM

This guide deploys the portfolio to an Ubuntu VPS and connects:

- `https://sathirasrisathsara.com`
- `https://www.sathirasrisathsara.com` → redirects to the non-`www` domain

The application directory used below is `/var/www/portfolio`, and only its `public/` directory is exposed by Nginx.

## 1. Before starting

You need:

- An Ubuntu VPS with a public static IPv4 address
- A non-root SSH user with `sudo` access
- Control of the DNS records for `sathirasrisathsara.com`
- The portfolio source code in a Git repository, or access to upload it with SCP/SFTP
- Ports 22, 80, and 443 allowed by the VPS provider firewall

Replace these placeholders throughout the guide:

| Placeholder | Replace with |
|---|---|
| `SERVER_IP` | Public IPv4 address of the VPS |
| `SSH_USER` | Your Ubuntu SSH username |
| `YOUR_REPOSITORY_URL` | Git clone URL, if deploying with Git |
| `STRONG_ADMIN_PASSWORD` | A new administrator password of at least 12 characters |
| `YOUR_EMAIL_ADDRESS` | Your real email address |

Do not reuse an administrator password from another service.

## 2. Point the domain to the server

At the DNS provider for `sathirasrisathsara.com`, create:

| Type | Name/Host | Value | TTL |
|---|---|---|---|
| A | `@` | `SERVER_IP` | 300 or Automatic |
| CNAME | `www` | `sathirasrisathsara.com` | 300 or Automatic |

If the DNS provider does not accept `@`, use the root domain or leave the host field blank. Do not create an `AAAA` record unless the VPS has working IPv6 and Nginx is listening on it.

Verify propagation from your computer:

```bash
nslookup sathirasrisathsara.com
nslookup www.sathirasrisathsara.com
```

Both names must resolve to the VPS before requesting the HTTPS certificate. DNS propagation can take from a few minutes to several hours.

## 3. Connect and update Ubuntu

```bash
ssh SSH_USER@SERVER_IP
sudo apt update
sudo apt upgrade -y
sudo timedatectl set-timezone Asia/Colombo
```

Reboot if Ubuntu reports that one is required:

```bash
sudo reboot
```

Reconnect after the server starts.

## 4. Install Nginx, PHP, and utilities

The application requires PHP 8.2 or newer. Install the version provided by the supported Ubuntu release:

```bash
sudo apt install -y \
  nginx git curl unzip snapd \
  php-fpm php-cli php-mysql php-mbstring php-xml php-curl \
  php-zip php-gd php-intl php-bcmath
```

Confirm the installed versions and PHP-FPM service:

```bash
nginx -v
php -v
systemctl list-units --type=service 'php*-fpm.service'
ls -la /run/php/
```

The final command shows the PHP-FPM socket, for example `/run/php/php8.3-fpm.sock`. Use the actual socket name in the Nginx configuration later.

Enable the services. Replace `php8.3-fpm` if your installed service has a different version:

```bash
sudo systemctl enable --now nginx
sudo systemctl enable --now php8.3-fpm
```

## 5. Configure the firewall

If UFW is being used, allow SSH before enabling it:

```bash
sudo ufw allow OpenSSH
sudo ufw allow 'Nginx Full'
sudo ufw enable
sudo ufw status
```

Also allow TCP 80 and 443 in the VPS provider's network firewall/security group.

## 6. Install Composer securely

Use the installer verification procedure published by Composer:

```bash
cd /tmp
EXPECTED_CHECKSUM="$(php -r 'copy("https://composer.github.io/installer.sig", "php://stdout");')"
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
ACTUAL_CHECKSUM="$(php -r "echo hash_file('sha384', 'composer-setup.php');")"

if [ "$EXPECTED_CHECKSUM" != "$ACTUAL_CHECKSUM" ]; then
  echo 'ERROR: Invalid Composer installer checksum'
  rm composer-setup.php
  exit 1
fi

sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
rm composer-setup.php
composer --version
```

Reference: [Composer installation documentation](https://getcomposer.org/doc/00-intro.md).

## 7. Upload the application

### Option A: Clone from Git

```bash
sudo mkdir -p /var/www
sudo chown "$USER":"$USER" /var/www
cd /var/www
git clone https://github.com/SathiraSriSathsara/portfolio.git
cd /var/www/portfolio
```

### Option B: Upload from the current Windows computer

Run this command in PowerShell on your computer, not on the VPS:

```powershell
scp -r "C:\Users\User\Documents\Projects\personal-portfolio" SSH_USER@SERVER_IP:/tmp/portfolio
```

Then run on the VPS:

```bash
sudo mv /tmp/sathira-portfolio /var/www/portfolio
sudo chown -R "$USER":"$USER" /var/www/portfolio
cd /var/www/portfolio
```

Do not upload your local `.env`, `vendor/`, `.phpunit.cache/`, or `storage/sessions/` data. If they were uploaded, replace `.env` with a production configuration and clear runtime files before launch.

Confirm the important files exist:

```bash
test -f composer.json && echo 'composer.json found'
test -f public/index.php && echo 'public/index.php found'
test -f database/migrations/001_initial.sql && echo 'migration found'
```

## 8. Install production dependencies

Run Composer as the deployment user, not as root:

```bash
cd /var/www/portfolio
composer install --no-dev --prefer-dist --no-interaction --classmap-authoritative
composer audit
```

Because `composer.lock` is present, `composer install` uses the locked dependency versions for a reproducible build. Reference: [Composer basic usage](https://getcomposer.org/doc/01-basic-usage.md).

## 9. Connect the existing remote MySQL database

This guide assumes your MySQL server, phpMyAdmin, database, user account, and remote access are already working. No MySQL server setup is required on the Nginx VPS.

Ensure the remote database firewall or hosting control panel allows connections from the public IP address of this VPS. Have the remote hostname, port, database name, username, and password ready for the `.env` file. The existing database user must be permitted to create and alter tables so application migrations can run.

## 10. Create the production environment file

```bash
cd /var/www/portfolio
cp .env.example .env
openssl rand -hex 32
```

Copy the random output, then edit `.env`:

```bash
nano .env
```

Use this production configuration:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://sathirasrisathsara.com
APP_KEY=PASTE_THE_RANDOM_64_CHARACTER_VALUE_HERE
APP_TIMEZONE=Asia/Colombo

DB_HOST=YOUR_REMOTE_MYSQL_HOST
DB_PORT=3306
DB_DATABASE=YOUR_EXISTING_DATABASE_NAME
DB_USERNAME=YOUR_EXISTING_DATABASE_USERNAME
DB_PASSWORD="YOUR_EXISTING_DATABASE_PASSWORD"
DB_CHARSET=utf8mb4

ADMIN_NAME="Sathira Sri Sathsara"
ADMIN_EMAIL="YOUR_EMAIL_ADDRESS"
ADMIN_PASSWORD="STRONG_ADMIN_PASSWORD"

CACHE_TTL=300
SESSION_SECURE=true
```

Save with `Ctrl+O`, Enter, and exit with `Ctrl+X`.

Protect the file:

```bash
sudo chown root:www-data .env
sudo chmod 640 .env
```

Never commit or serve `.env`. Nginx will expose only `public/`, but the file permissions provide an additional control.

## 11. Prepare writable directories

The web process needs write access only to runtime storage and uploads:

```bash
cd /var/www/portfolio
sudo mkdir -p storage/cache storage/logs storage/sessions public/uploads
sudo chown -R www-data:www-data storage public/uploads
sudo find storage public/uploads -type d -exec chmod 750 {} \;
sudo find storage public/uploads -type f -exec chmod 640 {} \;
sudo chown -R "$USER":www-data app bootstrap config routes database public/assets vendor
sudo find app bootstrap config routes database public/assets vendor -type d -exec chmod 750 {} \;
sudo find app bootstrap config routes database public/assets vendor -type f -exec chmod 640 {} \;
```

Leave `public/index.php` readable by the `www-data` group:

```bash
sudo chown "$USER":www-data public/index.php
sudo chmod 640 public/index.php
```

## 12. Run migrations and create the administrator

Run application commands as `www-data` so runtime-generated cache files have the correct owner:

```bash
cd /var/www/sathira-portfolio
sudo -u www-data php bin/console migrate
sudo -u www-data php bin/console seed
```

Expected output includes:

```text
Migrated: 001_initial.sql
Administrator seeded.
```

After the administrator is created, remove the plaintext administrator password from `.env`:

```bash
sudo nano .env
```

Change:

```dotenv
ADMIN_PASSWORD=
```

The hashed password remains in MySQL. Add a new temporary value only when intentionally running the seeder to reset the administrator password.

## 13. Configure Nginx

Determine the PHP socket first:

```bash
ls /run/php/php*-fpm.sock
```

Create the site configuration:

```bash
sudo nano /etc/nginx/sites-available/sathirasrisathsara.com
```

Paste the following. Replace `php8.3-fpm.sock` if the previous command showed a different version.

```nginx
server {
    listen 80;
    listen [::]:80;

    server_name sathirasrisathsara.com www.sathirasrisathsara.com;
    root /var/www/sathira-portfolio/public;
    index index.php;

    charset utf-8;
    client_max_body_size 6M;

    access_log /var/log/nginx/sathirasrisathsara-access.log;
    error_log  /var/log/nginx/sathirasrisathsara-error.log warn;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico {
        access_log off;
        log_not_found off;
        try_files $uri /assets/images/favicon-bw.png;
    }

    location ~ \.php$ {
        try_files $uri =404;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param DOCUMENT_ROOT $document_root;
        fastcgi_param HTTPS $https if_not_empty;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_read_timeout 60s;
    }

    location ~* ^/uploads/.*\.(php|phtml|phar|cgi|pl|py|sh)$ {
        deny all;
    }

    location ~* \.(?:css|js|png|jpe?g|webp|avif|ico|pdf)$ {
        expires 30d;
        add_header Cache-Control "public, max-age=2592000";
        access_log off;
        try_files $uri =404;
    }

    location ~ /\. {
        deny all;
    }

    location ~* ^/(?:app|bootstrap|config|database|routes|storage|tests|vendor)/ {
        deny all;
    }
}
```

Important details:

- The `root` points to `/public`, never the repository root.
- `try_files` sends clean application URLs to `index.php`.
- Nginx passes PHP only to the local PHP-FPM Unix socket.
- Executable uploads and hidden files are blocked.
- The exact domain names are selected through `server_name`, following the [official Nginx server-name documentation](https://nginx.org/en/docs/http/server_names.html).

Enable the site and disable the default site:

```bash
sudo ln -s /etc/nginx/sites-available/sathirasrisathsara.com /etc/nginx/sites-enabled/sathirasrisathsara.com
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx
```

Do not continue until `nginx -t` reports that the configuration test is successful.

## 14. Test HTTP before enabling HTTPS

```bash
curl -I http://sathirasrisathsara.com
curl http://sathirasrisathsara.com/health
```

Expected health output:

```json
{"status":"ok"}
```

If the health endpoint returns `503`, check the database configuration and migrations. If the site returns `502 Bad Gateway`, verify the `fastcgi_pass` socket and PHP-FPM service.

## 15. Enable HTTPS with Let's Encrypt

Certbot's current official guidance recommends its Snap package for most Linux users:

```bash
sudo apt remove -y certbot python3-certbot-nginx 2>/dev/null || true
sudo snap install core
sudo snap refresh core
sudo snap install --classic certbot
sudo ln -sf /snap/bin/certbot /usr/local/bin/certbot
```

Request certificates for both hostnames and redirect HTTP to HTTPS:

```bash
sudo certbot --nginx \
  -d sathirasrisathsara.com \
  -d www.sathirasrisathsara.com \
  --redirect \
  --agree-tos \
  --no-eff-email \
  --email YOUR_EMAIL_ADDRESS
```

Test renewal:

```bash
sudo certbot renew --dry-run
systemctl list-timers | grep certbot
```

Certbot installs automatic renewal through a system timer or scheduled task. References: [official Certbot Nginx instructions](https://certbot.eff.org/instructions?ws=nginx&os=snap) and [Certbot renewal documentation](https://eff-certbot.readthedocs.io/en/stable/using.html#renewing-certificates).

## 16. Redirect `www` to the canonical domain

After Certbot finishes, inspect the resulting configuration:

```bash
sudo nginx -T | less
```

Ensure the HTTPS configuration ultimately has a redirect server for `www`:

```nginx
server {
    listen 443 ssl;
    listen [::]:443 ssl;
    server_name www.sathirasrisathsara.com;

    ssl_certificate /etc/letsencrypt/live/sathirasrisathsara.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/sathirasrisathsara.com/privkey.pem;

    return 301 https://sathirasrisathsara.com$request_uri;
}
```

The main HTTPS server must contain:

```nginx
server_name sathirasrisathsara.com;
```

Certbot may organize the generated blocks differently. Preserve its managed certificate directives. After manual changes:

```bash
sudo nginx -t
sudo systemctl reload nginx
```

## 17. Configure scheduled publishing

Create a cron entry for the PHP-FPM user:

```bash
sudo crontab -u www-data -e
```

Add:

```cron
* * * * * cd /var/www/sathira-portfolio && /usr/bin/php bin/console schedule >> storage/logs/scheduler.log 2>&1
```

The scheduler publishes due posts and removes expired password-reset records. Confirm it runs:

```bash
sudo -u www-data php /var/www/sathira-portfolio/bin/console schedule
sudo tail -n 50 /var/www/sathira-portfolio/storage/logs/scheduler.log
```

## 18. Configure PHP for production

Find the active FPM configuration:

```bash
php --ini
ls /etc/php/*/fpm/php.ini
```

Edit the appropriate FPM `php.ini`, for example:

```bash
sudo nano /etc/php/8.3/fpm/php.ini
```

Recommended baseline values:

```ini
display_errors = Off
display_startup_errors = Off
log_errors = On
error_log = /var/www/sathira-portfolio/storage/logs/php-error.log
expose_php = Off
session.cookie_httponly = 1
session.cookie_secure = 1
session.cookie_samesite = Lax
upload_max_filesize = 5M
post_max_size = 6M
memory_limit = 256M
max_execution_time = 60
date.timezone = Asia/Colombo
opcache.enable = 1
opcache.validate_timestamps = 1
opcache.revalidate_freq = 2
```

Then restart FPM using the installed version:

```bash
sudo systemctl restart php8.3-fpm
sudo systemctl reload nginx
```

## 19. Final verification

Check every public endpoint:

```bash
curl -I https://sathirasrisathsara.com
curl -I https://www.sathirasrisathsara.com
curl https://sathirasrisathsara.com/health
curl -I https://sathirasrisathsara.com/admin/login
curl -I https://sathirasrisathsara.com/robots.txt
curl -I https://sathirasrisathsara.com/sitemap.xml
curl -I https://sathirasrisathsara.com/rss.xml
curl -I https://sathirasrisathsara.com/llms.txt
```

Verify in a browser:

- `https://sathirasrisathsara.com`
- `https://sathirasrisathsara.com/admin/login`
- The TLS lock icon is present
- `www` redirects to the non-`www` address
- Login works with the seeded administrator
- A draft post is not public
- Publishing makes a post visible
- The CV downloads correctly
- Images, CSS, JavaScript, favicon, and WhatsApp link work

Run these server checks:

```bash
sudo nginx -t
sudo systemctl --no-pager --full status nginx php8.3-fpm
sudo tail -n 100 /var/log/nginx/sathirasrisathsara-error.log
sudo tail -n 100 /var/www/portfolio/storage/logs/php-error.log
```

## 20. Safe update procedure

For a Git deployment:

```bash
cd /var/www/portfolio
git fetch --all --prune
git pull --ff-only
composer install --no-dev --prefer-dist --no-interaction --classmap-authoritative
sudo -u www-data php bin/console migrate
sudo chown -R www-data:www-data storage public/uploads
sudo systemctl reload php8.3-fpm
sudo systemctl reload nginx
```

Before deployment, back up the database and uploaded media. Do not use `git reset --hard` on a server containing uncommitted production changes.

## 21. Backups

Create a protected backup directory:

```bash
sudo install -d -m 700 /var/backups/portfolio
```

Media backup:

```bash
sudo tar -czf /var/backups/portfolio/uploads-$(date +%F-%H%M).tar.gz -C /var/www/portfolio/public uploads
```

Use the backup system provided by your existing remote MySQL server or hosting account. Store encrypted media backups off the VPS, retain multiple generations, and periodically test restoring both database and media backups. Do not include `.env` in ordinary source-code backups; back it up separately using encryption and restricted access.

## 22. Troubleshooting

### `502 Bad Gateway`

```bash
ls -la /run/php/
sudo systemctl status php8.3-fpm
grep fastcgi_pass /etc/nginx/sites-enabled/sathirasrisathsara.com
```

Set `fastcgi_pass` to the socket that actually exists, then test and reload Nginx.

### `500 Internal Server Error`

```bash
sudo tail -n 100 /var/log/nginx/sathirasrisathsara-error.log
sudo tail -n 100 /var/www/portfolio/storage/logs/php-error.log
sudo -u www-data php /var/www/portfolio/bin/console migrate
```

Common causes are missing Composer dependencies, an invalid `.env`, database access failure, and unwritable `storage/` directories.

### Database access denied

Confirm `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` match the existing remote database. Confirm the database server permits connections from the Nginx VPS IP. Do not paste `.env` output publicly because it contains credentials.

### CSS or JavaScript changes do not appear

Static files are cached for 30 days. Test with a private window or hard refresh. For frequent deployments, add asset versioning before using a one-year immutable cache lifetime.

### Certbot cannot validate the domain

- Confirm both DNS names resolve to this VPS.
- Confirm TCP port 80 is reachable publicly.
- Confirm Nginx is running and the HTTP server block includes both names.
- Remove incorrect proxy/CDN settings temporarily if they interfere with validation.

### Permission denied in sessions or cache

```bash
sudo chown -R www-data:www-data /var/www/portfolio/storage /var/www/portfolio/public/uploads
sudo find /var/www/portfolio/storage /var/www/portfolio/public/uploads -type d -exec chmod 750 {} \;
sudo find /var/www/portfolio/storage /var/www/portfolio/public/uploads -type f -exec chmod 640 {} \;
```

## 23. Production checklist

- [ ] DNS root record points to the VPS
- [ ] `www` resolves and redirects to the canonical domain
- [ ] Nginx document root is `/var/www/portfolio/public`
- [ ] `.env` is outside the public root and mode `640`
- [ ] `APP_ENV=production`
- [ ] `APP_DEBUG=false`
- [ ] `APP_URL=https://sathirasrisathsara.com`
- [ ] `SESSION_SECURE=true`
- [ ] Existing remote database credentials are configured in `.env`
- [ ] Remote MySQL allows connections from the Nginx VPS IP
- [ ] Composer dependencies installed with `--no-dev`
- [ ] Migrations completed
- [ ] Administrator seeded and plaintext seed password removed
- [ ] Storage and uploads are writable only where required
- [ ] HTTPS works for root and `www`
- [ ] Certbot dry-run renewal succeeds
- [ ] Scheduler cron is installed
- [ ] Backups are automated and restore-tested
- [ ] `/health` returns `{"status":"ok"}`
- [ ] Admin login, publishing, search, sitemap, RSS, CV, and mobile layout are tested
