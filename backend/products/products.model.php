<?php

function getProductById($id, $conn)
{
    $query = "SELECT * FROM products WHERE id = '$id'";
    $result = $conn->query($query);
    return $result->fetch_assoc();
}

function insertProduct($product, $query_id, $conn)
{
    $title = $conn->real_escape_string($product["title"]);
    $query = "SELECT id FROM products WHERE title = $title";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $product_id = $result->fetch_assoc()["id"];
    } else {
        $query = "INSERT INTO products (title, description, unit_size, brand_id, avg_price, image_url) VALUES (?, ?, ?, ?, ?, ?)";
        $brand = getBrandByName($product["brand"], $conn);
        $brand = $brand == -1 ? "" : $brand["id"];
        $stmt = $conn->prepare($query);
        $stmt->bind_param(
            "sssdds",
            $product["title"],
            $product["description"],
            $product["unitSize"],
            $brand,
            $product["price"],
            $product["image_url"]
        );
        $stmt->execute();
        $product_id = $conn->insert_id;
    }

    $query = "INSERT INTO query_products (query_id, product_id) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("dd", $query_id, $product_id);
    $stmt->execute();
    return $product_id;
}
