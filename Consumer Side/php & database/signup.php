<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include "db.php";


$data = json_decode(file_get_contents("php://input"), true);

$email = $conn->real_escape_string($data["email"]);
$username = $conn->real_escape_string($data["username"]);
$password = password_hash($data["password"], PASSWORD_BCRYPT);

$sql = "INSERT INTO users (email, username, password) VALUES ('$email', '$username', '$password')";

if ($conn->query($sql)) {
    echo json_encode(["success" => true, "message" => "User registered successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Username already exists or error"]);
}

$conn->close();
?>
