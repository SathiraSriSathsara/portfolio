<?php
$profile = is_array($profile ?? null) ? $profile : [];
$timezone = (string) ($profile['timezone'] ?? 'Asia/Colombo');
try {
    $localDateTime = new DateTimeImmutable('now', new DateTimeZone($timezone));
} catch (Throwable) {
    $timezone = 'Asia/Colombo';
    $localDateTime = new DateTimeImmutable('now', new DateTimeZone($timezone));
}
?>
<aside class="profile card" aria-label="Profile">
  <div class="avatar" aria-hidden="true">SS</div>
  <h2><?= e($profile['full_name'] ?? $profile['name'] ?? 'Sathira Sri Sathsara') ?></h2><p class="role"><?= e($profile['professional_title'] ?? $profile['title'] ?? 'Software Engineer / Backend Developer') ?></p>
  <p><?= e($profile['bio'] ?? '') ?></p>
  <dl class="details"><div><dt>Company</dt><dd><?= e($profile['company'] ?? 'Independent') ?></dd></div><div><dt>Location</dt><dd><?= e($profile['location'] ?? 'Sri Lanka') ?></dd></div><div><dt>Local time</dt><dd><time id="local-time" data-timezone="<?= e($timezone) ?>"><?= e($localDateTime->format('H:i T')) ?></time></dd></div></dl>
  <div class="profile-links"><?php if (!empty($profile['email'])): ?><a href="mailto:<?= e($profile['email']) ?>"><?= e($profile['email']) ?></a><?php endif ?><?php if (!empty($profile['github'])): ?><a href="<?= e($profile['github']) ?>" rel="me noopener">GitHub</a><?php endif ?><?php if (!empty($profile['linkedin'])): ?><a href="<?= e($profile['linkedin']) ?>" rel="me noopener">LinkedIn</a><?php endif ?></div>
  <?php if (!empty($profile['cv_file'])): ?><a class="button full" href="<?= e(url('cv/download')) ?>">Download CV</a><?php endif ?>
  <div class="badges"><?php foreach(($profile['stack']??[]) as $tech):?><span class="badge"><?= e((string)$tech) ?></span><?php endforeach?></div>
</aside>
