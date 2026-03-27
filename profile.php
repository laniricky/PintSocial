<?php
/**
 * PintSocial – Profile Page (Twitter 2020 Layout)
 */
require_once __DIR__ . '/config.php';
require_login();

$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
$initial  = mb_strtoupper(mb_substr($username, 0, 1));

// ── Fetch dynamic profile data ───────────────────────────────────────────────
$db = get_db();
$target_user = $_GET['u'] ?? $username;

$stmt = $db->prepare('SELECT id, username, bio, location, website, banner_color, DATE_FORMAT(created_at, "%M %Y") as join_month FROM users WHERE username = ?');
$stmt->execute([$target_user]);
$userData = $stmt->fetch();

if (!$userData) {
    die("User not found.");
}

$profile_id       = $userData['id'];
$profile_username = htmlspecialchars($userData['username']);
$profile_initial  = mb_strtoupper(mb_substr($profile_username, 0, 1));
$profile_handle   = '@' . strtolower($profile_username);
$profile_bio      = $userData['bio'] ? htmlspecialchars($userData['bio']) : 'No bio yet.';
$profile_location = $userData['location'] ? htmlspecialchars($userData['location']) : 'Earth';
$profile_website  = $userData['website'] ? htmlspecialchars($userData['website']) : '';
$profile_joined   = 'Joined ' . $userData['join_month'];
$profile_banner   = $userData['banner_color'] ?? '#f2561d';

// Fetch their pints
$stmt = $db->prepare('
    SELECT p.*, u.username 
    FROM pints p 
    JOIN users u ON p.user_id = u.id 
    WHERE p.user_id = ? 
    ORDER BY p.created_at DESC
');
$stmt->execute([$profile_id]);
$pints = $stmt->fetchAll();

require_once __DIR__ . '/components/stub_data.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>PintSocial / <?= $profile_username ?></title>
  <meta name="description" content="<?= htmlspecialchars($profile_bio) ?> – PintSocial profile."/>
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
        <div class="profile-feed-header-name"><?= $profile_username ?></div>
        <div class="profile-feed-header-count"><?= count($pints) ?> Pints</div>
      </div>
    </div>

    <!-- Banner -->
    <div class="profile-banner" style="background-color: <?= htmlspecialchars($profile_banner) ?>">
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
          <div class="avatar profile"><?= $profile_initial ?></div>
          <?php if ($profile_username === $username): ?>
          <div class="profile-avatar-cam" title="Change photo">
            <svg viewBox="0 0 24 24">
              <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
              <circle cx="12" cy="13" r="4"/>
            </svg>
          </div>
          <?php endif; ?>
        </div>
        <?php if ($profile_username === $username): ?>
          <a href="edit_profile.php" class="edit-profile-btn" style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center; box-sizing:border-box;">Edit profile</a>
        <?php else: ?>
          <button class="edit-profile-btn">Follow</button>
        <?php endif; ?>
      </div>

      <!-- Name + handle -->
      <div class="profile-info">
        <div class="profile-info-name">
          <?= $profile_username ?>
          <!-- Verified badge (stub for now) -->
          <span class="profile-verified" title="Verified">
            <svg viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </span>
        </div>
        <div class="profile-info-handle"><?= $profile_handle ?></div>
      </div>

      <!-- Bio -->
      <p class="profile-bio"><?= preg_replace('/(#\w+)/', '<span class="hashtag">$1</span>', $profile_bio) ?></p>

      <!-- Meta -->
      <div class="profile-meta">
        <span class="profile-meta-item">
          <!-- Location -->
          <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          <?= $profile_location ?>
        </span>
        <?php if ($profile_website): ?>
        <span class="profile-meta-item">
          <!-- Link -->
          <svg viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
          <a href="https://<?= $profile_website ?>" target="_blank" rel="noopener"><?= $profile_website ?></a>
        </span>
        <?php endif; ?>
        <span class="profile-meta-item">
          <!-- Calendar -->
          <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          <?= $profile_joined ?>
        </span>
      </div>

      <!-- Stats (stubbed for now) -->
      <div class="profile-stats">
        <div class="profile-stat">
          <span class="profile-stat-value">142</span>
          <span class="profile-stat-label">Following</span>
        </div>
        <div class="profile-stat">
          <span class="profile-stat-value">3,800</span>
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
      <?php if (empty($pints)): ?>
        <div style="padding: 32px 16px; text-align: center; color: var(--gray);">
          <p style="font-size: 18px; font-weight: 700; color: var(--black); margin-bottom: 8px;">No pints yet</p>
          <p>When they post, their pints will show up here.</p>
        </div>
      <?php else: ?>
        <?php foreach ($pints as $pint): ?>
        <article class="pint-card">
          <div class="avatar"><?= mb_strtoupper(mb_substr($pint['username'], 0, 1)) ?></div>
          <div style="flex:1;min-width:0">
            <div class="pint-meta">
              <span class="pint-name"><?= htmlspecialchars($pint['username']) ?></span>
              <span class="pint-handle">@<?= strtolower(htmlspecialchars($pint['username'])) ?></span>
              <span class="pint-dot">·</span>
              <span class="pint-time"><?= date('M j', strtotime($pint['created_at'])) ?></span>
            </div>
            <div class="pint-body">
              <?php
              echo preg_replace(
                '/(#\w+)/',
                '<span class="hashtag">$1</span>',
                nl2br(htmlspecialchars($pint['body']))
              );
              ?>
            </div>
            <div class="pint-actions">
              <!-- Reply -->
              <button class="pint-action reply">
                <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                <span><?= $pint['replies_count'] ?: '' ?></span>
              </button>
              <!-- Repint -->
              <button class="pint-action repint">
                <svg viewBox="0 0 24 24"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
                <span><?= $pint['repints_count'] ?: '' ?></span>
              </button>
              <!-- Like -->
              <button class="pint-action like">
                <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                <span><?= $pint['likes_count'] ?: '' ?></span>
              </button>
              <!-- Share -->
              <button class="pint-action share">
                <svg viewBox="0 0 24 24"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
              </button>
            </div>
          </div>
        </article>
        <?php endforeach; ?>
      <?php endif; ?>
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

// ── Remove Edit profile placeholder ──────────────────────────────────────────────

// ── Pint button → compose on dashboard ───────────────────────────────────
document.getElementById('pintBtn')?.addEventListener('click', () => {
  window.location.href = 'dashboard.php';
});
</script>
</body>
</html>
