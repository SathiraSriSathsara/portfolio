You are a senior PHP software architect, full-stack developer, UI engineer, security engineer, and technical SEO specialist.

Build a complete, production-ready personal portfolio and Markdown-based blogging website based on the design image attached to this task.

The design image is the primary visual reference. Carefully inspect it before writing code. Recreate its overall structure, proportions, spacing, dark visual style, typography hierarchy, borders, cards, navigation behavior, and responsive experience.

Do not generate only a prototype or static mockup. Build a fully functional application with a public portfolio, blog system, secure administration panel, MySQL persistence, runtime Markdown rendering, SEO metadata, structured data, tests, documentation, and deployment configuration.

Do not ask me to manually create boilerplate files. Create the complete project structure and implementation.

==================================================
1. PROJECT GOAL
==================================================

Create a fast, secure, component-based personal portfolio website for:

Name:
Sathira Sri Sathsara

Primary role:
Software Engineer / Backend Developer

The public site must have three primary desktop columns:

1. Left sidebar
   - Profile image
   - Full name
   - Professional title
   - Short introduction
   - Company
   - Location
   - Local time
   - Email
   - Personal website
   - GitHub
   - LinkedIn
   - Other social links
   - Download CV button
   - Technology stack badges
   - Social icon buttons

2. Center content area
   - Render the selected page or blog post from Markdown stored in MySQL
   - Support headings, paragraphs, lists, links, images, code blocks, inline code, blockquotes, tables, horizontal rules, and task lists
   - Show clean article typography
   - Show syntax-highlighted code blocks
   - Display article metadata where applicable
   - Provide good reading width and spacing

3. Right navigation sidebar
   - “About me” navigation item
   - “Blog Posts” section
   - List recent published blog titles
   - Each item should open the related post in the center content area or through an SEO-friendly canonical URL
   - Include a solid-color “See More” button at the bottom
   - Do not include an “Open in new tab” button

The website should preserve the visual design shown in the attached image while remaining accessible and responsive.

==================================================
2. REQUIRED TECHNOLOGY STACK
==================================================

Use:

- PHP 8.2 or newer
- MySQL 8 or MariaDB-compatible SQL
- PDO with prepared statements
- Composer for PHP dependencies
- Server-rendered PHP templates
- HTML5
- Modern CSS
- Minimal vanilla JavaScript
- Apache and Nginx compatibility
- Environment variables through a `.env` file
- League CommonMark, or another reputable maintained Composer package, for Markdown rendering
- HTMLPurifier or another reputable sanitizer where needed
- PHPUnit for tests

Do not use:

- WordPress
- Laravel
- Symfony as a full framework
- React
- Vue
- Angular
- jQuery
- Bootstrap
- Tailwind CSS
- Large client-side frameworks
- A heavy SPA architecture
- Inline database credentials
- Raw SQL interpolation
- Markdown files as the production source of blog content

Use a lightweight custom PHP architecture with clean separation of concerns.

==================================================
3. COMPONENT-BASED ARCHITECTURE
==================================================

Use a reusable component-based architecture for:

- Colors
- Typography
- Spacing
- Borders
- Shadows
- Buttons
- Badges
- Form fields
- Cards
- Sidebar items
- Navigation items
- Modal dialogs
- Alerts
- Empty states
- Markdown article elements
- Admin tables
- Pagination
- Authentication layouts

Create CSS design tokens using custom properties, for example:

- --color-background
- --color-surface
- --color-surface-raised
- --color-border
- --color-text-primary
- --color-text-secondary
- --color-accent
- --color-accent-hover
- --color-danger
- --color-success
- --font-sans
- --font-mono
- --space-*
- --radius-*
- --shadow-*

Do not duplicate color values, spacing values, or repeated component styles throughout the stylesheets.

Create reusable PHP view components or partials such as:

- layout
- head
- sidebar-profile
- markdown-navigation
- button
- badge
- alert
- pagination
- form-field
- article-card
- admin-header
- admin-sidebar
- flash-message
- confirmation-modal

