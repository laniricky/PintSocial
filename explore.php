<?php
/**
 * PintSocial – Explore Page (Twitter 2020 Layout)
 */
require_once __DIR__ . '/config.php';
require_login();

$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
$initial  = mb_strtoupper(mb_substr($username, 0, 1));

// ── Stub data ───────────────────────────────────────────────────────────────
$heroTrend = ['tag' => '#FridayVibes', 'category' => 'Trending worldwide', 'count' => '101K Pints'];

$trending = [
    ['num' => 1,  'tag' => '#PintSocial',   'category' => 'Trending · PintSocial', 'count' => '12.4K Pints'],
    ['num' => 2,  'tag' => '#CraftBeer',    'category' => 'Food & Drink',          'count' => '8.9K Pints'],
    ['num' => 3,  'tag' => '#BrewCulture',  'category' => 'Trending · Ireland',    'count' => '3.2K Pints'],
    ['num' => 4,  'tag' => '#TGIF',         'category' => 'Trending worldwide',    'count' => '54.1K Pints'],
    ['num' => 5,  'tag' => '#HopsAndMalt',  'category' => 'Food & Drink',          'count' => '1.8K Pints'],
    ['num' => 6,  'tag' => '#TapRoom',      'category' => 'Trending · Dublin',     'count' => '6.7K Pints'],
];

$people = [
    ['user' => 'Brew Daily',    'handle' => '@brewdaily',  'bio' => 'Your daily dose of craft beer news 🍺'],
    ['user' => 'Tap Room News', 'handle' => '@taproom',    'bio' => 'Events, tastings, and tap lists 🎉'],
    ['user' => 'Hops & Barley', 'handle' => '@hopsbarley', 'bio' => 'Exploring the world of hops, one pint at a time'],
    ['user' => 'Craft Corner',  'handle' => '@craftcorner', 'bio' => 'Independent craft beer reviews & ratings'],
];

// Gradient palettes for the media grid cells
$mediaGradients = [
    ['from' => '#f2561d', 'to' => '#d94312', 'label' => '#CraftBeer',   'likes' => '2.1K'],
    ['from' => '#1a1a2e', 'to' => '#16213e', 'label' => '#NightPint',   'likes' => '940'],
    ['from' => '#006400', 'to' => '#228b22', 'label' => '#HopFarm',     'likes' => '654'],
    ['from' => '#b8860b', 'to' => '#ffd700', 'label' => '#GoldenAle',   'likes' => '1.3K'],
    ['from' => '#800000', 'to' => '#c0392b', 'label' => '#RedAle',      'likes' => '876'],
    ['from' => '#1a0850', 'to' => '#4a0e8f', 'label' => '#BrewMagic',   'likes' => '534'],
    ['from' => '#0d6e6e', 'to' => '#00b4b4', 'label' => '#TealPint',    'likes' => '421'],
    ['from' => '#3d1c02', 'to' => '#8b4513', 'label' => '#Stout',       'likes' => '1.8K'],
    ['from' => '#2c1654', 'to' => '#a01e63', 'label' => '#PintVibes',   'likes' => '699'],
];

require_once __DIR__ . '/components/stub_data.php';

