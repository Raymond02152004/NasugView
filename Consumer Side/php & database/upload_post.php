<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");
include "db.php";

if (!isset($_POST['username'])) {
    echo json_encode(["success" => false, "message" => "Missing username"]);
    exit;
}

$username = $_POST['username'];
$caption = isset($_POST['caption']) ? $_POST['caption'] : '';
$imageName = null;

// 1. Get user_id from username
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo json_encode(["success" => false, "message" => "User not found"]);
    exit;
}

$user_id = $user['id'];

// 2. Handle image upload (if any)
if (isset($_FILES['image'])) {
    $image = $_FILES['image'];
    $tmpName = $image['tmp_name'];
    $originalName = basename($image['name']);
    $ext = pathinfo($originalName, PATHINFO_EXTENSION);
    $imageName = $username . '_' . time() . '.' . $ext;

    $targetDir = "posts/";
    $targetPath = $targetDir . $imageName;

    // Ensure folder exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    if (!move_uploaded_file($tmpName, $targetPath)) {
        echo json_encode(["success" => false, "message" => "Failed to save image"]);
        exit;
    }
}

// 3. Insert post into DB
$stmt = $conn->prepare("INSERT INTO posts (user_id, image, caption) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $imageName, $caption);
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Post uploaded"]);
} else {
    echo json_encode(["success" => false, "message" => "DB insert failed"]);
}
