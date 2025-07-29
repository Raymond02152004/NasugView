<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include "db.php";

$sql = "SELECT name, image_url, address, category, rating FROM businesses ORDER BY rating DESC";
$result = mysqli_query($conn, $sql);

if ($result) {
    $businesses = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $businesses[] = [
            "name" => $row["name"],
            "image_url" => "http://192.168.0.199/NasugView/" . $row["image_url"],
            "address" => $row["address"],
            "category" => $row["category"],
            "rating" => (float) $row["rating"],
        ];
    }

    echo json_encode(["success" => true, "businesses" => $businesses]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to load businesses"]);
}