$tabs = ['For You', 'Trending', 'News', 'Sports', 'Entertainment'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>PintSocial / Explore</title>
  <meta name="description" content="Explore trending topics, people, and media on PintSocial."/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="dashboard.css"/>
  <link rel="stylesheet" href="explore.css"/>
</head>
<body>
<div class="layout">

  <!-- ══════════════════════════════
       LEFT SIDEBAR
  ══════════════════════════════ -->
  <?php require __DIR__ . '/components/left_sidebar.php'; ?>

  <!-- ══════════════════════════════
       MAIN: EXPLORE
  ══════════════════════════════ -->
  <main class="explore-main">

    <!-- Sticky search header -->
    <div class="explore-header">
      <div class="explore-search-inner">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input class="explore-search-input" id="exploreSearch" type="search" placeholder="Search PintSocial"/>
      </div>
    </div>

    <!-- Category tabs -->
    <div class="explore-tabs" id="exploreTabs">
      <?php foreach ($tabs as $i => $tab): ?>
        <button class="explore-tab <?= $i === 0 ? 'active' : '' ?>" data-tab="<?= strtolower(str_replace(' ', '-', $tab)) ?>">
          <?= $tab ?>
        </button>
      <?php endforeach; ?>
    </div>

    <!-- ── FOR YOU tab content ── -->
    <div id="tab-for-you">

      <!-- Hero trending banner -->
      <div class="trending-hero">
        <div class="trending-hero-bg"></div>
        <div class="trending-hero-content">
          <div class="trending-hero-label">🔥 <?= $heroTrend['category'] ?></div>
          <div class="trending-hero-tag"><?= htmlspecialchars($heroTrend['tag']) ?></div>
          <div class="trending-hero-count"><?= $heroTrend['count'] ?></div>
        </div>
      </div>

      <!-- What's Happening / Trending -->
      <div class="explore-section-title">What's happening</div>
      <div class="trending-list">
        <?php foreach ($trending as $t): ?>
        <div class="trending-row">
          <div class="trending-row-left">
            <div class="trending-row-num"><?= $t['num'] ?> · <?= htmlspecialchars($t['category']) ?></div>
            <div class="trending-row-tag"><?= htmlspecialchars($t['tag']) ?></div>
            <div class="trending-row-meta"><?= htmlspecialchars($t['count']) ?></div>
          </div>
          <button class="trending-row-more" title="More">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
          </button>
        </div>
        <?php endforeach; ?>
      </div>
      <a class="show-more-link" href="#">Show more</a>

      <!-- Who to follow -->
      <div class="explore-section-title">Who to follow</div>
      <div class="people-grid">
        <?php foreach ($people as $p): ?>
        <div class="people-row">
          <div class="avatar"><?= mb_strtoupper(mb_substr($p['user'], 0, 1)) ?></div>
          <div class="people-info">
            <div class="people-name"><?= htmlspecialchars($p['user']) ?></div>
            <div class="people-handle"><?= htmlspecialchars($p['handle']) ?></div>
            <div class="people-bio"><?= htmlspecialchars($p['bio']) ?></div>
          </div>
          <button class="follow-btn">Follow</button>
        </div>
        <?php endforeach; ?>
      </div>
      <a class="show-more-link" href="#">Show more</a>

      <!-- Media / Photo grid -->
      <div class="explore-section-title">Popular media</div>
      <div class="media-grid">
        <?php foreach ($mediaGradients as $m): ?>
        <div class="media-cell">
          <div class="media-cell-bg"
               style="background: linear-gradient(135deg, <?= $m['from'] ?>, <?= $m['to'] ?>)">
            <?= htmlspecialchars($m['label']) ?>
          </div>
          <div class="media-overlay">
            <span class="media-overlay-count">❤ <?= $m['likes'] ?></span>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

    </div><!-- /#tab-for-you -->

    <!-- ── OTHER TABS (empty state) ── -->
    <div id="tab-empty" style="display:none; text-align:center; padding:56px 24px; color:var(--gray);">
      <svg style="width:48px;height:48px;stroke:var(--gray);fill:none;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round;margin-bottom:14px" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <p style="font-size:22px;font-weight:800;color:var(--black);margin-bottom:8px">Explore this tab</p>
      <p style="font-size:15px; max-width:280px; margin:0 auto">Content for this category will appear here.</p>
    </div>

  </main><!-- /.explore-main -->

  <!-- ══════════════════════════════
       RIGHT SIDEBAR
  ══════════════════════════════ -->
  <?php require __DIR__ . '/components/right_sidebar.php'; ?>

</div><!-- /.layout -->

<script>
// ── Category tab switching ─────────────────────────────────────────────────
const tabs     = document.querySelectorAll('.explore-tab');
const forYou   = document.getElementById('tab-for-you');
const tabEmpty = document.getElementById('tab-empty');

tabs.forEach(tab => {
  tab.addEventListener('click', () => {
    tabs.forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
    if (tab.dataset.tab === 'for-you') {
      forYou.style.display   = '';
      tabEmpty.style.display = 'none';
    } else {
      forYou.style.display   = 'none';
      tabEmpty.style.display = '';
    }
  });
});

// ── Search → highlight matching trending rows ──────────────────────────────
document.getElementById('exploreSearch')?.addEventListener('input', function () {
  const q = this.value.toLowerCase().replace('#', '');
  document.querySelectorAll('.trending-row').forEach(row => {
    const tag = row.querySelector('.trending-row-tag')?.textContent.toLowerCase() || '';
    row.style.background = (q && tag.includes(q)) ? 'var(--brand-bg)' : '';
  });
});

// ── Follow button toggle ───────────────────────────────────────────────────
document.querySelectorAll('.follow-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const following = btn.dataset.following === '1';
    btn.dataset.following = following ? '0' : '1';
    btn.textContent = following ? 'Follow' : 'Following';
    btn.style.background    = following ? '' : 'transparent';
    btn.style.color         = following ? '' : 'var(--black)';
    btn.style.border        = following ? '' : '1.5px solid var(--border)';
  });
});
</script>
</body>
</html>
