<?php
/**
 * PintSocial – Dashboard (Twitter 2020 Layout)
 */
require_once __DIR__ . '/config.php';
require_login();

$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
$initial  = mb_strtoupper(mb_substr($username, 0, 1));

// ── Handle new pint submission ─────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['body'])) {
    csrf_verify();
    $body = trim($_POST['body']);
    if ($body !== '') {
        $db = get_db();
        $stmt = $db->prepare('INSERT INTO pints (user_id, body) VALUES (?, ?)');
        $stmt->execute([$_SESSION['user_id'], $body]);
    }
    redirect('dashboard.php');
}

// ── Fetch feed data ─────────────────────────────────────────────────────────
$tab = $_GET['tab'] ?? 'foryou';
$db = get_db();

if ($tab === 'following') {
    $stmt = $db->prepare('
        SELECT p.*, u.username 
        FROM pints p 
        JOIN users u ON p.user_id = u.id 
        JOIN follows f ON p.user_id = f.following_id
        WHERE f.follower_id = ?
        ORDER BY p.created_at DESC 
        LIMIT 50
    ');
    $stmt->execute([$_SESSION['user_id']]);
} else {
    $stmt = $db->query('
        SELECT p.*, u.username 
        FROM pints p 
        JOIN users u ON p.user_id = u.id 
        ORDER BY p.created_at DESC 
        LIMIT 50
    ');
}
$pints = $stmt->fetchAll();

require_once __DIR__ . '/components/stub_data.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>PintSocial / Home</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="dashboard.css"/>
</head>
<body>
<div class="layout">

  <?php require __DIR__ . '/components/left_sidebar.php'; ?>

  <!-- ══════════════════════════════
       MAIN FEED
  ══════════════════════════════ -->
  <main class="main-feed">

    <!-- Sticky header -->
    <div class="feed-header">
      <div class="feed-header-inner">
        <h2>Home</h2>
      </div>
      <div class="feed-tabs">
        <a href="dashboard.php?tab=foryou" class="feed-tab <?= $tab === 'foryou' ? 'active' : '' ?>" style="text-decoration:none; display:flex; align-items:center; justify-content:center;">For you</a>
        <a href="dashboard.php?tab=following" class="feed-tab <?= $tab === 'following' ? 'active' : '' ?>" style="text-decoration:none; display:flex; align-items:center; justify-content:center;">Following</a>
      </div>
    </div>

    <!-- Compose box -->
    <form method="POST" action="dashboard.php" class="compose-box">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      <div class="avatar lg"><?= $initial ?></div>
      <div class="compose-body">
        <textarea name="body" class="compose-input" id="composeInput"
                  placeholder="What's happening?" rows="2" required></textarea>
        <div class="compose-divider"></div>
        <div class="compose-actions">
          <div class="compose-icons">
            <!-- Image -->
            <button type="button" class="compose-icon-btn" title="Photo">
              <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            </button>
            <!-- GIF -->
            <button type="button" class="compose-icon-btn" title="GIF">
              <svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="15" rx="2" ry="2"/><polyline points="17 2 12 7 7 2"/></svg>
            </button>
            <!-- Emoji -->
            <button type="button" class="compose-icon-btn" title="Emoji">
              <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
            </button>
            <!-- Location -->
            <button type="button" class="compose-icon-btn" title="Location">
              <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </button>
          </div>
          <button type="submit" class="compose-submit" id="composeBtn">Pint</button>
        </div>
      </div>
    </form>

    <!-- Feed -->
    <?php if (empty($pints)): ?>
      <div style="padding: 32px 16px; text-align: center; color: var(--gray);">
        <p style="font-size: 18px; font-weight: 700; color: var(--black); margin-bottom: 8px;">Welcome to PintSocial!</p>
        <p>Your feed is empty. Be the first to post a pint!</p>
      </div>
    <?php else: ?>
      <?php foreach ($pints as $pint): ?>
      <article class="pint-card">
        <a href="profile.php?u=<?= urlencode($pint['username']) ?>" style="text-decoration:none; color:inherit;">
          <div class="avatar"><?= mb_strtoupper(mb_substr($pint['username'], 0, 1)) ?></div>
        </a>
        <div style="flex:1;min-width:0">
          <div class="pint-meta">
            <a href="profile.php?u=<?= urlencode($pint['username']) ?>" class="pint-name" style="text-decoration:none; color:inherit; font-weight:700;"><?= htmlspecialchars($pint['username']) ?></a>
            <span class="pint-handle">@<?= strtolower(htmlspecialchars($pint['username'])) ?></span>
            <span class="pint-dot">·</span>
            <span class="pint-time"><?= date('M j', strtotime($pint['created_at'])) ?></span>
          </div>
          <div class="pint-body">
            <?php
            // Highlight hashtags
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

  </main>

  <?php require __DIR__ . '/components/right_sidebar.php'; ?>

</div><!-- /.layout -->

<script>
// Compose submit enable/disable
const txt = document.getElementById('composeInput');
const btn = document.getElementById('composeBtn');
txt?.addEventListener('input', () => {
  btn.classList.toggle('ready', txt.value.trim().length > 0);
});

// Removed JS feed tab toggle since it's driven by PHP now

// Pint action toggles (like, repint)
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

// Compose new pint
document.getElementById('pintBtn')?.addEventListener('click', () => {
  txt.focus();
  txt.scrollIntoView({ behavior: 'smooth', block: 'center' });
});
</script>
</body>
</html>
