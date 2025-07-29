<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");

$conn = new mysqli("localhost", "root", "", "nasugview");

if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed"
    ]);
    exit();
}

$data = json_decode(file_get_contents("php://input"));

if (isset($data->username) && isset($data->password)) {
    $username = $conn->real_escape_string($data->username);
    $password = $data->password;

    $query = "SELECT * FROM login WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows === 1) {
        $loginUser = $result->fetch_assoc();

        // NOTE: For secure handling, consider using password_hash() and password_verify() instead.
        if ($password === $loginUser['password']) {
            $userInfoQuery = "SELECT * FROM user_info WHERE username = '$username'";
            $userInfoResult = $conn->query($userInfoQuery);
            $userInfo = $userInfoResult->fetch_assoc();

            // Retrieve full name either from user_info or fallback to login table.
            $fullName = $userInfo['full_name'] ?? $loginUser['full_name'] ?? null;

            echo json_encode([
                "success" => true,
                "message" => "Login successful",
                "user" => [
                    "username" => $loginUser['username'],
                    "email" => $loginUser['email'],
                    "fullName" => $fullName,  // Changed to camelCase
                    "profilePicture" => $userInfo['profile_img'] ?? null, // Changed to camelCase
                    "coverPhoto" => $userInfo['cover_photo'] ?? null       // Changed to camelCase
                ]
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Incorrect password"
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "User not found"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Incomplete input"
    ]);
}

$conn->close();
?>
