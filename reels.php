<?php
/**
 * PintSocial – Reels Page
 */
require_once __DIR__ . '/config.php';
require_login();

$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
$initial  = mb_strtoupper(mb_substr($username, 0, 1));

// ── Stub reels ──────────────────────────────────────────────────────────────
$reels = [
    [
        'user'    => 'Brew Daily',    'handle' => '@brewdaily',
        'caption' => 'Morning pour ritual ☀️🍺 Nothing beats that first craft of the day. #CraftBeer #MorningBrew',
        'audio'   => 'Brew Beats Vol. 3 — BrewDaily',
        'likes'   => '12.4K', 'comments' => '342', 'shares' => '891',
        'from' => '#f2561d', 'to' => '#7a1a00', 'progress' => 62,
    ],
    [
        'user'    => 'Tap Room News', 'handle' => '@taproom',
        'caption' => 'Behind the scenes at our new tap installation 🔧🍺 #TapRoom #CraftBrewery #BehindTheScenes',
        'audio'   => 'Tap Room Sessions — Original',
        'likes'   => '8.9K', 'comments' => '217', 'shares' => '503',
        'from' => '#1a1a2e', 'to' => '#4a2080', 'progress' => 38,
    ],
    [
        'user'    => 'Hops & Barley', 'handle' => '@hopsbarley',
        'caption' => 'The perfect pour in slow motion 🐌🍺 Watch that foam settle... #HopsAndMalt #PerfectPour #ASMR',
        'audio'   => 'Lo-fi Hops — Chill Brews Mix',
        'likes'   => '23.1K', 'comments' => '641', 'shares' => '1.2K',
        'from' => '#006400', 'to' => '#003300', 'progress' => 80,
    ],
    [
        'user'    => 'Craft Corner',  'handle' => '@craftcorner',
        'caption' => 'Rating 5 IPAs so you don\'t have to 🧪🍺 Which one wins? Drop your vote below! #IPA #CraftBeer #BeerReview',
        'audio'   => 'PintSocial Originals — Craft Corner',
        'likes'   => '41.7K', 'comments' => '1.3K', 'shares' => '3.4K',
        'from' => '#b8860b', 'to' => '#6b3e00', 'progress' => 25,
    ],
];

require_once __DIR__ . '/components/stub_data.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>PintSocial / Reels</title>
  <meta name="description" content="Short brew videos on PintSocial Reels."/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="dashboard.css"/>
  <link rel="stylesheet" href="reels.css"/>
</head>
<body>
<div class="layout">

  <!-- LEFT SIDEBAR -->
  <?php require __DIR__ . '/components/left_sidebar.php'; ?>

  <!-- MAIN: REELS -->
  <main class="reels-main">

    <!-- Header -->
    <div class="reels-header">
      <h2>🎬 Reels</h2>
      <!-- Camera / record button -->
      <button style="background:none;border:none;cursor:pointer;padding:9px;border-radius:50%;transition:background .15s;display:flex;align-items:center" title="Create reel"
              onmouseover="this.style.background='var(--hover-bg)'" onmouseout="this.style.background='none'">
        <svg style="width:20px;height:20px;stroke:var(--black);fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round" viewBox="0 0 24 24">
          <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/>
        </svg>
      </button>
    </div>

    <!-- Reel cards -->
    <?php foreach ($reels as $i => $reel): ?>
    <div class="reel-card" data-index="<?= $i ?>">

      <!-- Progress bar -->
      <div class="reel-progress">
        <div class="reel-progress-fill" style="width:<?= $reel['progress'] ?>%"></div>
      </div>

      <!-- Background gradient (placeholder for video) -->
      <div class="reel-bg" style="background:linear-gradient(160deg, <?= $reel['from'] ?> 0%, <?= $reel['to'] ?> 100%)">
      </div>
      <div class="reel-foam"></div>

      <!-- Play button -->
      <div class="reel-play" id="playBtn<?= $i ?>">
        <svg viewBox="0 0 24 24"><polygon points="5 3 19 12 5 21 5 3"/></svg>
      </div>

      <!-- Right-side actions -->
      <div class="reel-actions">
        <button class="reel-action-btn" id="likeBtn<?= $i ?>">
          <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
          <span id="likeCount<?= $i ?>"><?= $reel['likes'] ?></span>
        </button>
        <button class="reel-action-btn">
          <svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
          <span><?= $reel['comments'] ?></span>
        </button>
        <button class="reel-action-btn">
          <svg viewBox="0 0 24 24"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
          <span><?= $reel['shares'] ?></span>
        </button>
        <button class="reel-action-btn">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
        </button>
      </div>

      <!-- Bottom info -->
      <div class="reel-bottom">
        <div class="reel-user">
          <div class="avatar sm"><?= mb_strtoupper(mb_substr($reel['user'], 0, 1)) ?></div>
          <div>
            <div class="reel-user-name"><?= htmlspecialchars($reel['user']) ?></div>
            <div class="reel-user-handle"><?= htmlspecialchars($reel['handle']) ?></div>
          </div>
          <button class="reel-follow-btn">Follow</button>
        </div>
        <div class="reel-caption">
          <?= preg_replace('/(#\w+)/', '<span class="hashtag">$1</span>', htmlspecialchars($reel['caption'])) ?>
        </div>
        <div class="reel-audio">
          <svg viewBox="0 0 24 24"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
          <?= htmlspecialchars($reel['audio']) ?>
        </div>
      </div>

    </div>
    <?php endforeach; ?>

  </main>

  <!-- RIGHT SIDEBAR -->
  <?php require __DIR__ . '/components/right_sidebar.php'; ?>

</div>

<script>
// ── Play / pause toggle per card ──────────────────────────────────────────
document.querySelectorAll('.reel-card').forEach((card, idx) => {
  let playing = false;
  let progress = parseInt(card.querySelector('.reel-progress-fill').style.width) || 0;
  let interval = null;
  const fill    = card.querySelector('.reel-progress-fill');
  const playBtn = card.querySelector('.reel-play');

  function tick() {
    progress = Math.min(progress + 0.3, 100);
    fill.style.width = progress + '%';
    if (progress >= 100) { clearInterval(interval); playing = false; playBtn.style.opacity = '1'; }
  }

  card.addEventListener('click', e => {
    if (e.target.closest('.reel-actions') || e.target.closest('.reel-follow-btn')) return;
    playing = !playing;
    playBtn.style.opacity = playing ? '0' : '1';
    if (playing) { interval = setInterval(tick, 100); }
    else { clearInterval(interval); }
  });
});

// ── Like toggle ───────────────────────────────────────────────────────────
document.querySelectorAll('.reel-action-btn').forEach((btn, i) => {
  if (!btn.id?.startsWith('likeBtn')) return;
  btn.addEventListener('click', e => {
    e.stopPropagation();
    btn.classList.toggle('liked');
  });
});

// ── Follow toggle ─────────────────────────────────────────────────────────
document.querySelectorAll('.reel-follow-btn').forEach(btn => {
  btn.addEventListener('click', e => {
    e.stopPropagation();
    const on = btn.dataset.following === '1';
    btn.dataset.following = on ? '0' : '1';
    btn.textContent = on ? 'Follow' : 'Following';
    btn.style.background = on ? '' : 'rgba(255,255,255,0.2)';
  });
});
</script>
</body>
</html>