Store editable portfolio text and contact information in configuration or database-backed settings rather than repeating it in templates.

==================================================
4. RECOMMENDED PROJECT STRUCTURE
==================================================

Use a structure similar to:

/
├── app/
│   ├── Controllers/
│   ├── Core/
│   ├── Helpers/
│   ├── Middleware/
│   ├── Models/
│   ├── Repositories/
│   ├── Services/
│   ├── Validation/
│   └── Views/
│       ├── admin/
│       ├── auth/
│       ├── components/
│       ├── errors/
│       ├── layouts/
│       └── public/
├── bootstrap/
├── config/
├── database/
│   ├── migrations/
│   └── seeds/
├── public/
│   ├── assets/
│   │   ├── css/
│   │   ├── fonts/
│   │   ├── icons/
│   │   ├── images/
│   │   └── js/
│   ├── uploads/
│   ├── .htaccess
│   └── index.php
├── routes/
├── storage/
│   ├── cache/
│   ├── logs/
│   └── sessions/
├── tests/
├── .env.example
├── .gitignore
├── AGENTS.md
├── composer.json
├── phpunit.xml
├── README.md
└── LICENSE

The `public` directory must be the web server document root.

==================================================
5. ROUTING
==================================================

Build a lightweight router supporting:

Public routes:

- GET /
- GET /about
- GET /blog
- GET /blog/page/{page}
- GET /blog/{slug}
- GET /category/{slug}
- GET /tag/{slug}
- GET /search?q=
- GET /rss.xml
- GET /sitemap.xml
- GET /robots.txt
- GET /manifest.webmanifest
- GET /cv/download
- GET /health

Authentication routes:

- GET /admin/login
- POST /admin/login
- POST /admin/logout
- GET /admin/forgot-password
- POST /admin/forgot-password
- GET /admin/reset-password
- POST /admin/reset-password

Protected admin routes:

- GET /admin
- GET /admin/posts
- GET /admin/posts/create
- POST /admin/posts
- GET /admin/posts/{id}/edit
- POST or PATCH /admin/posts/{id}
- POST or DELETE /admin/posts/{id}/delete
- POST /admin/posts/{id}/publish
- POST /admin/posts/{id}/unpublish
- POST /admin/posts/{id}/duplicate
- GET /admin/categories
- POST /admin/categories
- POST or PATCH /admin/categories/{id}
- POST or DELETE /admin/categories/{id}/delete
- GET /admin/tags
- POST /admin/tags
- POST or DELETE /admin/tags/{id}/delete
- GET /admin/media
- POST /admin/media/upload
- POST or DELETE /admin/media/{id}/delete
- GET /admin/settings
- POST /admin/settings
- GET /admin/profile
- POST /admin/profile
- POST /admin/change-password

Use appropriate HTTP status codes and custom 404, 403, 419, 422, and 500 pages.

==================================================
6. DATABASE CONFIGURATION
==================================================

Read database configuration only from environment variables:

DB_HOST=
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
DB_CHARSET=utf8mb4

Do not put real credentials in source code.

Create:

- `.env.example` containing empty or safe example values
- Environment loader
- Database connection class
- PDO error handling
- UTF8MB4 connection support
- Migration runner
- Seeder runner
- Transaction helpers

Use:

- PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
- PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
- PDO::ATTR_EMULATE_PREPARES => false

Never log passwords, session IDs, reset tokens, or database credentials.

==================================================
7. DATABASE SCHEMA
==================================================

Create migrations for at least the following tables.

users:
- id
- name
- email
- password_hash
- role
- status
- last_login_at
- created_at
- updated_at

