<?php
include_once "../config.php";
include_once "../isLoggedIn.php";
include_once "../products/products.model.php";

if(!isLoggedIn($conn)) {
    header('location: /');
    exit();
}

$user_id = $conn->real_escape_string(getUserId($conn));

$query = "SELECT * FROM shopping_lists WHERE user_id = $user_id";
$result = $conn->query($query);

$products = [];
while($row = $result->fetch_assoc()) {
    $product = getProductById($row["product_id"], $conn);
    array_push($products, [
        "id" => $product["id"],
        "title" => $product["title"],
        "image_url" => $product["image_url"],
        "unitSize" => $product["unit_size"],
        "description" => $product["description"],
        "price" => $product["avg_price"],
        "amount" => $row["amount"]
    ]);
}

echo json_encode($products);