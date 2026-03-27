<?php
/**
 * PintSocial – Network Layout (Followers / Following)
 */
require_once __DIR__ . '/config.php';
require_login();

$db = get_db();
$my_id = $_SESSION['user_id'];
$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
$initial  = mb_strtoupper(mb_substr($username, 0, 1));

$target_user = $_GET['u'] ?? $username;
$tab = $_GET['tab'] ?? 'followers';
if (!in_array($tab, ['followers', 'following'])) {
    $tab = 'followers';
}

// Fetch the targeted profile to get their ID and handle
$stmt = $db->prepare('SELECT id, username FROM users WHERE username = ?');
$stmt->execute([$target_user]);
$profile = $stmt->fetch();

if (!$profile) {
    die("User not found.");
}

$profile_id       = $profile['id'];
$profile_username = htmlspecialchars($profile['username']);
$profile_handle   = '@' . strtolower($profile_username);

// Fetch the list of users
if ($tab === 'followers') {
    // Who follows the target
    $stmt = $db->prepare('
        SELECT u.id, u.username, u.bio 
        FROM follows f 
        JOIN users u ON f.follower_id = u.id 
        WHERE f.following_id = ?
        ORDER BY f.created_at DESC
    ');
} else {
    // Who the target follows
    $stmt = $db->prepare('
        SELECT u.id, u.username, u.bio 
        FROM follows f 
        JOIN users u ON f.following_id = u.id 
        WHERE f.follower_id = ?
        ORDER BY f.created_at DESC
    ');
}
$stmt->execute([$profile_id]);
$users_list = $stmt->fetchAll();

// Fetch our *own* follows to see if we follow these users
$stmt = $db->prepare('SELECT following_id FROM follows WHERE follower_id = ?');
$stmt->execute([$my_id]);
$my_following_raw = $stmt->fetchAll(PDO::FETCH_COLUMN);
$my_following = array_flip($my_following_raw); // Fast lookup

require_once __DIR__ . '/components/stub_data.php';

// Force 'Profile' active if we are viewing our own network
if ($profile_username === $username) {
    foreach ($navItems as &$item) {
        $item['active'] = ($item['label'] === 'Profile');
    }
    unset($item);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>People following <?= $profile_username ?> / PintSocial</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="dashboard.css"/>
  <link rel="stylesheet" href="network.css"/>
</head>
<body>
<div class="layout">

  <?php require __DIR__ . '/components/left_sidebar.php'; ?>

  <!-- ══════════════════════════════
       MAIN: NETWORK
  ══════════════════════════════ -->
  <main class="network-main">

    <!-- Sticky header -->
    <div class="network-header">
      <a href="profile.php?u=<?= urlencode($profile_username) ?>" class="network-back" title="Back">
        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
      </a>
      <div class="network-header-text">
        <div class="network-header-name"><?= $profile_username ?></div>
        <div class="network-header-handle"><?= $profile_handle ?></div>
      </div>
    </div>
    
    <!-- Tabs -->
    <div class="network-tabs">
      <a href="network.php?u=<?= urlencode($profile_username) ?>&tab=followers" 
         class="network-tab <?= $tab === 'followers' ? 'active' : '' ?>">Followers</a>
      <a href="network.php?u=<?= urlencode($profile_username) ?>&tab=following" 
         class="network-tab <?= $tab === 'following' ? 'active' : '' ?>">Following</a>
    </div>

    <!-- User List -->
    <div class="network-list">
      <?php if (empty($users_list)): ?>
        <div class="network-empty">
           <p style="font-size:31px;font-weight:800;margin-bottom:8px;">Looking for followers?</p>
           <p style="color:var(--gray);font-size:15px;">When someone follows them, they'll show up here. Posting and engaging with others helps boost followers.</p>
        </div>
      <?php else: ?>
        <?php foreach ($users_list as $u): 
            $u_username = htmlspecialchars($u['username']);
            $u_initial = mb_strtoupper(mb_substr($u_username, 0, 1));
            $u_handle = '@' . strtolower($u_username);
            $u_bio = $u['bio'] ? htmlspecialchars($u['bio']) : '';
            
            $is_me = ($u['id'] == $my_id);
            $is_following_u = isset($my_following[$u['id']]);
        ?>
        <article class="user-card" onclick="window.location.href='profile.php?u=<?= urlencode($u_username) ?>'">
          <div class="avatar"><?= $u_initial ?></div>
          <div class="user-card-content">
            <div class="user-card-header">
              <div class="user-card-names">
                <span class="user-card-name"><?= $u_username ?></span>
                <span class="user-card-handle"><?= $u_handle ?></span>
              </div>
              <?php if (!$is_me): ?>
                <button class="user-follow-btn" 
                        data-id="<?= $u['id'] ?>" 
                        data-following="<?= $is_following_u ? '1' : '0' ?>"
                        onclick="toggleFollow(event, this)">
                  <?= $is_following_u ? 'Following' : 'Follow' ?>
                </button>
              <?php endif; ?>
            </div>
            <?php if ($u_bio): ?>
              <div class="user-card-bio">
                <?= preg_replace('/(#\w+)/', '<span class="hashtag">$1</span>', $u_bio); ?>
              </div>
            <?php endif; ?>
          </div>
        </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </main>

  <?php require __DIR__ . '/components/right_sidebar.php'; ?>

</div>

<script>
// Prevent card click when clicking the follow button
async function toggleFollow(e, btn) {
  e.stopPropagation(); // prevent triggering the onclick on the parent article
  
  const isFollowing = btn.dataset.following === '1';
  const action = isFollowing ? 'unfollow' : 'follow';
  const targetId = btn.dataset.id;
  
  // Optimistic UI update
  btn.dataset.following = isFollowing ? '0' : '1';
  btn.textContent = isFollowing ? 'Follow' : 'Following';
  btn.style.color = ''; 
  btn.style.borderColor = '';
  btn.style.backgroundColor = '';
  
  try {
    const fd = new FormData();
    fd.append('target_id', targetId);
    fd.append('action', action);
    fd.append('csrf_token', '<?= csrf_token() ?>'); // Pass the CSRF token
    
    const res = await fetch('ajax_follow.php', { method: 'POST', body: fd });
    if (!res.ok) throw new Error('Network error');
  } catch (err) {
    // Revert
    btn.dataset.following = isFollowing ? '1' : '0';
    btn.textContent = isFollowing ? 'Following' : 'Follow';
  }
}

// Hover effects for the buttons
document.querySelectorAll('.user-follow-btn').forEach(btn => {
  btn.addEventListener('mouseenter', () => {
    if (btn.dataset.following === '1') {
      btn.textContent = 'Unfollow';
      btn.style.color = '#f4212e';
      btn.style.borderColor = '#fdc9ce';
      btn.style.backgroundColor = 'rgba(244, 33, 46, 0.1)';
    }
  });
  btn.addEventListener('mouseleave', () => {
    if (btn.dataset.following === '1') {
      btn.textContent = 'Following';
      btn.style.color = '';
      btn.style.borderColor = '';
      btn.style.backgroundColor = '';
    } else {
      btn.textContent = 'Follow';
    }
  });
});
</script>
</body>
</html>