posts:
- id
- title
- slug
- excerpt
- markdown_content
- rendered_html_cache
- featured_image
- status: draft, scheduled, published, archived
- author_id
- category_id nullable
- meta_title nullable
- meta_description nullable
- canonical_url nullable
- focus_keyword nullable
- og_title nullable
- og_description nullable
- og_image nullable
- robots_index boolean
- robots_follow boolean
- published_at nullable
- scheduled_at nullable
- created_at
- updated_at
- deleted_at nullable

categories:
- id
- name
- slug
- description
- created_at
- updated_at

tags:
- id
- name
- slug
- created_at
- updated_at

post_tags:
- post_id
- tag_id

media:
- id
- original_name
- stored_name
- mime_type
- extension
- size
- width nullable
- height nullable
- alt_text nullable
- caption nullable
- path
- uploaded_by
- created_at

settings:
- id
- setting_key unique
- setting_value
- setting_type
- is_public
- updated_at

password_reset_tokens:
- id
- user_id
- token_hash
- expires_at
- used_at nullable
- created_at

login_attempts:
- id
- email
- ip_hash
- attempted_at
- successful

audit_logs:
- id
- user_id nullable
- action
- entity_type nullable
- entity_id nullable
- metadata_json nullable
- ip_hash nullable
- created_at

Include indexes for:

- post slug
- post status and published_at
- scheduled_at
- category slug
- tag slug
- user email
- full-text search where supported
- foreign keys and relationship columns

Use soft deletion for posts.

Create an initial administrator seeder that reads credentials from environment variables:

ADMIN_NAME=
ADMIN_EMAIL=
ADMIN_PASSWORD=

Hash passwords using `password_hash()` with `PASSWORD_ARGON2ID` when available, otherwise `PASSWORD_DEFAULT`.

==================================================
8. ADMIN PANEL
==================================================

Build a secure, responsive admin panel matching the public visual language but optimized for content management.

Dashboard must display:

- Total posts
- Published posts
- Draft posts
- Scheduled posts
- Recent posts
- Recently updated posts
- Basic content statistics

Post editor requirements:

- Title field
- Auto-generated editable slug
- Excerpt field
- Markdown editor textarea
- Live preview panel
- Desktop split view
- Mobile tabbed editor/preview view
- Category selector
- Tag selector
- Featured image selector/upload
- Draft save
- Publish button
- Schedule publication
- Unpublish/archive controls
- SEO fields
- Social sharing fields
- Canonical URL
- Index/follow settings
- Character counters for title and description
- Unsaved-change warning
- Server-side validation
- Preview before publishing

The Markdown source must be stored in the `markdown_content` column.

The rendered HTML may be cached in `rendered_html_cache`, but the Markdown source remains authoritative.

Whenever content changes:

1. Validate Markdown size
2. Convert Markdown to HTML
3. Sanitize the generated HTML
4. Store or refresh the cached HTML
5. Invalidate relevant page caches
6. Update timestamps
7. Update sitemap and feed cache if used

Do not allow arbitrary raw scripts, inline event handlers, iframes, or dangerous HTML from Markdown.

==================================================
9. MARKDOWN RENDERING
==================================================

Use a reputable maintained Markdown parser through Composer.

Support:

- Headings
- Paragraphs
- Bold and italic
- Ordered and unordered lists
- Links
- Images
- Tables
- Fenced code blocks
- Inline code
- Blockquotes
- Task lists
- Strikethrough
- Autolinks
- Horizontal rules

Requirements:

- Safe mode or HTML sanitization
- Automatically add safe heading IDs
- External links use:
  - rel="noopener noreferrer"
- Optionally open external links in a new tab
- Internal links remain in the same tab
- Lazy-load article images
- Require or encourage image alt text
- Add responsive wrappers around tables
- Add copy buttons to code blocks using minimal JavaScript
- Syntax highlighting must not require a large runtime bundle
- Prevent XSS through Markdown

==================================================
10. PUBLIC WEBSITE BEHAVIOR
==================================================

Home page:

- Default center content should show “About me”
- Right sidebar should show:
  - About me
  - Blog Posts heading
  - Recent blog titles
  - See More button
