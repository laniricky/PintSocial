<?php
/**
 * PintSocial – Explore Page (Twitter 2020 Layout)
 */
require_once __DIR__ . '/config.php';
require_login();

$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
$initial  = mb_strtoupper(mb_substr($username, 0, 1));

$db = get_db();
$my_id = $_SESSION['user_id'];
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

// ── Search Logic ──────────────────────────────────────────
$searchResults = [];
if ($q !== '') {
    $stmt = $db->prepare('
        SELECT p.*, u.username 
        FROM pints p 
        JOIN users u ON p.user_id = u.id 
        WHERE p.body LIKE ? 
        ORDER BY p.created_at DESC 
        LIMIT 50
    ');
    $stmt->execute(['%' . $q . '%']);
    $searchResults = $stmt->fetchAll();
} else {
    // ── Dynamic Suggestions (Who to follow) ───────────────────
    $stmt = $db->prepare('
        SELECT id, username, bio 
        FROM users 
        WHERE id != ? 
          AND id NOT IN (SELECT following_id FROM follows WHERE follower_id = ?)
        ORDER BY RAND() 
        LIMIT 4
    ');
    $stmt->execute([$my_id, $my_id]);
    $suggested_people = $stmt->fetchAll();
}

$heroTrend = ['tag' => '#FridayVibes', 'category' => 'Trending worldwide', 'count' => '101K Pints'];

$trending = [
    ['num' => 1,  'tag' => '#PintSocial',   'category' => 'Trending · PintSocial', 'count' => '12.4K Pints'],
    ['num' => 2,  'tag' => '#CraftBeer',    'category' => 'Food & Drink',          'count' => '8.9K Pints'],
    ['num' => 3,  'tag' => '#BrewCulture',  'category' => 'Trending · Ireland',    'count' => '3.2K Pints'],
    ['num' => 4,  'tag' => '#TGIF',         'category' => 'Trending worldwide',    'count' => '54.1K Pints'],
    ['num' => 5,  'tag' => '#HopsAndMalt',  'category' => 'Food & Drink',          'count' => '1.8K Pints'],
    ['num' => 6,  'tag' => '#TapRoom',      'category' => 'Trending · Dublin',     'count' => '6.7K Pints'],
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
        <form action="explore.php" method="GET" style="flex:1;">
          <input class="explore-search-input" name="q" id="exploreSearch" type="search" placeholder="Search PintSocial" value="<?= htmlspecialchars($q) ?>" required/>
        </form>
      </div>
    </div>

    <?php if ($q !== ''): ?>
      <!-- ── SEARCH RESULTS ── -->
      <div class="explore-section-title" style="padding-top:16px;">Search results for "<?= htmlspecialchars($q) ?>"</div>
      <div id="pintsFeed">
        <?php if (empty($searchResults)): ?>
          <div style="padding: 32px 16px; text-align: center; color: var(--gray);">No pints found matching your search.</div>
        <?php else: ?>
          <?php foreach ($searchResults as $pint): ?>
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
                <?= preg_replace('/(#\w+)/', '<span class="hashtag">$1</span>', nl2br(htmlspecialchars($pint['body']))) ?>
              </div>
              <div class="pint-actions">
                <button class="pint-action reply"><svg viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg><span><?= $pint['replies_count'] ?: '' ?></span></button>
                <button class="pint-action repint"><svg viewBox="0 0 24 24"><polyline points="17 1 21 5 17 9"/><path d="M3 11V9a4 4 0 0 1 4-4h14"/><polyline points="7 23 3 19 7 15"/><path d="M21 13v2a4 4 0 0 1-4 4H3"/></svg><span><?= $pint['repints_count'] ?: '' ?></span></button>
                <button class="pint-action like"><svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg><span><?= $pint['likes_count'] ?: '' ?></span></button>
                <button class="pint-action share"><svg viewBox="0 0 24 24"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg></button>
              </div>
            </div>
          </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    <?php else: ?>
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
      <div class="trending-hero" onclick="window.location.href='explore.php?q=<?= urlencode($heroTrend['tag']) ?>'" style="cursor:pointer;">
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
        <div class="trending-row" onclick="window.location.href='explore.php?q=<?= urlencode($t['tag']) ?>'" style="cursor:pointer;">
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
        <?php foreach ($suggested_people as $p): ?>
        <div class="people-row" onclick="window.location.href='profile.php?u=<?= urlencode($p['username']) ?>'" style="cursor:pointer;">
          <div class="avatar"><?= mb_strtoupper(mb_substr($p['username'], 0, 1)) ?></div>
          <div class="people-info">
            <div class="people-name"><?= htmlspecialchars($p['username']) ?></div>
            <div class="people-handle">@<?= strtolower(htmlspecialchars($p['username'])) ?></div>
            <div class="people-bio" style="font-size:13px; color:var(--gray);"><?= htmlspecialchars($p['bio'] ?? '') ?></div>
          </div>
          <button class="user-follow-btn" style="background:#fff; border:1px solid var(--border); border-radius:999px; padding:6px 16px; font-weight:700; cursor:pointer;" data-id="<?= $p['id'] ?>" data-following="0" onclick="toggleFollow(event, this)">Follow</button>
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
    
    <?php endif; ?> <!-- End search conditional -->

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

// ── Search matching (disabled when fully querying DB) ──
// Removed since we actually query the DB properly now.

// ── Follow button toggle ───────────────────────────────────────────────────
async function toggleFollow(e, btn) {
  e.stopPropagation(); // prevent clicking the card
  const isFollowing = btn.dataset.following === '1';
  const action = isFollowing ? 'unfollow' : 'follow';
  const targetId = btn.dataset.id;
  
  // Optimistic UI update
  btn.dataset.following = isFollowing ? '0' : '1';
  btn.textContent = isFollowing ? 'Follow' : 'Following';
  btn.style.color = '';
  btn.style.borderColor = 'var(--border)';
  btn.style.backgroundColor = isFollowing ? '#fff' : 'var(--black)';
  if(!isFollowing) btn.style.color = '#fff';
  
  try {
    const fd = new FormData();
    fd.append('target_id', targetId);
    fd.append('action', action);
    fd.append('csrf_token', '<?= csrf_token() ?>');
    
    const res = await fetch('ajax_follow.php', { method: 'POST', body: fd });
    if (!res.ok) throw new Error('Network error');
  } catch (err) {
    // Revert
    btn.dataset.following = isFollowing ? '1' : '0';
    btn.textContent = isFollowing ? 'Following' : 'Follow';
  }
}

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
      btn.style.color = 'var(--black)';
      btn.style.borderColor = 'var(--border)';
      btn.style.backgroundColor = '#fff';
    } else {
      btn.textContent = 'Follow';
      btn.style.color = '#fff';
      btn.style.backgroundColor = 'var(--black)';
    }
  });
  // init state colors
  if (btn.dataset.following === '0') {
      btn.style.color = '#fff';
      btn.style.backgroundColor = 'var(--black)';
  }
});
</script>
</body>
</html>
