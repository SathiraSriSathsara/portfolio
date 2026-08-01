<?php
$currentPath = trim((string) parse_url($_SERVER['REQUEST_URI'] ?? '/admin', PHP_URL_PATH), '/');
$navItems = [
    ['label' => 'Dashboard', 'href' => 'admin', 'match' => 'admin', 'icon' => '<path d="M3 13h8V3H3v10Zm0 8h8v-6H3v6Zm10 0h8V11h-8v10Zm0-18v6h8V3h-8Z"/>'],
    ['label' => 'Posts', 'href' => 'admin/posts', 'match' => 'admin/posts', 'icon' => '<path d="M5 3h10l4 4v14H5V3Zm9 1.5V8h3.5M8 12h8M8 16h8"/>'],
    ['label' => 'Settings', 'href' => 'admin/settings', 'match' => 'admin/settings', 'icon' => '<path d="M12 8.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm8.5 3.5-.1-1.3 2-1.6-2-3.4-2.5 1a9 9 0 0 0-2.2-1.3L15.3 3h-4l-.4 2.4a9 9 0 0 0-2.2 1.3l-2.5-1-2 3.4 2 1.6a9 9 0 0 0 0 2.6l-2 1.6 2 3.4 2.5-1a9 9 0 0 0 2.2 1.3l.4 2.4h4l.4-2.4a9 9 0 0 0 2.2-1.3l2.5 1 2-3.4-2-1.6.1-.1V12Z"/>'],
];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title><?= e($title) ?> · Admin</title>
  <link rel="icon" type="image/png" href="<?= e(asset('images/favicon-bw.png')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/app.css')) ?>">
  <link rel="stylesheet" href="<?= e(asset('css/admin-modern.css')) ?>">
  <script defer src="<?= e(asset('js/admin.js')) ?>"></script>
</head>
<body class="admin-dashboard">
  <a class="skip" href="#content">Skip to content</a>
  <div class="admin-shell">
    <aside class="admin-nav">
      <a class="brand" href="<?= e(url('admin')) ?>">Sathira<span>.</span></a>
      <p class="admin-nav-label">Workspace</p>
      <nav aria-label="Administration">
        <?php foreach ($navItems as $item): ?>
          <?php $active = $item['match'] === 'admin' ? $currentPath === 'admin' : str_starts_with($currentPath, $item['match']); ?>
          <a href="<?= e(url($item['href'])) ?>" <?= $active ? 'aria-current="page"' : '' ?>>
            <svg viewBox="0 0 24 24" aria-hidden="true"><?= $item['icon'] ?></svg>
            <span><?= e($item['label']) ?></span>
          </a>
        <?php endforeach ?>
        <a href="<?= e(url()) ?>">
          <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 4h6v6M20 4l-9 9M18 13v7H4V6h7"/></svg>
          <span>View site</span>
        </a>
      </nav>
      <form method="post" action="<?= e(url('admin/logout')) ?>"><?= csrf_field() ?><button class="admin-signout"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 4H4v16h6M14 8l4 4-4 4M18 12H8"/></svg><span>Sign out</span></button></form>
    </aside>
    <main id="content" class="admin-main">
      <header class="admin-commandbar">
        <form class="admin-search" action="<?= e(url('search')) ?>" method="get">
          <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m16 16 4 4"/></svg>
          <label class="sr-only" for="admin-search">Search portfolio content</label>
          <input id="admin-search" name="q" type="search" placeholder="Search portfolio content">
        </form>
        <div class="admin-user"><span class="admin-user-copy"><strong><?= e($_SESSION['user']['name'] ?? 'Administrator') ?></strong><small>Portfolio owner</small></span><img src="<?= e(asset('images/profile.jpg')) ?>" alt="" width="34" height="34"></div>
      </header>
      <div class="admin-content"><?= $content ?></div>
    </main>
  </div>
</body>
</html>