- Blog title links must use actual post titles, not filenames
- Do not display `.md` in public post titles
- Use a solid accent color for headings
- Use a solid accent color for buttons
- Do not use gradients for primary buttons or headings
- Do not include an “Open in new tab” button in the article header

Blog index:

- Searchable post list
- Pagination
- Featured image where available
- Title
- Excerpt
- Publication date
- Reading time
- Category
- Tags
- Empty state
- SEO-friendly pagination

Blog detail:

- Breadcrumbs
- Article title
- Publication date
- Updated date where applicable
- Reading time
- Category and tags
- Featured image
- Markdown-rendered content
- Previous and next article navigation
- Related posts
- Share links
- Canonical URL
- Author information
- Back-to-blog link

The article must remain fully available in server-rendered HTML. Do not depend on JavaScript to load the main content.

==================================================
11. RESPONSIVE DESIGN
==================================================

Desktop:
- Three-column layout matching the reference design
- Left sidebar fixed or sticky where practical
- Right sidebar sticky where practical
- Center area independently readable
- Avoid nested full-page scrollbars unless necessary

Tablet:
- Profile sidebar becomes compact
- Main content remains primary
- Blog navigation may become a drawer or collapsible panel

Mobile:
- Single-column layout
- Compact profile header
- Easy access to article navigation
- Blog list opens as a drawer, sheet, or collapsible section
- Minimum 44px touch targets
- No horizontal page overflow
- Code blocks and tables scroll horizontally inside their containers
- Preserve article readability

Use responsive CSS, not user-agent detection.

==================================================
12. PERFORMANCE REQUIREMENTS
==================================================

Prioritize very fast loading.

Implement:

- Server-side rendering
- Minimal JavaScript
- No unnecessary dependencies
- No large CSS framework
- Optimized local assets
- Responsive images using `srcset` where useful
- WebP or AVIF generation where supported
- Lazy loading below-the-fold images
- Explicit image dimensions to prevent layout shift
- CSS organized and minifiable
- Deferred JavaScript
- Browser caching headers
- ETag or Last-Modified support where appropriate
- Page caching for public pages
- Query result caching where useful
- Cache invalidation after admin changes
- Database indexes
- Paginated queries
- Avoid N+1 database queries
- Gzip/Brotli deployment guidance
- Font optimization
- Prefer system font stack or carefully self-hosted fonts
- No external font dependency required for initial paint

Performance goals under normal production conditions:

- Lighthouse Performance: 90+
- Accessibility: 95+
- Best Practices: 95+
- SEO: 95+
- LCP below 2.5 seconds
- CLS below 0.1
- INP below 200ms where practical

Do not claim these scores without explaining that they must be measured in the target hosting environment.

==================================================
13. SECURITY REQUIREMENTS
==================================================

Implement:

- PDO prepared statements
- CSRF protection for all state-changing requests
- Secure session cookies
- HttpOnly cookies
- SameSite=Lax or stricter
- Secure cookie flag in HTTPS production
- Session ID rotation after login
- Authentication middleware
- Role authorization
- Rate limiting for login and password reset
- Generic authentication error messages
- Password hashing
- Password reset tokens stored only as hashes
- Reset token expiration
- Login attempt logging
- Output escaping by default
- Markdown sanitization
- File upload MIME validation
- File extension allowlist
- Random generated filenames
- Image size and dimension limits
- Block PHP execution in upload directories
- Security headers
- Content-Security-Policy
- X-Content-Type-Options
- Referrer-Policy
- Permissions-Policy
- Frame protection
- HTTPS deployment guidance
- Audit logs for important admin actions

Allowed media types should initially be limited to safe image types:

- image/jpeg
- image/png
- image/webp
- image/avif, only if server support is confirmed

Do not accept SVG uploads by default because of script and XSS risk.

Add production-safe error handling:

