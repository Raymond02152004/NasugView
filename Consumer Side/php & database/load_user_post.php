<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

if (!isset($_GET['username'])) {
    echo json_encode(['success' => false, 'message' => 'Missing username']);
    exit;
}

$username = $_GET['username'];

// First, get the user ID based on username
$userStmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
if (!$userStmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed (users): ' . $conn->error]);
    exit;
}
$userStmt->bind_param("s", $username);
$userStmt->execute();
$userResult = $userStmt->get_result();

if ($userResult->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

$user = $userResult->fetch_assoc();
$user_id = $user['id'];

// Now fetch the posts
$postStmt = $conn->prepare("SELECT image, caption, created_at FROM posts WHERE user_id = ? ORDER BY created_at DESC");
if (!$postStmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed (posts): ' . $conn->error]);
    exit;
}
$postStmt->bind_param("i", $user_id);
$postStmt->execute();
$postResult = $postStmt->get_result();

$posts = [];
while ($row = $postResult->fetch_assoc()) {
    $posts[] = [
        'image' => 'posts/' . $row['image'],
        'caption' => $row['caption'],
        'created_at' => $row['created_at']
    ];
}

echo json_encode(['success' => true, 'posts' => $posts]);
$conn->close();
