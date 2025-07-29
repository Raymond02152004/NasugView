<?php
// Set headers
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Connect to the database
$conn = new mysqli("localhost", "root", "", "nasugview");

// Check DB connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed."]);
    exit();
}

// Get 'email' parameter from URL
$email = $_GET['email'] ?? '';

if (!$email) {
    echo json_encode(["success" => false, "message" => "Email is required."]);
    exit();
}

// Prepare query: join user_info and signup on username
$query = "
    SELECT 
        user_info.username,
        user_info.full_name,
        user_info.profile_img,
        user_info.cover_photo
    FROM user_info
    INNER JOIN signup ON user_info.username = signup.username
    WHERE signup.email = ?
";

// Execute prepared statement
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// If user data is found
if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();

    // Base URL for images
    $baseUrl = "http://192.168.0.101/NasugView-Backend/uploads/";

    // Append full URL to profile_img and cover_photo if not empty
    $userData['profile_img'] = !empty($userData['profile_img']) ? $baseUrl . $userData['profile_img'] : null;
    $userData['cover_photo'] = !empty($userData['cover_photo']) ? $baseUrl . $userData['cover_photo'] : null;

    echo json_encode(["success" => true, "data" => $userData]);
} else {
    echo json_encode(["success" => false, "message" => "No profile found for the given email."]);
}

// Close connections
$stmt->close();
$conn->close();
?>
