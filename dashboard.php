<?php
/**
 * PintSocial – Dashboard (Twitter 2020 Layout)
 */
require_once __DIR__ . '/config.php';
require_login();

$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
$initial  = mb_strtoupper(mb_substr($username, 0, 1));

// ── Stub feed data ─────────────────────────────────────────────────────────
$pints = [
    [
        'user'    => $username,
        'handle'  => '@' . strtolower($username),
        'time'    => 'just now',
        'body'    => 'Just joined PintSocial! 🍺 Cheers to everyone here. #PintSocial #FirstPint',
        'replies' => 0, 'repints' => 0, 'likes' => 0,
    ],
    [
        'user'    => 'PintSocial',
        'handle'  => '@pintsocial',
        'time'    => '2m',
        'body'    => 'Welcome to PintSocial — the social network that never runs dry. 🍻 Share your world, one pint at a time.',
        'replies' => 12, 'repints' => 47, 'likes' => 203,
    ],
    [
        'user'    => 'Brew Daily',
        'handle'  => '@brewdaily',
        'time'    => '18m',
        'body'    => 'Hot take: a cold pint fixes most problems. Discuss. 👇',
        'replies' => 88, 'repints' => 124, 'likes' => 940,
    ],
    [
        'user'    => 'Tap Room News',
        'handle'  => '@taproom',
        'time'    => '1h',
        'body'    => 'New craft ales dropping this weekend at the Tap Room. Who\'s coming? 🎉 #CraftBeer #Weekend',
        'replies' => 34, 'repints' => 61, 'likes' => 412,
    ],
];

$trends = [
    ['tag' => '#PintSocial',  'pints' => '12.4K'],
    ['tag' => '#CraftBeer',   'pints' => '8.9K'],
    ['tag' => '#TGIF',        'pints' => '54.1K'],
    ['tag' => '#BrewCulture', 'pints' => '3.2K'],
    ['tag' => '#FridayVibes', 'pints' => '101K'],
];

