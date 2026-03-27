<?php
/**
 * AJAX Endpoint for Follow/Unfollow
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
$target_id = $_POST['target_id'] ?? 0;
$action = $_POST['action'] ?? '';

if (!$target_id || $target_id == $my_id || !in_array($action, ['follow', 'unfollow'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid parameters']);
    exit;
}

$db = get_db();

try {
    if ($action === 'follow') {
        $stmt = $db->prepare('INSERT IGNORE INTO follows (follower_id, following_id) VALUES (?, ?)');
        $stmt->execute([$my_id, $target_id]);
    } else {
        $stmt = $db->prepare('DELETE FROM follows WHERE follower_id = ? AND following_id = ?');
        $stmt->execute([$my_id, $target_id]);
    }
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}
