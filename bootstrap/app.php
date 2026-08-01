<?php
declare(strict_types=1);

use App\Core\App;
use Dotenv\Dotenv;

define('BASE_PATH', dirname(__DIR__));
require BASE_PATH . '/vendor/autoload.php';
if (is_file(BASE_PATH . '/.env')) {
    Dotenv::createImmutable(BASE_PATH)->safeLoad();
}
date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'Asia/Colombo');

$secure = filter_var($_ENV['SESSION_SECURE'] ?? false, FILTER_VALIDATE_BOOL);
session_name('portfolio_session');
session_save_path(BASE_PATH . '/storage/sessions');
session_set_cookie_params(['httponly' => true, 'secure' => $secure, 'samesite' => 'Lax', 'path' => '/']);
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
return new App(require BASE_PATH . '/config/app.php');
