<?php
/**
 * PintSocial – Saved Page
 */
require_once __DIR__ . '/config.php';
require_login();

$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
$initial  = mb_strtoupper(mb_substr($username, 0, 1));

// ── Stub saved pints ────────────────────────────────────────────────────────
$savedPints = [
    ['user' => 'Brew Daily',    'handle' => '@brewdaily',  'time' => '3h',
     'body' => 'Hot take: a cold pint fixes most problems. Discuss. 👇 #CraftBeer',
     'replies' => 88, 'repints' => 124, 'likes' => 940],
    ['user' => 'Tap Room News', 'handle' => '@taproom',    'time' => '1d',
     'body' => 'New craft ales dropping this weekend at the Tap Room. Who\'s coming? 🎉 #CraftBeer #Weekend',
     'replies' => 34, 'repints' => 61, 'likes' => 412],
    ['user' => 'PintSocial',    'handle' => '@pintsocial', 'time' => '2d',
     'body' => 'Welcome to PintSocial — the social network that never runs dry. 🍻 Share your world, one pint at a time.',
     'replies' => 12, 'repints' => 47, 'likes' => 203],
    ['user' => 'Hops & Barley', 'handle' => '@hopsbarley', 'time' => '4d',
     'body' => 'Session IPAs are having a moment and we are absolutely here for it. 🍺 #HopsAndMalt #IPA',
     'replies' => 22, 'repints' => 38, 'likes' => 317],
];

$savedMedia = [
    ['from' => '#f2561d', 'to' => '#d94312', 'label' => '#CraftBeer',  'likes' => '2.1K'],
    ['from' => '#1a1a2e', 'to' => '#16213e', 'label' => '#NightPint',  'likes' => '940'],
    ['from' => '#006400', 'to' => '#228b22', 'label' => '#HopFarm',    'likes' => '654'],
    ['from' => '#b8860b', 'to' => '#ffd700', 'label' => '#GoldenAle',  'likes' => '1.3K'],
    ['from' => '#800000', 'to' => '#c0392b', 'label' => '#RedAle',     'likes' => '876'],
    ['from' => '#3d1c02', 'to' => '#8b4513', 'label' => '#Stout',      'likes' => '1.8K'],
];

require_once __DIR__ . '/components/stub_data.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>PintSocial / Saved</title>
  <meta name="description" content="Your saved pints and media on PintSocial."/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="dashboard.css"/>
  <link rel="stylesheet" href="saved.css"/>
</head>
<body>
<div class="layout">

  <!-- LEFT SIDEBAR -->
  <?php require __DIR__ . '/components/left_sidebar.php'; ?>

  <!-- MAIN: SAVED -->
  <main class="saved-main">

    <!-- Header -->
    <div class="saved-header">
      <div>
        <h2>Saved</h2>
        <div class="saved-header-sub"><?= count($savedPints) ?> pints · <?= count($savedMedia) ?> media</div>
      </div>
      <!-- Sort icon -->
      <button style="background:none;border:none;cursor:pointer;padding:8px;border-radius:50%;transition:background .15s" title="Sort"
              onmouseover="this.style.background='var(--hover-bg)'" onmouseout="this.style.background='none'">
        <svg style="width:20px;height:20px;stroke:var(--black);fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round" viewBox="0 0 24 24">
          <line x1="21" y1="10" x2="7" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="21" y1="18" x2="7" y2="18"/>
        </svg>
      </button>
    </div>

    <!-- Tabs: Pints / Media -->
    <div class="saved-tabs">
      <button class="saved-tab active" id="tabPints">Pints</button>
      <button class="saved-tab" id="tabMedia">Media</button>
    </div>

    <!-- Pints view -->
    <div id="pintsFeed">
      <?php foreach ($savedPints as $pint): ?>
      <article class="pint-card">
        <div class="avatar"><?= mb_strtoupper(mb_substr($pint['user'], 0, 1)) ?></div>
        <div style="flex:1;min-width:0">
          <div class="pint-meta">
            <span class="pint-name"><?= htmlspecialchars($pint['user']) ?></span>
            <span class="pint-handle"><?= htmlspecialchars($pint['handle']) ?></span>
            <span class="pint-dot">·</span>
            <span class="pint-time"><?= htmlspecialchars($pint['time']) ?></span>
          </div>
          <div class="pint-body"><?= preg_replace('/(#\w+)/', '<span class="hashtag">$1</span>', htmlspecialchars($pint['body'])) ?></div>
          <div class="pint-actions">
            <button class="pint-action reply">
              <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              <span><?= $pint['replies'] ?: '' ?></span>
            </button>
            <button class="pint-action repint">
              <svg viewBox="0 0 24 24"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg>
              <span><?= $pint['repints'] ?: '' ?></span>
            </button>
            <button class="pint-action like">
              <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
              <span><?= $pint['likes'] ?: '' ?></span>
            </button>
            <button class="pint-action share" title="Unsave" style="color:var(--brand)">
              <svg viewBox="0 0 24 24"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z" fill="currentColor" stroke="none"/></svg>
            </button>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>

    <!-- Media grid view -->
    <div id="mediaFeed" style="display:none">
      <div class="saved-grid">
        <?php foreach ($savedMedia as $m): ?>
        <div class="saved-cell">
          <div class="saved-cell-bg" style="background:linear-gradient(135deg,<?= $m['from'] ?>,<?= $m['to'] ?>)">
            <?= htmlspecialchars($m['label']) ?>
          </div>
          <div class="saved-cell-overlay">
            <span class="saved-cell-meta">❤ <?= $m['likes'] ?></span>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>

  </main>

  <!-- RIGHT SIDEBAR -->
  <?php require __DIR__ . '/components/right_sidebar.php'; ?>

</div>

<script>
// Tab switching
const tabPints = document.getElementById('tabPints');
const tabMedia = document.getElementById('tabMedia');
const pintsFeed = document.getElementById('pintsFeed');
const mediaFeed = document.getElementById('mediaFeed');

tabPints.addEventListener('click', () => {
  tabPints.classList.add('active'); tabMedia.classList.remove('active');
  pintsFeed.style.display = ''; mediaFeed.style.display = 'none';
});
tabMedia.addEventListener('click', () => {
  tabMedia.classList.add('active'); tabPints.classList.remove('active');
  mediaFeed.style.display = ''; pintsFeed.style.display = 'none';
});

// Like toggles
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
</script>
</body>
</html>
