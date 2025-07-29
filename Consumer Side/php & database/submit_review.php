<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include "db.php";

// Check required POST fields
if (
    !isset($_POST['username']) ||
    !isset($_POST['business_name']) ||
    !isset($_POST['excellent_rating']) ||
    !isset($_POST['service_rating']) ||
    !isset($_POST['comment'])
) {
    echo json_encode(["success" => false, "message" => "Missing required fields."]);
    exit;
}

$username = $_POST['username'];
$business_name = $_POST['business_name'];
$excellent_rating = (int)$_POST['excellent_rating'];
$service_rating = (int)$_POST['service_rating'];
$comment = $_POST['comment'];
$created_at = date("Y-m-d H:i:s");

$imageName = null;
$uploadDir = __DIR__ . '/reviews/';  // Absolute path to the reviews folder

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $tmpName = $_FILES['image']['tmp_name'];
    $originalName = basename($_FILES['image']['name']);
    $ext = pathinfo($originalName, PATHINFO_EXTENSION);

    // Generate a unique filename to avoid overwriting
    $imageName = uniqid() . '.' . $ext;
    $destination = $uploadDir . $imageName;

    if (!move_uploaded_file($tmpName, $destination)) {
        echo json_encode(["success" => false, "message" => "Failed to move uploaded image."]);
        exit;
    }
}

$sql = "INSERT INTO reviews (username, business_name, excellent_rating, service_rating, comment, image_path, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssiisss", $username, $business_name, $excellent_rating, $service_rating, $comment, $imageName, $created_at);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Review submitted successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to insert review: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
