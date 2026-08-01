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
  <img class="avatar" src="<?= e(asset('images/profile.jpg')) ?>" alt="Portrait of <?= e($profile['full_name'] ?? $profile['name'] ?? 'Sathira Sri Sathsara') ?>" width="636" height="637" fetchpriority="high">
  <h2><?= e($profile['full_name'] ?? $profile['name'] ?? 'Sathira Sri Sathsara') ?></h2><p class="role"><?= e($profile['professional_title'] ?? $profile['title'] ?? 'Software Engineer / Backend Developer') ?></p>
  <p><?= e($profile['bio'] ?? '') ?></p>
  <dl class="details"><div><dt>Company</dt><dd><?= e($profile['company'] ?? 'Independent') ?></dd></div><div><dt>Location</dt><dd><?= e($profile['location'] ?? 'Sri Lanka') ?></dd></div><div><dt>Local time</dt><dd><time id="local-time" data-timezone="<?= e($timezone) ?>"><?= e($localDateTime->format('H:i T')) ?></time></dd></div></dl>
  <div class="profile-links">
    <?php if (!empty($profile['email'])): ?><a class="profile-email" href="mailto:<?= e($profile['email']) ?>"><?= e($profile['email']) ?></a><?php endif ?>
  </div>
  <?php if (!empty($profile['cv_file'])): ?><a class="button full" href="<?= e(url('cv/download')) ?>">Download CV</a><?php endif ?>
  <div class="badges"><?php foreach(($profile['stack']??[]) as $tech):?><span class="badge"><?= e((string)$tech) ?></span><?php endforeach?></div>
  <div class="social-links" aria-label="Social profiles">
    <?php if (!empty($profile['github'])): ?><a class="social-icon" href="<?= e($profile['github']) ?>" rel="me noopener" aria-label="GitHub"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 .7a11.5 11.5 0 0 0-3.64 22.41c.58.1.79-.25.79-.56v-2.23c-3.22.7-3.9-1.37-3.9-1.37-.52-1.34-1.28-1.7-1.28-1.7-1.05-.72.08-.7.08-.7 1.16.08 1.77 1.19 1.77 1.19 1.03 1.77 2.7 1.26 3.36.96.1-.75.4-1.26.73-1.55-2.57-.29-5.27-1.28-5.27-5.68 0-1.26.45-2.28 1.19-3.09-.12-.29-.52-1.47.11-3.05 0 0 .97-.31 3.16 1.18a10.95 10.95 0 0 1 5.76 0c2.2-1.49 3.16-1.18 3.16-1.18.63 1.58.23 2.76.11 3.05.74.81 1.19 1.83 1.19 3.09 0 4.41-2.71 5.38-5.29 5.67.42.36.79 1.06.79 2.14v3.17c0 .31.21.67.8.56A11.5 11.5 0 0 0 12 .7Z"/></svg><span class="sr-only">GitHub</span></a><?php endif ?>
    <?php if (!empty($profile['linkedin'])): ?><a class="social-icon" href="<?= e($profile['linkedin']) ?>" rel="me noopener" aria-label="LinkedIn"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5.37 7.98H1.8V22h3.57V7.98ZM3.59 2A2.08 2.08 0 1 0 3.6 6.16 2.08 2.08 0 0 0 3.59 2ZM22.2 13.96c0-4.22-2.25-6.18-5.26-6.18-2.42 0-3.51 1.33-4.11 2.27V7.98H9.26V22h3.57v-6.94c0-1.83.35-3.61 2.62-3.61 2.24 0 2.27 2.1 2.27 3.73V22h3.57l.91-8.04Z"/></svg><span class="sr-only">LinkedIn</span></a><?php endif ?>
    <?php if (!empty($profile['x'])): ?><a class="social-icon" href="<?= e($profile['x']) ?>" rel="me noopener" aria-label="X (Twitter)"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M18.9 2H22l-6.77 7.74L23.2 22h-6.24l-4.89-6.39L6.48 22H3.36l7.26-8.3L2.98 2h6.4l4.42 5.84L18.9 2Zm-1.1 17.84h1.73L8.44 4.05H6.58L17.8 19.84Z"/></svg><span class="sr-only">X (Twitter)</span></a><?php endif ?>
    <?php if (!empty($profile['facebook'])): ?><a class="social-icon" href="<?= e($profile['facebook']) ?>" rel="me noopener" aria-label="Facebook"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.07C24 5.4 18.63 0 12 0S0 5.4 0 12.07C0 18.1 4.39 23.1 10.13 24v-8.44H7.08v-3.49h3.05V9.41c0-3.03 1.79-4.7 4.53-4.7 1.31 0 2.69.24 2.69.24v2.97h-1.51c-1.49 0-1.96.93-1.96 1.89v2.26h3.33l-.53 3.49h-2.8V24C19.61 23.1 24 18.1 24 12.07Z"/></svg><span class="sr-only">Facebook</span></a><?php endif ?>
  </div>
</aside>
