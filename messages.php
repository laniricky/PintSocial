<?php
/**
 * PintSocial – Messages Page (Twitter 2020 Layout)
 */
require_once __DIR__ . '/config.php';
require_login();

$username = htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');
$initial  = mb_strtoupper(mb_substr($username, 0, 1));

// ── Stub conversation data ──────────────────────────────────────────────────
$conversations = [
    [
        'id'      => 1,
        'user'    => 'Brew Daily',
        'handle'  => '@brewdaily',
        'preview' => 'Totally agree, the hops make all the difference 🍺',
        'time'    => '2m',
        'unread'  => true,
        'active'  => true,
    ],
    [
        'id'      => 2,
        'user'    => 'Tap Room News',
        'handle'  => '@taproom',
        'preview' => 'See you at the event this Saturday!',
        'time'    => '1h',
        'unread'  => false,
        'active'  => false,
    ],
    [
        'id'      => 3,
        'user'    => 'Hops & Barley',
        'handle'  => '@hopsbarley',
        'preview' => 'Which IPA do you recommend for beginners?',
        'time'    => '3h',
        'unread'  => true,
        'active'  => false,
    ],
    [
        'id'      => 4,
        'user'    => 'PintSocial',
        'handle'  => '@pintsocial',
        'preview' => 'Welcome to PintSocial! 🍻 Enjoy every pint.',
        'time'    => '2d',
        'unread'  => false,
        'active'  => false,
    ],
    [
        'id'      => 5,
        'user'    => 'Craft Corner',
        'handle'  => '@craftcorner',
        'preview' => 'Let me know when you\'re free for a collab!',
        'time'    => '5d',
        'unread'  => false,
        'active'  => false,
    ],
];

// Active chat messages (for conversation 1 – Brew Daily)
$messages = [
    ['mine' => false, 'text' => 'Hey! Loved your post about IPAs 🍺',           'time' => '10:02 AM', 'date' => 'Today'],
    ['mine' => true,  'text' => 'Thanks! IPAs are my absolute favourite.',        'time' => '10:04 AM', 'date' => 'Today'],
    ['mine' => false, 'text' => 'Same here. Have you tried the local brewing kit?','time' => '10:06 AM', 'date' => 'Today'],
    ['mine' => true,  'text' => 'Not yet but it\'s on my list! 😄 What hops would you suggest?', 'time' => '10:08 AM', 'date' => 'Today'],
    ['mine' => false, 'text' => 'Totally agree, the hops make all the difference 🍺', 'time' => '10:10 AM', 'date' => 'Today'],
];

$activeConv = $conversations[0];

require_once __DIR__ . '/components/stub_data.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>PintSocial / Messages</title>
  <meta name="description" content="Your PintSocial direct messages and conversations."/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="dashboard.css"/>
  <link rel="stylesheet" href="messages.css"/>
