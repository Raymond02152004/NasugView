<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

$conn = new mysqli("localhost", "root", "", "nasugview");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "DB connection failed"]);
    exit;
}

$username = $_POST['username'] ?? '';
$content = $_POST['content'] ?? '';
$imagePaths = [];

if (!empty($_FILES['images']['name'][0])) {
    $uploadDir = 'uploads/';

    foreach ($_FILES['images']['tmp_name'] as $index => $tmpName) {
        if ($_FILES['images']['error'][$index] === 0) {
            $originalName = basename($_FILES['images']['name'][$index]);
            $filename = uniqid() . '_' . $originalName;
            $uploadPath = $uploadDir . $filename;

            if (move_uploaded_file($tmpName, $uploadPath)) {
                // Normalize path for cross-platform compatibility
                $normalizedPath = str_replace('\\', '/', $uploadPath);
                $imagePaths[] = $normalizedPath;
            }
        }
    }
}

// Encode image paths as JSON
$imageJSON = implode(',', $imagePaths); // ✅ Comma-separated string

$stmt = $conn->prepare("INSERT INTO posts (username, content, image) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $content, $imageJSON);
$success = $stmt->execute();

if ($success) {
    echo json_encode([
        "success" => true,
        "message" => "Post created successfully",
        "post" => [
            "username" => $username,
            "content" => $content,
            "images" => $imagePaths
        ]
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Failed to insert post"
    ]);
}

$conn->close();
?>
