<?php
include_once "../config.php";
include_once "../isLoggedIn.php";

$data = json_decode(file_get_contents("php://input"));
$product_id = $conn->real_escape_string($data->id);

if (!isLoggedIn($conn)) {
    echo "LOGIN";
    exit();
}


$query = "SELECT MAX(id), MIN(id) FROM products";
$result = $conn->query($query)->fetch_assoc();
$max_id = $result["MAX(id)"];
$min_id = $result["MIN(id)"];

if (!is_numeric($product_id) || $product_id < $min_id || $product_id > $max_id) {
    echo "INVALID";
    exit();
}

$user_id = getUserId($conn);

$query = "SELECT * FROM shopping_lists WHERE user_id = $user_id AND product_id = $product_id";
$result = $conn->query($query);
if ($result->num_rows == 0) {
    exit();
} else {
    $amount = $conn->real_escape_string($result->fetch_assoc()["amount"]);
    if ($amount > 1) {
        $new_amount = $amount - 1;
        $query = "UPDATE shopping_lists SET amount = ? WHERE product_id = ? AND user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ddd", $new_amount, $product_id, $user_id);
        $stmt->execute();
    } else if ($amount == 1) {
        $query = "DELETE FROM shopping_lists WHERE product_id = ? AND user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("dd", $product_id, $user_id);
        $stmt->execute();
    }
}
echo $product_id;