</head>
<body>
<div class="layout">

  <?php require __DIR__ . '/components/left_sidebar.php'; ?>

  <!-- ══════════════════════════════
       MAIN: MESSAGES
  ══════════════════════════════ -->
  <main class="messages-main">

    <!-- Header -->
    <div class="messages-header">
      <h2>Messages</h2>
      <div class="messages-header-actions">
        <!-- Settings -->
        <button class="icon-btn" title="Settings">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
        </button>
        <!-- New message -->
        <button class="icon-btn" title="New message" id="newMsgIconBtn">
          <svg viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        </button>
      </div>
    </div>

    <!-- Search conversations -->
    <div class="conv-search">
      <div class="conv-search-inner">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input class="conv-search-input" type="search" placeholder="Search Direct Messages" id="convSearch"/>
      </div>
    </div>

    <!-- Two-pane body -->
    <div class="messages-body">

      <!-- Conversation list -->
      <div class="conv-list" id="convList">
        <?php foreach ($conversations as $conv): ?>
        <div class="conv-item <?= $conv['active'] ? 'active' : '' ?>"
             data-conv="<?= $conv['id'] ?>"
             data-name="<?= htmlspecialchars($conv['user']) ?>"
             data-handle="<?= htmlspecialchars($conv['handle']) ?>">
          <div class="avatar sm"><?= mb_strtoupper(mb_substr($conv['user'], 0, 1)) ?></div>
          <div class="conv-item-body">
            <div class="conv-name"><?= htmlspecialchars($conv['user']) ?></div>
            <div class="conv-preview"><?= htmlspecialchars($conv['preview']) ?></div>
          </div>
          <span class="conv-time"><?= htmlspecialchars($conv['time']) ?></span>
          <?php if ($conv['unread']): ?>
            <span class="conv-unread"></span>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Chat pane -->
      <div class="chat-pane" id="chatPane">

        <!-- Chat header -->
        <div class="chat-header">
          <div class="avatar sm"><?= mb_strtoupper(mb_substr($activeConv['user'], 0, 1)) ?></div>
          <div class="chat-header-info">
            <div class="chat-header-name" id="chatName"><?= htmlspecialchars($activeConv['user']) ?></div>
            <div class="chat-header-handle" id="chatHandle"><?= htmlspecialchars($activeConv['handle']) ?></div>
          </div>
          <!-- More options -->
          <button class="icon-btn" title="More options">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
          </button>
        </div>

        <!-- Messages area -->
        <div class="chat-messages" id="chatMessages">

          <div class="chat-date-sep">Today</div>

          <?php
          $prevDate = '';
          foreach ($messages as $msg):
            if ($msg['date'] !== $prevDate) { $prevDate = $msg['date']; }
          ?>
          <div class="chat-bubble-wrap <?= $msg['mine'] ? 'mine' : '' ?>">
            <?php if (!$msg['mine']): ?>
              <div class="avatar sm"><?= mb_strtoupper(mb_substr($activeConv['user'], 0, 1)) ?></div>
            <?php endif; ?>
            <div class="chat-bubble"><?= htmlspecialchars($msg['text']) ?></div>
            <span class="chat-time"><?= htmlspecialchars($msg['time']) ?></span>
          </div>
          <?php endforeach; ?>

        </div><!-- /#chatMessages -->

        <!-- Compose bar -->
        <div class="chat-compose">
          <div class="chat-input-wrap">
            <!-- Emoji -->
            <button class="icon-btn" title="Emoji" style="padding:4px">
              <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M8 14s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/></svg>
            </button>
            <textarea class="chat-input" id="chatInput" placeholder="Start a new message" rows="1"></textarea>
            <!-- Image -->
            <button class="icon-btn" title="Photo" style="padding:4px">
              <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            </button>
          </div>
          <button class="chat-send-btn" id="sendBtn" title="Send">
            <svg viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
          </button>
        </div>

      </div><!-- /#chatPane -->

    </div><!-- /.messages-body -->

  </main><!-- /.messages-main -->

  <?php require __DIR__ . '/components/right_sidebar.php'; ?>

</div><!-- /.layout -->

<script>
// ── Auto-resize textarea ──────────────────────────────────────────────────
const chatInput = document.getElementById('chatInput');
const sendBtn   = document.getElementById('sendBtn');

chatInput?.addEventListener('input', () => {
  chatInput.style.height = 'auto';
  chatInput.style.height = chatInput.scrollHeight + 'px';
  sendBtn.classList.toggle('ready', chatInput.value.trim().length > 0);
});

// ── Send message ──────────────────────────────────────────────────────────
function sendMessage() {
  const text = chatInput.value.trim();
  if (!text) return;

  const wrap = document.createElement('div');
  wrap.className = 'chat-bubble-wrap mine';

  const bubble = document.createElement('div');
  bubble.className = 'chat-bubble';
  bubble.textContent = text;

  const now = new Date();
  const timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  const time = document.createElement('span');
  time.className = 'chat-time';
  time.textContent = timeStr;

  wrap.appendChild(bubble);
  wrap.appendChild(time);

  const msgs = document.getElementById('chatMessages');
  msgs.appendChild(wrap);
  msgs.scrollTop = msgs.scrollHeight;

  chatInput.value = '';
  chatInput.style.height = 'auto';
  sendBtn.classList.remove('ready');
}

sendBtn?.addEventListener('click', sendMessage);
chatInput?.addEventListener('keydown', e => {
  if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage(); }
});

// ── Conversation switching ────────────────────────────────────────────────
document.querySelectorAll('.conv-item').forEach(item => {
  item.addEventListener('click', () => {
    document.querySelectorAll('.conv-item').forEach(i => i.classList.remove('active'));
    item.classList.add('active');
    // Remove unread dot
    item.querySelector('.conv-unread')?.remove();
    // Update chat header
    document.getElementById('chatName').textContent   = item.dataset.name;
    document.getElementById('chatHandle').textContent = item.dataset.handle;
  });
});

// ── Filter conversations by search ───────────────────────────────────────
document.getElementById('convSearch')?.addEventListener('input', function () {
  const q = this.value.toLowerCase();
  document.querySelectorAll('.conv-item').forEach(item => {
    const name = item.dataset.name?.toLowerCase() || '';
    item.style.display = name.includes(q) ? '' : 'none';
  });
});

// ── Scroll chat to bottom on load ────────────────────────────────────────
const msgs = document.getElementById('chatMessages');
if (msgs) msgs.scrollTop = msgs.scrollHeight;
</script>
</body>
</html>
