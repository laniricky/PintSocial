<?php
/**
 * PintSocial – Notifications Page
 */
require_once __DIR__ . '/config.php';
require_login();

$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
$initial  = mb_strtoupper(mb_substr($username, 0, 1));

// ── Stub notifications data ─────────────────────────────────────────────────
$notifications = [
    [
        'type'    => 'like',
        'users'   => ['Brew Daily', 'Tap Room News'],
        'text'    => 'liked your pint',
        'snippet' => 'Just cracked open a fresh IPA. Life is good. 🍺 #CraftBeer #GoldenHour',
        'unread'  => true,
        'verified'=> true,
    ],
    [
        'type'    => 'repint',
        'users'   => ['Hops & Barley'],
        'text'    => 'repinted your pint',
        'snippet' => 'Hot take: a great pint starts with great water. Change my mind. 💧🍺 #BrewFact',
        'unread'  => true,
        'verified'=> false,
    ],
    [
        'type'    => 'follow',
        'users'   => ['Craft Corner'],
        'text'    => 'followed you',
        'snippet' => '',
        'unread'  => false,
        'verified'=> true,
    ],
    [
        'type'    => 'mention',
        'users'   => ['Brew Daily'],
        'text'    => 'mentioned you',
        'snippet' => '@pintsocial is the best place to talk about beer!',
        'unread'  => false,
        'verified'=> true,
    ],
    [
        'type'    => 'like',
        'users'   => ['Ale Enthusiast', 'Stout Lover', 'Lager Fan'],
        'text'    => 'liked your reply',
        'snippet' => 'Totally agree, the hops make all the difference 🍺',
        'unread'  => false,
        'verified'=> false,
    ]
];

require_once __DIR__ . '/components/stub_data.php';

// SVG icons for different notification types
$notifIcons = [
    'like'    => '<path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>',
    'repint'  => '<polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/>',
    'follow'  => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
    'mention' => '<path d="M22 2L11 13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/>', // Using send icon for mention as placeholder
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>PintSocial / Notifications</title>
  <meta name="description" content="Your PintSocial notifications."/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="dashboard.css"/>
  <link rel="stylesheet" href="notifications.css"/>
</head>
<body>
<div class="layout">

  <!-- LEFT SIDEBAR -->
  <?php require __DIR__ . '/components/left_sidebar.php'; ?>

  <!-- MAIN: NOTIFICATIONS -->
  <main class="notif-main">

    <!-- Header -->
    <div class="notif-header">
      <h2>Notifications</h2>
      <button class="notif-settings-btn" title="Settings">
        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
      </button>
    </div>

    <!-- Tabs -->
    <div class="notif-tabs">
      <button class="notif-tab active" data-tab="all">All</button>
      <button class="notif-tab" data-tab="verified">Verified</button>
      <button class="notif-tab" data-tab="mentions">Mentions</button>
    </div>

    <!-- All Notifications Feed -->
    <div id="feed-all">
      <?php foreach ($notifications as $n): ?>
      <article class="notif-item <?= $n['unread'] ? 'unread' : '' ?> <?= $n['verified'] ? 'verified' : '' ?> <?= $n['type'] === 'mention' ? 'mention-item' : '' ?>">
        
        <!-- Left icon -->
        <div class="notif-icon-col <?= $n['type'] ?>">
          <svg viewBox="0 0 24 24"><?= $notifIcons[$n['type']] ?></svg>
        </div>
        
        <!-- Right content -->
        <div class="notif-content-col">
          <div class="notif-avatars">
            <?php foreach (array_slice($n['users'], 0, 3) as $user): ?>
              <div class="avatar sm"><?= mb_strtoupper(mb_substr($user, 0, 1)) ?></div>
            <?php endforeach; ?>
            <?php if (count($n['users']) > 3): ?>
              <div class="avatar sm" style="font-size:10px; background:var(--border); color:var(--gray)">+<?= count($n['users'])-3 ?></div>
            <?php endif; ?>
          </div>
          
          <div class="notif-text">
            <?php
              $firstUser = $n['users'][0];
              $otherCount = count($n['users']) - 1;
              $userText = "<span class=\"notif-user\">" . htmlspecialchars($firstUser) . "</span>";
              if ($otherCount === 1) {
                  $userText .= " and 1 other";
              } elseif ($otherCount > 1) {
                  $userText .= " and {$otherCount} others";
              }
              echo $userText . " " . htmlspecialchars($n['text']);
            ?>
          </div>
          
          <?php if ($n['snippet']): ?>
          <div class="notif-snippet">
            <?= preg_replace('/(#\w+|@\w+)/', '<span class="hashtag">$1</span>', htmlspecialchars($n['snippet'])) ?>
          </div>
          <?php endif; ?>
        </div>

      </article>
      <?php endforeach; ?>
    </div><!-- /#feed-all -->

    <!-- Empty States for other tabs -->
    <div id="feed-verified" style="display:none;" class="notif-empty">
      <svg viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      <h3>Nothing to see here — yet</h3>
      <p>Likes, mentions, Repints, and a whole lot more from verified users will appear here.</p>
    </div>

    <div id="feed-mentions" style="display:none;" class="notif-empty">
      <svg viewBox="0 0 24 24"><path d="M22 2L11 13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
      <h3>Nothing to see here — yet</h3>
      <p>When someone mentions you, you’ll find it here.</p>
    </div>

  </main>

  <!-- RIGHT SIDEBAR -->
  <?php require __DIR__ . '/components/right_sidebar.php'; ?>

</div>

<script>
// ── Tab Switching & Filtering ─────────────────────────────────────────────
const tabs = document.querySelectorAll('.notif-tab');
const feedAll = document.getElementById('feed-all');
const feedVerified = document.getElementById('feed-verified');
const feedMentions = document.getElementById('feed-mentions');

tabs.forEach(tab => {
  tab.addEventListener('click', () => {
    // UI update
    tabs.forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    
    const which = tab.dataset.tab;
    
    // Hide all empty states initially
    feedVerified.style.display = 'none';
    feedMentions.style.display = 'none';
    
    if (which === 'all') {
      feedAll.style.display = '';
      // Show all items
      document.querySelectorAll('.notif-item').forEach(el => el.style.display = 'flex');
      
    } else if (which === 'verified') {
      feedAll.style.display = '';
      let hasVisible = false;
      document.querySelectorAll('.notif-item').forEach(el => {
        if (el.classList.contains('verified')) {
          el.style.display = 'flex';
          hasVisible = true;
        } else {
          el.style.display = 'none';
        }
      });
      // Fallback empty state
      if (!hasVisible) {
        feedAll.style.display = 'none';
        feedVerified.style.display = '';
      }
      
    } else if (which === 'mentions') {
      feedAll.style.display = '';
      let hasVisible = false;
      document.querySelectorAll('.notif-item').forEach(el => {
        if (el.classList.contains('mention-item')) {
          el.style.display = 'flex';
          hasVisible = true;
        } else {
          el.style.display = 'none';
        }
      });
      // Fallback empty state
      if (!hasVisible) {
        feedAll.style.display = 'none';
        feedMentions.style.display = '';
      }
    }
  });
});
</script>
</body>
</html>
