  <!-- ══════════════════════════════
       LEFT SIDEBAR (Included)
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
      <?php foreach ($navItems as $item): ?>
        <a href="<?= $item['href'] ?>" class="nav-item <?= $item['active'] ? 'active' : '' ?>">
          <svg class="nav-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <?= $item['icon'] ?>
          </svg>
          <span><?= $item['label'] ?></span>
        </a>
      <?php endforeach; ?>
    </nav>

    <!-- Pint button -->
    <a href="dashboard.php" class="pint-btn" style="text-decoration:none;text-align:center;display:block">🍺 Pint</a>

    <!-- Profile row -->
    <a href="logout.php" class="sidebar-profile" title="Log out">
      <div class="avatar sm"><?= isset($initial) ? $initial : '?' ?></div>
      <div class="profile-names">
        <div class="profile-display"><?= isset($username) ? $username : 'User' ?></div>
        <div class="profile-handle">@<?= isset($username) ? strtolower($username) : 'user' ?></div>
      </div>
      <div class="profile-dots">···</div>
    </a>

  </aside>
