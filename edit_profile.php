<?php
/**
 * PintSocial – Edit Profile Handler & UI
 */
require_once __DIR__ . '/config.php';
require_login();

$db = get_db();
$user_id  = $_SESSION['user_id'];
$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
$initial  = mb_strtoupper(mb_substr($username, 0, 1));

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    
    $bio      = trim($_POST['bio'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $website  = trim($_POST['website'] ?? '');
    $banner   = trim($_POST['banner_color'] ?? '');
    
    // Basic validation
    $bio      = mb_substr($bio, 0, 160);
    $location = mb_substr($location, 0, 50);
    $website  = mb_substr($website, 0, 100);
    
    // Website format check (strip http/https if they put it just to save space, or let them keep it)
    $website = str_replace(['http://', 'https://'], '', $website);
    
    if (!preg_match('/^#[a-fA-F0-9]{6}$/', $banner)) {
        $banner = null;
    }

    try {
        $stmt = $db->prepare("UPDATE users SET bio = ?, location = ?, website = ?, banner_color = ? WHERE id = ?");
        $stmt->execute([$bio, $location, $website, $banner, $user_id]);
        redirect('profile.php');
    } catch (PDOException $e) {
        $error = "Failed to update profile. Please try again.";
    }
}

// Fetch current data
$stmt = $db->prepare('SELECT bio, location, website, banner_color FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$currentUser = $stmt->fetch();

$bio      = $currentUser['bio'] ?? '';
$location = $currentUser['location'] ?? '';
$website  = $currentUser['website'] ?? '';
$banner   = $currentUser['banner_color'] ?? '#333333';

require_once __DIR__ . '/components/stub_data.php';

// Force 'Profile' to be active in the nav for the active state
foreach ($navItems as &$item) {
    if ($item['label'] === 'Profile') {
        $item['active'] = true;
    } else {
        $item['active'] = false;
    }
}
unset($item);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit Profile / PintSocial</title>
  <meta name="description" content="Edit your PintSocial profile."/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="dashboard.css"/>
  <link rel="stylesheet" href="edit_profile.css"/>
</head>
<body>
<div class="layout">

  <?php require __DIR__ . '/components/left_sidebar.php'; ?>

  <!-- ══════════════════════════════
       MAIN: EDIT PROFILE
  ══════════════════════════════ -->
  <main class="edit-profile-main">

    <form method="POST" action="edit_profile.php">
      <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      
      <!-- Sticky header -->
      <div class="edit-feed-header">
        <a href="profile.php" class="edit-feed-back" title="Cancel">
          <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </a>
        <h2 class="edit-feed-title">Edit profile</h2>
        <button type="submit" class="edit-feed-save">Save</button>
      </div>

      <?php if (!empty($error)): ?>
        <div class="error-msg"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <!-- Banner -->
      <div class="edit-banner" style="background-color: <?= htmlspecialchars($banner) ?>">
        <div class="banner-color-picker">
          <label for="banner_color" class="banner-cam" title="Change banner color">
            <svg viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
          </label>
          <input type="color" id="banner_color" name="banner_color" value="<?= htmlspecialchars($banner) ?>" style="opacity:0;position:absolute;z-index:-1;">
        </div>
      </div>

      <!-- Avatar row -->
      <div class="edit-avatar-row">
        <div class="edit-avatar-wrap">
          <div class="avatar edit-avatar-circle"><?= $initial ?></div>
          <div class="edit-avatar-cam" title="Change photo">
            <svg viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
          </div>
        </div>
      </div>

      <!-- Form Fields -->
      <div class="edit-form-fields">
        
        <!-- Name (Disabled for now as per Twitter conventions sometimes) -->
        <div class="input-group">
          <label for="username">Username</label>
          <input type="text" id="username" value="<?= $username ?>" disabled>
        </div>

        <div class="input-group">
          <label for="bio">Bio</label>
          <textarea id="bio" name="bio" rows="3" maxlength="160"><?= htmlspecialchars($bio) ?></textarea>
        </div>

        <div class="input-group">
          <label for="location">Location</label>
          <input type="text" id="location" name="location" maxlength="50" value="<?= htmlspecialchars($location) ?>">
        </div>

        <div class="input-group">
          <label for="website">Website</label>
          <input type="text" id="website" name="website" maxlength="100" value="<?= htmlspecialchars($website) ?>">
        </div>

      </div>

    </form>

  </main>

  <?php require __DIR__ . '/components/right_sidebar.php'; ?>

</div>

<script>
// Live update banner color pick
const bannerColor = document.getElementById('banner_color');
const bannerHeader = document.querySelector('.edit-banner');
bannerColor.addEventListener('input', (e) => {
    bannerHeader.style.backgroundColor = e.target.value;
});
</script>
</body>
</html>