- Detailed errors only in development
- Generic public errors in production
- Log exceptions to `storage/logs`
- Never expose stack traces or credentials publicly

==================================================
14. SEO REQUIREMENTS
==================================================

Implement technical and on-page SEO:

- Unique page titles
- Unique meta descriptions
- Canonical URLs
- Semantic HTML
- One appropriate H1 per page
- Logical heading hierarchy
- Human-readable slugs
- XML sitemap
- robots.txt
- RSS or Atom feed
- Breadcrumbs
- Pagination metadata
- Open Graph tags
- Twitter Card tags
- Article publication metadata
- Article modified metadata
- Image alt text
- Internal linking
- Custom 404 page
- Correct HTTP status codes
- Redirect handling for changed slugs if implemented
- No duplicate URL variants
- Configurable site URL
- Configurable indexing per article
- Clean URLs without `.php`

Create JSON-LD structured data where valid:

Home page:
- Person
- WebSite
- ProfilePage

Blog listing:
- Blog
- CollectionPage
- BreadcrumbList

Blog post:
- BlogPosting or Article
- Person as author
- BreadcrumbList
- WebPage

Use only properties that correspond to visible and truthful page content. Do not fabricate ratings, reviews, awards, employers, or credentials.

==================================================
15. GEO, AEO, AIO, AND LLMO REQUIREMENTS
==================================================

Treat these as content discoverability and machine-readability improvements, not as guaranteed ranking mechanisms.

Optimize pages for search engines, answer engines, AI systems, and language-model-based retrieval by implementing:

- Clear server-rendered page structure
- Descriptive headings
- Concise introductory summaries
- Direct answers near the beginning of informational articles
- Semantic lists and tables
- Definitions where appropriate
- Author identity and expertise information
- Accurate publication and update dates
- Source links and references where used
- Stable canonical URLs
- Consistent entity information
- Related content links
- Topic categories and tags
- Breadcrumbs
- Structured data
- RSS feed
- XML sitemap
- Accessible HTML
- Descriptive anchor text
- Article excerpts
- Proper language declaration
- `lastmod` values in sitemap
- Machine-readable author and organization information
- Optional article FAQ sections when the content genuinely contains FAQs
- Optional table of contents generated from Markdown headings
- “Key takeaways” component that an author may enable
- “Last reviewed” date where applicable
- Clear distinction between personal opinions and factual claims

Add a public `llms.txt` route or file containing concise links to:

- Home
- About
- Blog index
- Important categories
- RSS feed
- Sitemap
- Contact information

Also create an optional `llms-full.txt` implementation only if it can be generated responsibly without exposing drafts, private data, admin routes, credentials, or the full content of pages that should not be bulk exported.

Do not add fake FAQ markup to ordinary content.
Do not keyword-stuff.
Do not hide text.
Do not create doorway pages.
Do not make unsupported claims that the site is “optimized for every AI.”
Do not promise rankings or citations in AI answers.

==================================================
16. SEARCH
==================================================

Implement blog search.

Search should support:

- Title
- Excerpt
- Markdown content
- Category
- Tags

Requirements:

- Sanitize and validate query
- Limit query length
- Paginate results
- Escape output
- Highlighting is optional and must be safe
- Prefer MySQL FULLTEXT where supported
- Include a fallback LIKE-based search if FULLTEXT cannot be used
- Do not expose unpublished posts

==================================================
17. SETTINGS MANAGEMENT
==================================================

Admin settings should support:

Site identity:
- Site name
- Site tagline
- Site URL
- Default meta title
- Default meta description
- Default social image
- Logo/favicon

Profile:
- Full name
- Professional title
- Bio
- Company
- Location
- Email
- Website
- GitHub
- LinkedIn
- Other social links
- Profile image
- CV file
- Technology stack

Content:
- Number of recent posts in sidebar
- Posts per page
- Date format
- Time zone
- Enable reading time
- Enable table of contents
- Enable related posts

