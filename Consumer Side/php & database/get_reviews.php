<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include "db.php";

$business_name = $_GET['business_name'] ?? '';

if (empty($business_name)) {
    echo json_encode(["success" => false, "message" => "Missing business name"]);
    exit;
}

$query = "SELECT username, excellent_rating, service_rating, comment, created_at, image_path FROM reviews WHERE business_name = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    echo json_encode(["success" => false, "message" => "SQL Prepare failed: " . $conn->error]);
    exit;
}

$stmt->bind_param("s", $business_name);
$stmt->execute();
$result = $stmt->get_result();

$reviews = [];
$starCounts = [0, 0, 0, 0, 0]; // index 0 = 5 stars

$imageBase = "http://192.168.0.199/NasugView/reviews/";

while ($row = $result->fetch_assoc()) {
    $rating = ($row["excellent_rating"] + $row["service_rating"]) / 2;
    $rounded = round($rating);

    if ($rounded >= 1 && $rounded <= 5) {
        $starCounts[5 - $rounded]++;
    }

    $reviews[] = [
        "username" => $row["username"],
        "excellent_rating" => (int)$row["excellent_rating"],
        "service_rating" => (int)$row["service_rating"],
        "rating" => $rating,
        "comment" => $row["comment"],
        "created_at" => $row["created_at"],
        "image_path" => $row["image_path"] ? $imageBase . $row["image_path"] : null
    ];
}

echo json_encode([
    "success" => true,
    "reviews" => $reviews,
    "star_counts" => $starCounts
]);

$conn->close();
?>
