<?php
// Determine the current file name (e.g. "dashboard.php")
$current_page = basename($_SERVER['PHP_SELF']);

// ── Shared Stub Data ────────────────────────────────────────────────────────
$suggestions = [
    ['user' => 'Brew Daily',    'handle' => '@brewdaily'],
    ['user' => 'Tap Room News', 'handle' => '@taproom'],
    ['user' => 'Hops & Barley', 'handle' => '@hopsbarley'],
];

$trends = [
    ['tag' => '#PintSocial',  'pints' => '12.4K'],
    ['tag' => '#CraftBeer',   'pints' => '8.9K'],
    ['tag' => '#TGIF',        'pints' => '54.1K'],
];

$navItems = [
    ['icon' => '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',               'label' => 'Home',          'href' => 'dashboard.php',     'active' => ($current_page === 'dashboard.php')],
    ['icon' => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>',                                        'label' => 'Explore',       'href' => 'explore.php',       'active' => ($current_page === 'explore.php')],
    ['icon' => '<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>',                     'label' => 'Notifications', 'href' => 'notifications.php', 'active' => ($current_page === 'notifications.php')],
    ['icon' => '<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>', 'label' => 'Messages', 'href' => 'messages.php',      'active' => ($current_page === 'messages.php')],
    ['icon' => '<path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/>',                                                     'label' => 'Saved',         'href' => 'saved.php',         'active' => ($current_page === 'saved.php')],
    ['icon' => '<polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/>',                   'label' => 'Reels',         'href' => 'reels.php',         'active' => ($current_page === 'reels.php')],
    ['icon' => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',                               'label' => 'Profile',       'href' => 'profile.php',       'active' => ($current_page === 'profile.php')],
    ['icon' => '<circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/>',                       'label' => 'More',          'href' => '#',                 'active' => false],
];