$suggestions = [
    ['user' => 'Brew Daily',    'handle' => '@brewdaily'],
    ['user' => 'Tap Room News', 'handle' => '@taproom'],
    ['user' => 'Hops & Barley', 'handle' => '@hopsbarley'],
];
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
  <style>
    /* ── Reset ── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --brand:      #f2561d;
      --brand-dark: #d94312;
      --brand-bg:   rgba(242,86,29,0.08);
      --black:      #0f1419;
      --gray:       #536471;
      --border:     #eff3f4;
      --bg:         #ffffff;
      --sidebar-bg: #f7f9f9;
      --hover-bg:   #f7f9f9;
    }

    html, body {
      height: 100%;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      background: var(--bg);
      color: var(--black);
      font-size: 15px;
    }

    a { text-decoration: none; color: inherit; }
    button { font-family: inherit; cursor: pointer; }

    /* ════════════════════════════════════════
       LAYOUT  – 3 columns
    ════════════════════════════════════════ */
    .layout {
      display: flex;
      max-width: 1280px;
      margin: 0 auto;
      min-height: 100vh;
    }

    /* ── LEFT SIDEBAR ── */
    .left-sidebar {
      width: 275px;
      flex-shrink: 0;
      padding: 0 12px;
      position: sticky;
      top: 0;
      height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      overflow-y: auto;
    }

    /* Logo */
    .sidebar-logo {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 14px;
      margin-top: 4px;
      margin-bottom: 4px;
      border-radius: 9999px;
      transition: background 0.18s;
      font-size: 20px;
      font-weight: 900;
      color: var(--brand);
      cursor: pointer;
    }
    .sidebar-logo:hover { background: var(--brand-bg); }
    .sidebar-logo svg { width: 32px; flex-shrink: 0; }

    /* Nav items */
    .nav { display: flex; flex-direction: column; gap: 2px; width: 100%; margin-bottom: 16px; }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 20px;
      padding: 12px 14px;
      border-radius: 9999px;
      font-size: 20px;
      font-weight: 500;
      color: var(--black);
      transition: background 0.15s;
      cursor: pointer;
      border: none;
      background: none;
      width: 100%;
      text-align: left;
      white-space: nowrap;
    }
    .nav-item:hover { background: var(--hover-bg); }
    .nav-item.active { font-weight: 700; }
    .nav-item.active .nav-icon { color: var(--brand); }

    .nav-icon {
      width: 26px;
      height: 26px;
      flex-shrink: 0;
      stroke: currentColor;
      fill: none;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
    }

    /* Pint button */
    .pint-btn {
      background: var(--brand);
      color: #fff;
      border: none;
      border-radius: 9999px;
      padding: 16px;
      font-size: 17px;
      font-weight: 700;
      width: 90%;
      max-width: 220px;
      margin: 4px 0 16px;
      transition: background 0.18s, transform 0.1s;
    }
    .pint-btn:hover { background: var(--brand-dark); }
    .pint-btn:active { transform: scale(0.97); }

    /* Profile row at bottom */
    .sidebar-profile {
      margin-top: auto;
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 14px;
      border-radius: 9999px;
      cursor: pointer;
      transition: background 0.15s;
      width: 100%;
      margin-bottom: 12px;
    }
    .sidebar-profile:hover { background: var(--hover-bg); }
    .sidebar-profile .avatar { flex-shrink: 0; }
    .profile-names { flex: 1; min-width: 0; }
    .profile-display { font-weight: 700; font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .profile-handle  { font-size: 13px; color: var(--gray); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .profile-dots { color: var(--gray); font-size: 18px; }

    /* ── MAIN FEED ── */
    .main-feed {
      flex: 1;
      min-width: 0;
      border-left:  1px solid var(--border);
      border-right: 1px solid var(--border);
      max-width: 600px;
    }

    /* Feed header */
    .feed-header {
      position: sticky;
      top: 0;
      background: rgba(255,255,255,0.85);
      backdrop-filter: blur(8px);
      border-bottom: 1px solid var(--border);
      z-index: 10;
    }

    .feed-header-inner {
      display: flex;
      align-items: center;
      padding: 14px 16px;
    }
    .feed-header h2 { font-size: 20px; font-weight: 800; }

    /* Feed tabs */
    .feed-tabs {
      display: flex;
      border-bottom: 1px solid var(--border);
    }
    .feed-tab {
      flex: 1;
      text-align: center;
      padding: 16px;
      font-size: 15px;
      font-weight: 500;
      color: var(--gray);
      cursor: pointer;
      border: none;
      background: none;
      position: relative;
      transition: background 0.15s;
    }
    .feed-tab:hover { background: var(--hover-bg); }
    .feed-tab.active {
      font-weight: 700;
      color: var(--black);
    }
    .feed-tab.active::after {
      content: '';
      position: absolute;
      bottom: 0; left: 25%; right: 25%;
      height: 4px;
      background: var(--brand);
      border-radius: 2px 2px 0 0;
    }

    /* Compose box */
    .compose-box {
      display: flex;
      gap: 12px;
      padding: 12px 16px;
      border-bottom: 1px solid var(--border);
    }
    .compose-body { flex: 1; }
    .compose-input {
      width: 100%;
      border: none;
      outline: none;
      font-size: 20px;
      font-family: inherit;
      color: var(--black);
      placeholder-color: #536471;
      resize: none;
      line-height: 1.5;
      min-height: 56px;
      background: transparent;
    }
    .compose-input::placeholder { color: #536471; }
    .compose-divider { height: 1px; background: var(--border); margin: 8px 0; }
    .compose-actions {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .compose-icons { display: flex; gap: 4px; }
    .compose-icon-btn {
      background: none;
      border: none;
      color: var(--brand);
      padding: 8px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      transition: background 0.15s;
    }
    .compose-icon-btn:hover { background: var(--brand-bg); }
    .compose-icon-btn svg { width: 20px; height: 20px; stroke: var(--brand); fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    .compose-submit {
      background: var(--brand);
      color: #fff;
      border: none;
      border-radius: 9999px;
      padding: 8px 20px;
      font-size: 15px;
      font-weight: 700;
      opacity: 0.6;
      transition: background 0.18s, opacity 0.18s;
    }
    .compose-submit.ready { opacity: 1; }
    .compose-submit.ready:hover { background: var(--brand-dark); }

    /* ── PINT CARD ── */
    .pint-card {
      display: flex;
      gap: 12px;
      padding: 12px 16px;
      border-bottom: 1px solid var(--border);
      cursor: pointer;
      transition: background 0.15s;
    }
    .pint-card:hover { background: var(--hover-bg); }

    .pint-meta {
      display: flex;
      align-items: center;
      gap: 6px;
      margin-bottom: 2px;
      flex-wrap: wrap;
    }
    .pint-name   { font-weight: 700; font-size: 15px; }
    .pint-handle { color: var(--gray); font-size: 15px; }
    .pint-time   { color: var(--gray); font-size: 15px; }
    .pint-dot    { color: var(--gray); }

    .pint-body { font-size: 15px; line-height: 1.5; margin-bottom: 12px; }
    .pint-body .hashtag { color: var(--brand); }

    .pint-actions {
      display: flex;
      justify-content: space-between;
      max-width: 380px;
    }

    .pint-action {
      display: flex;
      align-items: center;
      gap: 6px;
      color: var(--gray);
      font-size: 13px;
      background: none;
      border: none;
      padding: 4px 8px;
      border-radius: 9999px;
      transition: color 0.15s, background 0.15s;
    }
    .pint-action svg { width: 18px; height: 18px; stroke: currentColor; fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .pint-action:hover.reply  { color: var(--brand); background: var(--brand-bg); }
    .pint-action:hover.reply svg { stroke: var(--brand); }
    .pint-action:hover.repint { color: #00ba7c; background: rgba(0,186,124,0.1); }
    .pint-action:hover.repint svg { stroke: #00ba7c; }
    .pint-action:hover.like   { color: #f91880; background: rgba(249,24,128,0.1); }
    .pint-action:hover.like svg { stroke: #f91880; }
    .pint-action:hover.share  { color: var(--brand); background: var(--brand-bg); }
    .pint-action:hover.share svg { stroke: var(--brand); }

    /* ── RIGHT SIDEBAR ── */
    .right-sidebar {
      width: 350px;
      flex-shrink: 0;
      padding: 0 16px;
      position: sticky;
      top: 0;
      height: 100vh;
      overflow-y: auto;
    }

    /* Search */
    .search-bar {
      position: sticky;
      top: 0;
      background: var(--bg);
      padding: 12px 0;
      z-index: 5;
    }
    .search-inner {
      display: flex;
      align-items: center;
      gap: 12px;
      background: var(--sidebar-bg);
      border: 1px solid transparent;
      border-radius: 9999px;
      padding: 10px 16px;
      transition: border-color 0.2s;
    }
    .search-inner:focus-within {
      background: #fff;
      border-color: var(--brand);
    }
    .search-inner svg { width: 18px; height: 18px; stroke: var(--gray); fill: none; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; flex-shrink: 0; }
    .search-input {
      border: none; outline: none;
      background: transparent;
      font-size: 15px; font-family: inherit;
      color: var(--black); width: 100%;
    }
    .search-input::placeholder { color: var(--gray); }

    /* Widget box */
    .widget {
      background: var(--sidebar-bg);
      border-radius: 16px;
      padding: 12px 16px;
      margin-bottom: 16px;
    }
    .widget-title {
      font-size: 20px;
      font-weight: 800;
      margin-bottom: 12px;
    }

    /* Trend item */
    .trend-item {
      padding: 12px 0;
      border-bottom: 1px solid var(--border);
      cursor: pointer;
      transition: background 0.15s;
      border-radius: 8px;
      padding: 10px 8px;
    }
    .trend-item:hover { background: rgba(0,0,0,0.03); }
    .trend-item:last-child { border-bottom: none; }
    .trend-category { font-size: 13px; color: var(--gray); }
    .trend-tag      { font-size: 15px; font-weight: 700; margin: 2px 0; }
    .trend-count    { font-size: 13px; color: var(--gray); }

    .widget-link {
      display: block;
      color: var(--brand);
      font-size: 15px;
      margin-top: 12px;
      cursor: pointer;
    }
    .widget-link:hover { text-decoration: underline; }

    /* Who to follow */
    .follow-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px 8px;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.15s;
    }
    .follow-item:hover { background: rgba(0,0,0,0.03); }
    .follow-info { flex: 1; min-width: 0; }
    .follow-name   { font-weight: 700; font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .follow-handle { font-size: 13px; color: var(--gray); }
    .follow-btn {
      background: var(--black);
      color: #fff;
      border: none;
      border-radius: 9999px;
      padding: 6px 16px;
      font-size: 14px;
      font-weight: 700;
      flex-shrink: 0;
      transition: background 0.15s;
    }
    .follow-btn:hover { background: #333; }

    /* Footer links */
    .sidebar-footer {
      font-size: 13px;
      color: var(--gray);
      line-height: 1.8;
      margin-top: 8px;
      padding: 0 8px;
    }
    .sidebar-footer a { color: var(--gray); margin-right: 8px; }
    .sidebar-footer a:hover { text-decoration: underline; }

    /* ── AVATAR ── */
    .avatar {
      width: 40px; height: 40px;
      border-radius: 50%;
      background: linear-gradient(135deg, var(--brand), var(--brand-dark));
      display: flex; align-items: center; justify-content: center;
      font-size: 17px; font-weight: 800; color: #fff;
      flex-shrink: 0;
    }
    .avatar.sm { width: 34px; height: 34px; font-size: 14px; }
    .avatar.lg { width: 48px; height: 48px; font-size: 20px; }

    /* ── RESPONSIVE ── */
    @media (max-width: 1100px) {
      .right-sidebar { display: none; }
    }
    @media (max-width: 700px) {
      .left-sidebar { width: 72px; }
      .nav-item span, .sidebar-logo span, .pint-btn, .profile-names, .profile-dots { display: none; }
      .nav-item { justify-content: center; padding: 12px; }
      .sidebar-logo { justify-content: center; }
      .sidebar-profile { justify-content: center; padding: 12px; }
      .pint-btn-wrap { width: 100%; display: flex; justify-content: center; }
    }
  </style>
</head>
<body>
<div class="layout">

  <!-- ══════════════════════════════
       LEFT SIDEBAR
  ══════════════════════════════ -->
  <aside class="left-sidebar">

    <!-- Logo -->
    <a href="dashboard.php" class="sidebar-logo">
      <svg viewBox="0 0 100 120" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M18 10 L12 110 Q12 116 20 116 L80 116 Q88 116 88 110 L82 10 Z"
              fill="#fde8dc" stroke="#f2561d" stroke-width="5" stroke-linejoin="round"/>
        <ellipse cx="50" cy="10" rx="32" ry="10" fill="#f2561d" opacity="0.9"/>
        <path d="M22 32 L18.5 108 Q18.5 112 24 112 L76 112 Q81.5 112 81.5 108 L78 32 Z" fill="rgba(242,86,29,0.25)"/>
        <path d="M82 35 Q100 35 100 55 Q100 75 82 75" stroke="#f2561d" stroke-width="5" fill="none" stroke-linecap="round"/>
      </svg>
      <span>PintSocial</span>
    </a>

    <!-- Nav -->
    <nav class="nav">
      <?php
      $navItems = [
        ['icon' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',               'label' => 'Home',         'active' => true],
        ['icon' => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',                                        'label' => 'Explore',      'active' => false],
        ['icon' => '<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>',                     'label' => 'Notifications','active' => false],
        ['icon' => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>', 'label' => 'Messages',     'active' => false],
        ['icon' => '<path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>',                                                     'label' => 'Bookmarks',    'active' => false],
        ['icon' => '<line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>','label' => 'Lists','active'=>false],
        ['icon' => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',                               'label' => 'Profile',      'active' => false],
        ['icon' => '<circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/>',                       'label' => 'More',         'active' => false],
      ];
      foreach ($navItems as $item): ?>
        <button class="nav-item <?= $item['active'] ? 'active' : '' ?>">
          <svg class="nav-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <?= $item['icon'] ?>
          </svg>
          <span><?= $item['label'] ?></span>
        </button>
      <?php endforeach; ?>
    </nav>

    <!-- Pint button -->
    <button class="pint-btn" id="pintBtn">🍺 Pint</button>

    <!-- Profile row -->
    <a href="logout.php" class="sidebar-profile" title="Log out">
      <div class="avatar sm"><?= $initial ?></div>
      <div class="profile-names">
        <div class="profile-display"><?= $username ?></div>
        <div class="profile-handle">@<?= strtolower($username) ?></div>
      </div>
      <div class="profile-dots">···</div>
    </a>

  </aside>

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
        <button class="feed-tab active">For you</button>
        <button class="feed-tab">Following</button>
      </div>
    </div>

    <!-- Compose box -->
    <div class="compose-box">
      <div class="avatar lg"><?= $initial ?></div>
      <div class="compose-body">
        <textarea class="compose-input" id="composeInput"
                  placeholder="What's happening?" rows="2"></textarea>
        <div class="compose-divider"></div>
        <div class="compose-actions">
          <div class="compose-icons">
            <!-- Image -->
            <button class="compose-icon-btn" title="Photo">
              <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            </button>
            <!-- GIF -->
            <button class="compose-icon-btn" title="GIF">
              <svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="15" rx="2" ry="2"/><polyline points="17 2 12 7 7 2"/></svg>
            </button>
            <!-- Emoji -->
            <button class="compose-icon-btn" title="Emoji">
              <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
            </button>
            <!-- Location -->
            <button class="compose-icon-btn" title="Location">
              <svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </button>
          </div>
          <button class="compose-submit" id="composeBtn">Pint</button>
        </div>
      </div>
    </div>

    <!-- Feed -->
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
          // Highlight hashtags
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

  </main>

  <!-- ══════════════════════════════
       RIGHT SIDEBAR
  ══════════════════════════════ -->
  <aside class="right-sidebar">

    <!-- Search -->
    <div class="search-bar">
      <div class="search-inner">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input class="search-input" type="search" placeholder="Search PintSocial"/>
      </div>
    </div>

    <!-- Trending -->
    <div class="widget">
      <div class="widget-title">Trends for you</div>
      <?php foreach ($trends as $t): ?>
      <div class="trend-item">
        <div class="trend-category">Trending · PintSocial</div>
        <div class="trend-tag"><?= htmlspecialchars($t['tag']) ?></div>
        <div class="trend-count"><?= $t['pints'] ?> Pints</div>
      </div>
      <?php endforeach; ?>
      <a class="widget-link" href="#">Show more</a>
    </div>

    <!-- Who to follow -->
    <div class="widget">
      <div class="widget-title">Who to follow</div>
      <?php foreach ($suggestions as $s): ?>
      <div class="follow-item">
        <div class="avatar sm"><?= mb_strtoupper(mb_substr($s['user'], 0, 1)) ?></div>
        <div class="follow-info">
          <div class="follow-name"><?= htmlspecialchars($s['user']) ?></div>
          <div class="follow-handle"><?= htmlspecialchars($s['handle']) ?></div>
        </div>
        <button class="follow-btn">Follow</button>
      </div>
      <?php endforeach; ?>
      <a class="widget-link" href="#">Show more</a>
    </div>

    <!-- Footer links -->
    <div class="sidebar-footer">
      <a href="#">Terms of Service</a>
      <a href="#">Privacy Policy</a>
      <a href="#">Cookie Policy</a>
      <a href="#">Ads info</a>
      <a href="#">More ···</a>
      <br/>© 2026 PintSocial, Inc.
    </div>

  </aside>

</div><!-- /.layout -->

<script>
// Compose submit enable/disable
const txt = document.getElementById('composeInput');
const btn = document.getElementById('composeBtn');
txt?.addEventListener('input', () => {
  btn.classList.toggle('ready', txt.value.trim().length > 0);
});

// Feed tab toggle
document.querySelectorAll('.feed-tab').forEach(tab => {
  tab.addEventListener('click', () => {
    document.querySelectorAll('.feed-tab').forEach(t => t.classList.remove('active'));
    tab.classList.add('active');
  });
});

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
