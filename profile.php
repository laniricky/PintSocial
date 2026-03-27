<?php
/**
 * PintSocial – Profile Page (Twitter 2020 Layout)
 */
require_once __DIR__ . '/config.php';
require_login();

$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
$initial  = mb_strtoupper(mb_substr($username, 0, 1));

// ── Stub profile data ───────────────────────────────────────────────────────
$profile = [
    'user'       => $username,
    'handle'     => '@' . strtolower($username),
    'bio'        => '🍺 Craft beer enthusiast & social brewer. Sharing one pint at a time. <span class="hashtag">#PintSocial</span> <span class="hashtag">#CraftBeer</span>',
    'location'   => 'Dublin, Ireland',
    'website'    => 'pintsocial.io',
    'joined'     => 'Joined March 2026',
    'following'  => 142,
    'followers'  => 3800,
    'pint_count' => 47,
];

$pints = [
    [
        'user'    => $username,
        'handle'  => '@' . strtolower($username),
        'time'    => '2h',
        'body'    => 'Just cracked open a fresh IPA. Life is good. 🍺 #CraftBeer #GoldenHour',
        'replies' => 4, 'repints' => 9, 'likes' => 62,
    ],
    [
        'user'    => $username,
        'handle'  => '@' . strtolower($username),
        'time'    => '5h',
        'body'    => 'Hot take: a great pint starts with great water. Change my mind. 💧🍺 #BrewFact',
        'replies' => 17, 'repints' => 33, 'likes' => 210,
    ],
    [
        'user'    => $username,
        'handle'  => '@' . strtolower($username),
        'time'    => '1d',
        'body'    => 'Exploring local tap rooms this weekend. Any recommendations? Drop them below 👇 #TapRoom #PintSocial',
        'replies' => 28, 'repints' => 11, 'likes' => 145,
    ],
    [
        'user'    => $username,
        'handle'  => '@' . strtolower($username),
        'time'    => '3d',
        'body'    => 'Just joined PintSocial! 🍺 Cheers to everyone here. #PintSocial #FirstPint',
        'replies' => 0, 'repints' => 0, 'likes' => 5,
    ],
];

require_once __DIR__ . '/components/stub_data.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>PintSocial / <?= $username ?></title>
  <meta name="description" content="<?= htmlspecialchars($profile['bio']) ?> – PintSocial profile."/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="dashboard.css"/>
  <link rel="stylesheet" href="profile.css"/>