SEO:
- Default robots behavior
- Search verification codes
- Social profile URLs
- Sitemap settings
- RSS settings

Settings must be validated and escaped.

Sensitive environment configuration must not be editable through the public admin settings table.

==================================================
18. ACCESSIBILITY
==================================================

Meet WCAG 2.2 AA where practical.

Include:

- Keyboard navigation
- Visible focus states
- Skip-to-content link
- Semantic landmarks
- Correct button and link semantics
- Form labels
- Accessible validation errors
- ARIA only where native HTML is insufficient
- Sufficient contrast
- Reduced-motion handling
- Accessible menu/drawer behavior
- Proper dialog focus management
- Alt text
- Descriptive link names
- Status announcements where useful

Do not rely on color alone to convey state.

==================================================
19. LOCAL TIME COMPONENT
==================================================

The profile sidebar includes the user’s local time.

Implement it as:

- Server-rendered initial time
- Configurable time zone, defaulting to `Asia/Colombo`
- Optional minimal JavaScript update every minute
- Do not cause hydration or layout issues
- Display the UTC offset
- Use semantic `<time>` markup where appropriate

==================================================
20. TESTING
==================================================

Create automated tests for at least:

- Router matching
- Slug generation
- Markdown rendering
- XSS sanitization
- Authentication
- Password hashing
- CSRF validation
- Post validation
- Draft visibility
- Published post visibility
- Scheduled post behavior
- Pagination
- Sitemap output
- RSS output
- SEO metadata generation
- Canonical URL generation
- File upload validation

Also provide a manual QA checklist covering:

- Desktop layout
- Tablet layout
- Mobile layout
- Keyboard navigation
- Login rate limiting
- Post create/edit/publish
- Markdown preview
- Image upload
- Search
- 404 handling
- Sitemap
- RSS
- Open Graph metadata
- Structured data validation
- Cache invalidation

==================================================
21. DEPLOYMENT
==================================================

Provide:

- Apache `.htaccess`
- Example Apache VirtualHost
- Example Nginx server block
- PHP extension requirements
- Composer installation commands
- MySQL setup commands
- Migration command
- Seeder command
- Storage permission commands
- Production environment checklist
- HTTPS recommendation
- Cron example for scheduled posts if needed
- Cache directory configuration
- Backup recommendations

The application must work when deployed with the `public/` directory as document root.

Include an optional cron command for:

- Publishing scheduled posts
- Cleaning expired reset tokens
- Clearing obsolete cache entries

==================================================
22. README
==================================================

Write a complete README with:

- Project overview
- Features
- Technology stack
- Architecture
- Directory structure
- Prerequisites
- Local installation
- Environment setup
- Database creation
- Migration commands
- Seeder commands
- Running locally
- Admin login setup
- Production deployment
- Apache configuration
- Nginx configuration
- Scheduled task setup
- Testing commands
- Security notes
- Backup notes
- Troubleshooting
- How to create and publish a blog post
- How Markdown rendering works
- How cache invalidation works

Do not place real credentials in the README.

==================================================
23. AGENTS.MD
==================================================

Create an `AGENTS.md` file for future coding-agent work.

It should explain:

- Project purpose
- Architecture rules
- Coding conventions
- Security constraints
- Database conventions
- Routing conventions
- View/component conventions
- CSS token rules
- Testing expectations
- Commands for tests and migrations
- Files that must never contain secrets
- Requirement to preserve server-rendered content
- Requirement to avoid heavy frontend dependencies
- Requirement to update tests and documentation with relevant changes

==================================================
24. CODING STANDARDS
==================================================

Use:

- `declare(strict_types=1);`
- PSR-4 autoloading
- PSR-12 style
- Typed properties
- Return types
- Dependency injection where useful
- Small focused classes
- Repository/service separation where it improves maintainability
- Centralized validation
- Centralized escaping helpers
- Centralized response helpers
- Clear exception handling
- Descriptive names
- Comments only where the reason is not obvious

Avoid:

