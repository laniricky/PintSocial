<?php
/**
 * AJAX Endpoint for Save/Unsave PINTS
 */
require_once __DIR__ . '/config.php';
header('Content-Type: application/json');

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}
csrf_verify();

$my_id = $_SESSION['user_id'];
$pint_id = $_POST['pint_id'] ?? 0;
$action = $_POST['action'] ?? '';

if (!$pint_id || !in_array($action, ['save', 'unsave'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid parameters']);
    exit;
}

$db = get_db();
try {
    if ($action === 'save') {
        $stmt = $db->prepare('INSERT IGNORE INTO saved_pints (user_id, pint_id) VALUES (?, ?)');
        $stmt->execute([$my_id, $pint_id]);
    } else {
        $stmt = $db->prepare('DELETE FROM saved_pints WHERE user_id = ? AND pint_id = ?');
        $stmt->execute([$my_id, $pint_id]);
    }
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
