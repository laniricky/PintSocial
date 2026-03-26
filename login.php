<?php
/**
 * PintSocial – Login Handler
 * Accepts POST from index.php login form.
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

csrf_verify();

// ── Collect input ──────────────────────────────────────────────────────────
$identifier = trim($_POST['username']  ?? '');   // email or username
$password   =       $_POST['password'] ?? '';

if ($identifier === '' || $password === '') {
    redirect('index.php', 'error', 'Please fill in all fields.');
}

// ── Look up user by email OR username ──────────────────────────────────────
try {
    $db   = get_db();
    $stmt = $db->prepare(
        'SELECT id, username, password_hash
           FROM users
          WHERE email = :email OR username = :uname
          LIMIT 1'
    );
    $stmt->execute([':email' => $identifier, ':uname' => $identifier]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        redirect('index.php', 'error',
            'Incorrect credentials. Please try again.');
    }

    // ── Rehash if bcrypt cost has changed ──────────────────────────────────
    if (password_needs_rehash($user['password_hash'], PASSWORD_BCRYPT)) {
        $new_hash = password_hash($password, PASSWORD_BCRYPT);
        $db->prepare('UPDATE users SET password_hash = ? WHERE id = ?')
           ->execute([$new_hash, $user['id']]);
    }

    // ── Start authenticated session ────────────────────────────────────────
    session_regenerate_id(true);
    $_SESSION['user_id']  = $user['id'];
    $_SESSION['username'] = $user['username'];

    header('Location: dashboard.php');
    exit;

} catch (PDOException $ex) {
    error_log('[PintSocial login] ' . $ex->getMessage());
    redirect('index.php', 'error',
        'A database error occurred. Please try again later.');
}