- God classes
- Global mutable state
- Business logic in templates
- SQL inside templates
- Repeated environment access throughout the app
- Hard-coded URLs
- Hard-coded credentials
- Excessive abstraction
- Placeholder TODO implementations
- Empty methods
- Fake data in production paths

==================================================
25. VISUAL REQUIREMENTS FROM THE ATTACHED DESIGN
==================================================

Follow the attached design closely.

Important visual requirements:

- Dark near-black background
- Three clear desktop columns
- Thin subtle borders
- Rounded containers
- Profile image at top of left sidebar
- Profile details below image
- Solid-color accent headings
- Solid-color primary buttons
- No gradient title text
- No gradient primary buttons
- Center Markdown article presentation
- Right sidebar with:
  - About me
  - Blog Posts
  - Blog title list
  - See More button at the bottom
- No “Open in new tab” button
- Elegant developer-focused appearance
- High readability
- Spacious but efficient layout
- Professional rather than overly futuristic
- Subtle hover and focus effects
- Respect `prefers-reduced-motion`

Use the attached screenshot as visual context, but implement the interface as real responsive HTML and CSS rather than placing the screenshot into the page.

==================================================
26. IMPLEMENTATION WORKFLOW
==================================================

Follow this order:

1. Inspect the attached design
2. Inspect the current repository
3. Write a concise implementation plan
4. Create the project architecture
5. Create Composer configuration
6. Implement environment and configuration loading
7. Implement database connection and migrations
8. Implement routing and HTTP foundation
9. Implement models, repositories, and services
10. Implement authentication and security controls
11. Implement the administration panel
12. Implement Markdown parsing and sanitization
13. Implement public portfolio and blog pages
14. Implement responsive styling
15. Implement SEO and structured data
16. Implement sitemap, RSS, robots.txt, and llms.txt
17. Implement caching
18. Add tests
19. Run tests
20. Fix failures
21. Review security
22. Review responsive behavior
23. Write README and AGENTS.md
24. Provide a final implementation summary

Continue through the full implementation without pausing for approval unless:

- A destructive action would delete existing user data
- Required information is genuinely unavailable
- The repository contains an architectural conflict that cannot safely be resolved
- An external service requires credentials or paid access

Do not stop after creating a plan.
Do not stop after creating the database schema.
Do not leave core functionality as pseudocode.

==================================================
27. ACCEPTANCE CRITERIA
==================================================

The task is complete only when:

- The application installs with documented commands
- Environment credentials are not committed
- Migrations successfully create the database schema
- The admin user can log in securely
- The administrator can create a blog post using Markdown
- Markdown source is saved in MySQL
- Markdown is rendered safely at runtime or from a refreshed safe cache
- Draft posts are not publicly visible
- Published posts are publicly accessible through unique slugs
- The public homepage matches the attached three-column design
- The right sidebar shows About me and recent blog titles
- The See More button opens the blog index
- Primary buttons use a solid accent color
- Headings use a solid accent color
- There is no Open in New Tab button
- The layout works on desktop, tablet, and mobile
- Search works
- Pagination works
- Sitemap works
- RSS works
- robots.txt works
- llms.txt works
- Canonical metadata works
- Open Graph metadata works
- JSON-LD is valid and page-specific
- Authentication includes CSRF and rate limiting
- Markdown output is protected against XSS
- Uploads are validated securely
- Automated tests pass
- README contains complete setup and deployment instructions
- AGENTS.md is included
- No production-critical TODOs remain

==================================================
28. FINAL RESPONSE FORMAT
==================================================

After implementation, respond with:

1. Summary of what was built
2. Main architecture decisions
3. Created or modified files
4. Database migrations created
5. Security controls implemented
6. SEO and machine-discoverability features implemented
7. Test results
8. Exact local setup commands
9. Exact production deployment steps
10. Any assumptions or remaining limitations

Before finishing, run the available tests and inspect the generated application for obvious errors.