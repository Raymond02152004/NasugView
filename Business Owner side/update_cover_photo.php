<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// CORS headers for React Native
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$response = ['success' => false];

// Allow only POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';

    if (!$username) {
        echo json_encode(["success" => false, "message" => "Username is required."]);
        exit();
    }

    if (!isset($_FILES['cover_photo'])) {
        echo json_encode(["success" => false, "message" => "No file uploaded."]);
        exit();
    }

    $targetDir = __DIR__ . "/uploads/";
    $baseUrl = "http://192.168.0.101/NasugView-Backend/uploads/";

    // Create uploads folder if not exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    // Sanitize and create unique filename
    $originalName = basename($_FILES["cover_photo"]["name"]);
    $uniqueFileName = uniqid() . "_" . preg_replace("/[^a-zA-Z0-9._-]/", "_", $originalName);
    $targetFilePath = $targetDir . $uniqueFileName;

    // Upload file
    if (move_uploaded_file($_FILES["cover_photo"]["tmp_name"], $targetFilePath)) {
        // Connect to DB
        $db = new mysqli("localhost", "root", "", "nasugview");

        if ($db->connect_error) {
            echo json_encode(["success" => false, "message" => "Database connection failed."]);
            exit();
        }

        // Update cover photo in DB
        $stmt = $db->prepare("UPDATE user_info SET cover_photo = ? WHERE username = ?");
        $stmt->bind_param("ss", $uniqueFileName, $username);

        if ($stmt->execute()) {
            echo json_encode([
                "success" => true,
                "cover_photo" => $baseUrl . $uniqueFileName,
                "message" => "Cover photo updated successfully."
            ]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update database."]);
        }

        $stmt->close();
        $db->close();
    } else {
        echo json_encode(["success" => false, "message" => "Failed to move uploaded file."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
exit();
