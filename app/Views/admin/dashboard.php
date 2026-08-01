<?php
$total = max(1, (int) ($counts['total'] ?? 0));
$publishedPercent = round(((int) ($counts['published'] ?? 0) / $total) * 100);
$draftPercent = round(((int) ($counts['drafts'] ?? 0) / $total) * 100);
$scheduledPercent = round(((int) ($counts['scheduled'] ?? 0) / $total) * 100);
$maxMonthly = max(1, ...array_map(static fn(array $month): int => (int) $month['total'], $monthly));
$mediaBytes = (int) ($resources['media_bytes'] ?? 0);
$mediaSize = $mediaBytes >= 1048576 ? number_format($mediaBytes / 1048576, 1) . ' MB' : number_format($mediaBytes / 1024, 1) . ' KB';
?>
<header class="portal-header">
  <div>
    <p class="portal-breadcrumb">Portfolio Admin <span>/</span> Overview</p>
    <h1>Dashboard</h1>
    <p class="portal-subtitle">Content health, publishing activity, and portfolio resources at a glance.</p>
  </div>
  <div class="portal-actions">
    <a class="button secondary" href="<?= e(url()) ?>">View portfolio</a>
    <a class="button" href="<?= e(url('admin/posts/create')) ?>">+ Create post</a>
  </div>
</header>

<section class="portal-stats" aria-label="Content statistics">
  <?php foreach ([
      ['key'=>'total','label'=>'Total posts','note'=>'All active content','tone'=>'purple'],
      ['key'=>'published','label'=>'Published','note'=>$publishedPercent.'% of content','tone'=>'green'],
      ['key'=>'drafts','label'=>'Drafts','note'=>'Waiting for review','tone'=>'blue'],
      ['key'=>'scheduled','label'=>'Scheduled','note'=>'Queued to publish','tone'=>'orange'],
  ] as $stat): ?>
    <article class="portal-stat <?= e($stat['tone']) ?>">
      <div class="portal-stat-icon" aria-hidden="true"><span></span></div>
      <div><p><?= e($stat['label']) ?></p><strong><?= (int) ($counts[$stat['key']] ?? 0) ?></strong><small><?= e($stat['note']) ?></small></div>
    </article>
  <?php endforeach ?>
</section>

<div class="portal-grid portal-grid-primary">
  <section class="portal-card publishing-chart">
    <header class="portal-card-header"><div><p class="kicker">Publishing trend</p><h2>Posts published</h2></div><span class="period-label">Last 6 months</span></header>
    <div class="bar-chart" role="img" aria-label="Posts published during the last six months">
      <?php foreach ($monthly as $month): ?><div class="bar-column"><div class="bar-value"><?= (int) $month['total'] ?></div><div class="bar-track"><span style="height:<?= max(4, ((int)$month['total'] / $maxMonthly) * 100) ?>%"></span></div><small><?= e($month['label']) ?></small></div><?php endforeach ?>
    </div>
  </section>

  <section class="portal-card content-health">
    <header class="portal-card-header"><div><p class="kicker">Content health</p><h2>Publishing status</h2></div></header>
    <div class="donut-layout">
      <div class="status-donut" style="--published:<?= $publishedPercent ?>;--draft-end:<?= min(100,$publishedPercent+$draftPercent) ?>;--scheduled-end:<?= min(100,$publishedPercent+$draftPercent+$scheduledPercent) ?>" role="img" aria-label="<?= $publishedPercent ?> percent published"><span><strong><?= (int)($counts['total']??0) ?></strong><small>posts</small></span></div>
      <ul class="status-legend"><li><i class="green"></i><span>Published</span><strong><?= (int)($counts['published']??0) ?></strong></li><li><i class="blue"></i><span>Draft</span><strong><?= (int)($counts['drafts']??0) ?></strong></li><li><i class="orange"></i><span>Scheduled</span><strong><?= (int)($counts['scheduled']??0) ?></strong></li><li><i class="gray"></i><span>Archived</span><strong><?= (int)($counts['archived']??0) ?></strong></li></ul>
    </div>
  </section>
</div>

<div class="portal-grid portal-grid-secondary">
  <section class="portal-card recent-content">
    <header class="portal-card-header"><div><p class="kicker">Content</p><h2>Recently updated</h2></div><a href="<?= e(url('admin/posts')) ?>">View all →</a></header>
    <?php if (!$items): ?><div class="portal-empty"><strong>No posts yet</strong><p>Create your first article to start building the content overview.</p><a href="<?= e(url('admin/posts/create')) ?>">Create a post</a></div><?php else: ?>
      <div class="table-wrap"><table class="portal-table"><thead><tr><th>Post</th><th>Status</th><th>Author</th><th>Updated</th><th></th></tr></thead><tbody><?php foreach($items as $item):?><tr><td><a class="post-title" href="<?= e(url('admin/posts/'.$item['id'].'/edit')) ?>"><?= e($item['title']) ?></a><small>/<?= e($item['slug']) ?></small></td><td><span class="status-badge status-<?= e($item['status']) ?>"><?= e($item['status']) ?></span></td><td><?= e($item['author_name']) ?></td><td><?= e(date('M j, Y',strtotime($item['updated_at']))) ?></td><td><a class="row-action" href="<?= e(url('admin/posts/'.$item['id'].'/edit')) ?>" aria-label="Edit <?= e($item['title']) ?>">•••</a></td></tr><?php endforeach?></tbody></table></div>
    <?php endif ?>
  </section>

  <section class="portal-card activity-feed">
    <header class="portal-card-header"><div><p class="kicker">Audit log</p><h2>Recent activity</h2></div></header>
    <?php if (!$activity): ?><div class="portal-empty compact"><p>Activity will appear after you create or update content.</p></div><?php else: ?><ol><?php foreach($activity as $event):?><li><span class="activity-marker"></span><div><p><strong><?= e(ucfirst($event['action'])) ?></strong> <?= e($event['entity_type']??'content') ?><?php if($event['entity_id']):?> #<?= (int)$event['entity_id'] ?><?php endif?></p><small><?= e($event['user_name']??'System') ?> · <?= e(date('M j, H:i',strtotime($event['created_at']))) ?></small></div></li><?php endforeach?></ol><?php endif ?>
  </section>
</div>

<section class="resource-strip" aria-label="Portfolio resources">
  <div><span>Categories</span><strong><?= (int)($resources['categories']??0) ?></strong></div>
  <div><span>Tags</span><strong><?= (int)($resources['tags']??0) ?></strong></div>
  <div><span>Media files</span><strong><?= (int)($resources['media']??0) ?></strong></div>
  <div><span>Media storage</span><strong><?= e($mediaSize) ?></strong></div>
  <div><span>Environment</span><strong class="environment-status">Production ready</strong></div>
</section>
