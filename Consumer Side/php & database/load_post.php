<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

include "db.php";

$sql = "SELECT 
            u.username, 
            p.caption, 
            p.image AS post_images, 
            p.created_at, 
            u.image AS profile_image
        FROM posts p
        LEFT JOIN users u ON p.user_id = u.id
        ORDER BY p.created_at DESC";

$result = $conn->query($sql);

$posts = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $imagePaths = explode(',', $row['post_images']); // handles multiple images
        $fullImages = array_map(function($img) {
            return "http://192.168.0.199/NasugView/posts/" . trim($img);
        }, $imagePaths);

        $profile = $row['profile_image']
            ? "http://192.168.0.199/NasugView/" . $row['profile_image']
            : "http://192.168.0.199/NasugView/profiles/default.png";


        $posts[] = [
            'name' => $row['username'],
            'text' => $row['caption'],
            'date' => $row['created_at'],
            'profile' => $profile,
            'images' => $fullImages
        ];
    }
    echo json_encode(['success' => true, 'posts' => $posts]);
} else {
    echo json_encode(['success' => false, 'message' => 'No posts found.']);
}

$conn->close();
