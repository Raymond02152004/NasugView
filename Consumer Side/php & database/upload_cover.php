<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include "db.php";

$username = $_POST["username"];
$image = $_FILES["image"];

$targetDir = "covers/";
$ext = pathinfo($image["name"], PATHINFO_EXTENSION);
$filename = $username . '_cover_' . time() . '.' . $ext;
$targetFile = $targetDir . $filename;

// Ensure the covers/ folder exists
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Move uploaded file
if (move_uploaded_file($image["tmp_name"], $targetFile)) {
    $sql = "UPDATE users SET cover = '$targetFile' WHERE username = '$username'";
    if ($conn->query($sql)) {
        echo json_encode(["success" => true, "image" => $targetFile]);
    } else {
        echo json_encode(["success" => false, "message" => "DB update failed"]);
    }
} else {
    error_log("Upload failed for $username: " . print_r($_FILES, true));
    echo json_encode(["success" => false, "message" => "Upload failed"]);
}

$conn->close();
?>