</head>
<body>
<div class="layout">

  <?php require __DIR__ . '/components/left_sidebar.php'; ?>

  <!-- ══════════════════════════════
       MAIN: PROFILE
  ══════════════════════════════ -->
  <main class="profile-main">

    <!-- Sticky mini header -->
    <div class="profile-feed-header">
      <a href="dashboard.php" class="profile-feed-back" title="Back">
        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
      </a>
      <div class="profile-feed-header-text">
        <div class="profile-feed-header-name"><?= $username ?></div>
        <div class="profile-feed-header-count"><?= $profile['pint_count'] ?> Pints</div>
      </div>
    </div>

    <!-- Banner -->
    <div class="profile-banner">
      <div class="profile-banner-pattern"></div>
      <button class="banner-edit-btn" title="Edit banner">
        <svg style="width:16px;height:16px;display:inline;vertical-align:middle;stroke:#fff;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;margin-right:4px" viewBox="0 0 24 24">
          <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
          <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
        </svg>
        Edit
      </button>
    </div>

    <!-- Profile header -->
    <div class="profile-header">

      <!-- Avatar + Edit button row -->
      <div class="profile-avatar-row">
        <div class="profile-avatar-wrap">
          <div class="avatar profile"><?= $initial ?></div>
          <div class="profile-avatar-cam" title="Change photo">
            <svg viewBox="0 0 24 24">
              <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
              <circle cx="12" cy="13" r="4"/>
            </svg>
          </div>
        </div>
        <button class="edit-profile-btn" id="editProfileBtn">Edit profile</button>
      </div>

      <!-- Name + handle -->
      <div class="profile-info">
        <div class="profile-info-name">
          <?= $username ?>
          <!-- Verified badge -->
          <span class="profile-verified" title="Verified">
            <svg viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </span>
        </div>
        <div class="profile-info-handle"><?= $profile['handle'] ?></div>
      </div>

      <!-- Bio -->
      <p class="profile-bio"><?= $profile['bio'] ?></p>

      <!-- Meta -->
      <div class="profile-meta">
        <span class="profile-meta-item">
          <!-- Location -->
          <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          <?= htmlspecialchars($profile['location']) ?>
        </span>
        <span class="profile-meta-item">
          <!-- Link -->
          <svg viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
          <a href="https://<?= htmlspecialchars($profile['website']) ?>" target="_blank" rel="noopener"><?= htmlspecialchars($profile['website']) ?></a>
        </span>
        <span class="profile-meta-item">
          <!-- Calendar -->
          <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          <?= htmlspecialchars($profile['joined']) ?>
        </span>
      </div>

      <!-- Stats -->
      <div class="profile-stats">
        <div class="profile-stat">
          <span class="profile-stat-value"><?= number_format($profile['following']) ?></span>
          <span class="profile-stat-label">Following</span>
        </div>
        <div class="profile-stat">
          <span class="profile-stat-value"><?= number_format($profile['followers']) ?></span>
          <span class="profile-stat-label">Followers</span>
        </div>
      </div>

    </div><!-- /.profile-header -->

    <!-- Tabs -->
    <div class="profile-tabs" id="profileTabs">
      <button class="profile-tab active" data-tab="pints">Pints</button>
      <button class="profile-tab" data-tab="replies">Replies</button>
      <button class="profile-tab" data-tab="media">Media</button>
      <button class="profile-tab" data-tab="likes">Likes</button>
    </div>

    <!-- Pint feed -->
    <div id="pintsFeed">
      <?php foreach ($pints as $pint): ?>
      <article class="pint-card">
        <div class="avatar"><?= mb_strtoupper(mb_substr($pint['user'], 0, 1)) ?></div>
        <div style="flex:1;min-width:0">
          <div class="pint-meta">
            <span class="pint-name"><?= htmlspecialchars($pint['user']) ?></span>
            <span class="pint-handle"><?= htmlspecialchars($pint['handle']) ?></span>
            <span class="pint-dot">·</span>
            <span class="pint-time"><?= htmlspecialchars($pint['time']) ?></span>
          </div>
          <div class="pint-body">
            <?php
            echo preg_replace(
              '/(#\w+)/',
              '<span class="hashtag">$1</span>',
              htmlspecialchars($pint['body'])
            );
            ?>
          </div>
          <div class="pint-actions">
            <!-- Reply -->
            <button class="pint-action reply">
              <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              <span><?= $pint['replies'] ?: '' ?></span>
            </button>
            <!-- Repint -->
            <button class="pint-action repint">
              <svg viewBox="0 0 24 24"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
              <span><?= $pint['repints'] ?: '' ?></span>
            </button>
            <!-- Like -->
            <button class="pint-action like">
              <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
              <span><?= $pint['likes'] ?: '' ?></span>
            </button>
            <!-- Share -->
            <button class="pint-action share">
              <svg viewBox="0 0 24 24"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
            </button>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div><!-- /#pintsFeed -->

    <!-- Empty-state for other tabs -->
    <div id="emptyTab" style="display:none; text-align:center; padding:48px 24px; color:var(--gray);">
      <svg style="width:40px;height:40px;stroke:var(--gray);fill:none;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round;margin-bottom:12px" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <p style="font-size:17px;font-weight:700;color:var(--black);margin-bottom:6px;">Nothing here yet</p>
      <p style="font-size:15px;">Content for this tab will appear here.</p>
    </div>

  </main><!-- /.profile-main -->

  <?php require __DIR__ . '/components/right_sidebar.php'; ?>

</div><!-- /.layout -->

<script>
// ── Profile tab switching ─────────────────────────────────────────────────
const tabs     = document.querySelectorAll('.profile-tab');
const pintsFeed = document.getElementById('pintsFeed');
const emptyTab  = document.getElementById('emptyTab');

tabs.forEach(tab => {
  tab.addEventListener('click', () => {
    tabs.forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    const which = tab.dataset.tab;
    if (which === 'pints') {
      pintsFeed.style.display = '';
      emptyTab.style.display  = 'none';
    } else {
      pintsFeed.style.display = 'none';
      emptyTab.style.display  = '';
    }
  });
});

// ── Like & Repint toggles ─────────────────────────────────────────────────
document.querySelectorAll('.pint-action.like').forEach(btn => {
  btn.addEventListener('click', () => {
    const span = btn.querySelector('span');
    const isLiked = btn.dataset.liked === '1';
    btn.dataset.liked = isLiked ? '0' : '1';
    btn.style.color = isLiked ? '' : '#f91880';
    const cur = parseInt(span.textContent || '0', 10);
    span.textContent = isLiked ? (cur - 1 || '') : (cur + 1);
  });
});
document.querySelectorAll('.pint-action.repint').forEach(btn => {
  btn.addEventListener('click', () => {
    const span = btn.querySelector('span');
    const on = btn.dataset.repinted === '1';
    btn.dataset.repinted = on ? '0' : '1';
    btn.style.color = on ? '' : '#00ba7c';
    const cur = parseInt(span.textContent || '0', 10);
    span.textContent = on ? (cur - 1 || '') : (cur + 1);
  });
});

// ── Edit profile placeholder ──────────────────────────────────────────────
document.getElementById('editProfileBtn')?.addEventListener('click', () => {
  alert('Edit profile — coming soon!');
});

// ── Pint button → compose on dashboard ───────────────────────────────────
document.getElementById('pintBtn')?.addEventListener('click', () => {
  window.location.href = 'dashboard.php';
});
</script>
</body>
</html>
