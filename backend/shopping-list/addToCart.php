<?php
include_once "../config.php";
include_once "../isLoggedIn.php";

$data = json_decode(file_get_contents("php://input"));
$product_id = $conn->real_escape_string($data->id);

if(!isLoggedIn($conn)) {
    echo "LOGIN";
    exit();
}


function getUserId($conn) {
    if(!isset($_COOKIE['auth_token'])) {
        return false;
    }

    $token = $conn->real_escape_string($_COOKIE['auth_token']);
    $query = "SELECT * FROM authorization_tokens WHERE name = '$token'";
    $result = $conn->query($query);
    return $result->fetch_assoc()["user_id"];
}

$query = "SELECT MAX(id) AS MAX_ID FROM products";
$max_id = $conn->query($query)->fetch_assoc()["MAX_ID"];

if(!is_numeric($product_id) || $product_id < 0 || $product_id > $max_id) {
    echo "INVALID";
    exit();
}

$user_id = getUserId($conn);

$query = "INSERT INTO shopping_lists (product_id, user_id) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("dd", $product_id, $user_id);
$stmt->execute();
echo "SUCCESS";