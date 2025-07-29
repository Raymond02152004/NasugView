<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$conn = new mysqli("localhost", "root", "", "nasugview");

if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed."]);
    exit();
}

$username = $_GET['username'] ?? '';

if (!$username) {
    echo json_encode(["success" => false, "message" => "Username is required."]);
    exit();
}

// Query to get posts by username
$query = "
    SELECT content, image, created_at AS timestamp
    FROM posts
    WHERE username = ?
    ORDER BY created_at DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$posts = [];
$baseUrl = "http://192.168.0.101/NasugView-Backend/";

while ($row = $result->fetch_assoc()) {
    $row['image'] = !empty($row['image']) ? $baseUrl . "uploads/" . $row['image'] : null;
    $posts[] = $row;
}

echo json_encode(["success" => true, "posts" => $posts]);

$stmt->close();
$conn->close();
?>
