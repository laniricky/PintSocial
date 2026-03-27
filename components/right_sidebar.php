  <!-- ══════════════════════════════
       RIGHT SIDEBAR (Included)
  ══════════════════════════════ -->
  <aside class="right-sidebar">

    <!-- Search -->
    <?php if (basename($_SERVER['PHP_SELF']) !== 'explore.php'): ?>
    <div class="search-bar">
      <div class="search-inner">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input class="search-input" type="search" placeholder="Search PintSocial"/>
      </div>
    </div>
    <?php else: ?>
    <!-- Search (decorative duplicate for sidebar on explore) -->
    <div class="search-bar">
      <div class="search-inner" style="pointer-events:none;opacity:0.5">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input class="search-input" type="text" placeholder="Search" tabindex="-1"/>
      </div>
    </div>
    <?php endif; ?>

    <!-- Who to follow -->
    <?php if (isset($suggestions) && !empty($suggestions)): ?>
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
    <?php endif; ?>

    <!-- Trends -->
    <?php if (isset($trends) && !empty($trends)): ?>
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
    <?php endif; ?>

    <!-- Footer -->
    <div class="sidebar-footer">
      <a href="#">Terms of Service</a>
      <a href="#">Privacy Policy</a>
      <a href="#">Cookie Policy</a>
      <a href="#">More ···</a>
      <br/>© 2026 PintSocial, Inc.
    </div>

  </aside>
