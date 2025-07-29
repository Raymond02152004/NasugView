<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *"); 
header("Access-Control-Allow-Methods: POST");

ini_set('display_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli("localhost", "root", "", "nasugview");

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]));
}

$data = json_decode(file_get_contents("php://input"));

if (!$data) {
    echo json_encode(["success" => false, "message" => "Invalid JSON input."]);
    exit();
}

// Sanitize inputs
$fullName   = $conn->real_escape_string($data->fullName ?? '');
$username   = $conn->real_escape_string($data->username ?? '');
$email      = $conn->real_escape_string($data->email ?? '');
$password   = $conn->real_escape_string($data->password ?? '');
$profileImg = $conn->real_escape_string($data->profileImg ?? 'https://via.placeholder.com/100');
$coverPhoto = $conn->real_escape_string($data->coverPhoto ?? 'https://via.placeholder.com/600x200');

// Validate required fields
if (!$fullName || !$username || !$email || !$password) {
    echo json_encode(["success" => false, "message" => "All fields are required."]);
    exit();
}

$hashedPassword = $password; // In production, use password_hash()

// Check for duplicate username/email
$checkQuery = "SELECT * FROM signup WHERE username = '$username' OR email = '$email'";
$result = $conn->query($checkQuery);
if ($result->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "Username or Email already exists."]);
    exit();
}

// Start transaction
$conn->begin_transaction();

try {
    // ✅ 1. Insert into signup
    $insertSignup = "
        INSERT INTO signup (username, email, password, full_name)
        VALUES ('$username', '$email', '$hashedPassword', '$fullName')
    ";
    if (!$conn->query($insertSignup)) {
        throw new Exception("Failed to insert into signup: " . $conn->error);
    }

    // ✅ 2. Insert into login
    $insertLogin = "
        INSERT INTO login (username, email, password)
        VALUES ('$username', '$email', '$hashedPassword')
    ";
    if (!$conn->query($insertLogin)) {
        throw new Exception("Failed to insert into login: " . $conn->error);
    }

    // ✅ 3. Insert into user_info
    $insertUserInfo = "
        INSERT INTO user_info (username, full_name, profile_img, cover_photo)
        VALUES ('$username', '$fullName', '$profileImg', '$coverPhoto')
    ";
    if (!$conn->query($insertUserInfo)) {
        throw new Exception("Failed to insert into user_info: " . $conn->error);
    }

    $conn->commit();

    echo json_encode([
        "success" => true,
        "message" => "Account created successfully!",
        "user" => [
            "username" => $username,
            "fullName" => $fullName,
            "email" => $email,
                    "profilePicture" => $userInfo['profile_img'] ?? null, // Changed to camelCase
            "coverPhoto" => $coverPhoto
        ]
    ]);

} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

$conn->close();
?>
