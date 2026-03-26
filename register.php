<?php
/**
 * PintSocial – Register Handler
 * Accepts POST from index.php signup form.
 */
require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

csrf_verify();

// ── Collect & trim input ───────────────────────────────────────────────────
$username = trim($_POST['reg_username'] ?? '');
$email    = trim($_POST['reg_email']    ?? '');
$password =       $_POST['reg_password'] ?? '';
$confirm  =       $_POST['reg_confirm']  ?? '';

// ── Validation ─────────────────────────────────────────────────────────────
if (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username)) {
    redirect('index.php?tab=signup', 'error',
        'Username must be 3–30 characters (letters, numbers, underscores).');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect('index.php?tab=signup', 'error', 'Please enter a valid email address.');
}

if (strlen($password) < 8) {
    redirect('index.php?tab=signup', 'error', 'Password must be at least 8 characters.');
}

if ($password !== $confirm) {
    redirect('index.php?tab=signup', 'error', 'Passwords do not match.');
}

// ── Uniqueness check ───────────────────────────────────────────────────────
try {
    $db  = get_db();
    $stmt = $db->prepare(
        'SELECT id FROM users WHERE email = :email OR username = :username LIMIT 1'
    );
    $stmt->execute([':email' => $email, ':username' => $username]);

    if ($stmt->fetch()) {
        redirect('index.php?tab=signup', 'error',
            'That email or username is already taken.');
    }

    // ── Insert ─────────────────────────────────────────────────────────────
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $ins  = $db->prepare(
        'INSERT INTO users (username, email, password_hash) VALUES (:u, :e, :h)'
    );
    $ins->execute([':u' => $username, ':e' => $email, ':h' => $hash]);

    redirect('index.php', 'success', 'Account created! You can now log in.');

} catch (PDOException $ex) {
    error_log('[PintSocial register] ' . $ex->getMessage());
    redirect('index.php?tab=signup', 'error',
        'A database error occurred. Please try again later.');
}
