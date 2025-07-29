<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include "db.php";

if (!isset($_POST['username'])) {
    echo json_encode([
        "success" => false,
        "message" => "Username is required."
    ]);
    exit;
}

$username = $_POST['username'];

$sql = "SELECT * FROM reviews WHERE username = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$reviews = [];

while ($row = $result->fetch_assoc()) {
    $reviews[] = [
        "id" => $row["id"],
        "username" => $row["username"],
        "business_name" => $row["business_name"],
        "excellent_rating" => (int)$row["excellent_rating"],
        "service_rating" => (int)$row["service_rating"],
        "comment" => $row["comment"],
        "created_at" => $row["created_at"],
        "image_path" => $row["image_path"] ? $row["image_path"] : null
    ];
}

echo json_encode([
    "success" => true,
    "reviews" => $reviews
]);

$conn->close();
