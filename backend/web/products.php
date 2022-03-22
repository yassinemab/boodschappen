<?php

include_once "../config.php";
require_once 'webscraping/ah.php';
require_once 'webscraping/dirk.php';
require_once 'webscraping/vomar.php';
require_once 'webscraping/dekamarkt.php';
include_once "../brands/brands.model.php";
// require_once 'webscraping/jumbo.php';

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

$query = "SELECT MAX(Id) as MAX_ID from products";
$result = $conn->query($query);
$cur_id = $result->fetch_assoc()["MAX_ID"] + 1;

function insertShopProduct($product, $conn, $cur_id)
{
    // $query = "INSERT INTO shop_products (title,  "
    return $cur_id + 1;
}

function insertQuery($query, $conn)
{
    $db_query = "INSERT INTO queries (name, updated_at) VALUES (?, ?)";
    $stmt = $conn->prepare($db_query);
    $stmt->bind_param("ss", $query, date("Y/m/d"));
    $stmt->execute();
    return $conn->insert_id;
}

function getProductById($id, $conn)
{
    $query = "SELECT * FROM products WHERE id = '$id'";
    $result = $conn->query($query);
    return $result->fetch_assoc();
}

//MULTITHREAD DEZE SHIT
$data = json_decode(file_get_contents("php://input"));
$page = $data->page;
$query = $conn->real_escape_string($data->query);

if (!isset($page)) {
    $page = 1;
}

if (!isset($query)) {
    exit();
}

$result_num = ($page - 1) * 24;

// See if query already exists. If not, insert. If it does, see if it needs to be updated and get the products from the db.
$db_query = "SELECT * FROM queries WHERE name = '$query'";
$result = $conn->query(($db_query));
if ($result->num_rows > 0) {
    $response = $result->fetch_assoc();
    $query_id = $response["id"];
    $now = time();
    $your_date = strtotime($response["updated_at"]);
    $datediff = $now - $your_date;
    // This means the query needs to be updated.
    if (floor($datediff / (60 * 60 * 24)) >= 1) {
        $db_query = "UPDATE queries SET updated_at = $now";
        echo $now;
        $db_query = "DELETE FROM products WHERE query_id = $query_id";
        // $conn->query()
        // fetch the shit from the apis
        // Update all the values in the db
    } else {
        // Just fetch from db
        $db_query = "SELECT * FROM query_products WHERE query_id = '$query_id'";
        $products = [];
        $result = $conn->query($db_query);
        while ($row = $result->fetch_assoc()) {
            $product = getProductById($row["product_id"], $conn);
            $brand_result = getBrandById($product["brand_id"], $conn);
            $brand = $brand_result == -1 ? "" : $brand_result["name"];
            array_push(
                $products,
                [
                    "id" => $product["id"],
                    "title" => $product["title"],
                    "description" => $product["description"],
                    "unitSize" => $product["unit_size"],
                    "brand" => $brand,
                    "price" => $product["avg_price"],
                    "image_url" => $product["image_url"]
                ]
            );
        }
        $amt_of_results = $result->num_rows;
        if ($amt_of_results == 1) {
            $results = $amt_of_results . " resultaat, pagina " . $page . " van de " . ceil($amt_of_results / 24);
        } else {
            $results = $amt_of_results . " resultaten, pagina " . $page . " van de " . ceil($amt_of_results / 24);
        }
        $products = array_slice($products, $result_num, 24);
        array_push($products, $results);
        echo json_encode($products);
        exit();
    }
}

$query_id = insertQuery($query, $conn);

$ah_output = ah_get_all_products($query, $token);
$dirk_output = dirk_get_all_products($query);
$vomar_output = vomar_get_all_products($query);
$deka_output = deka_get_all_products($query);
// $jumbo_output = jumbo_get_all_products($query);

$items = [];

//MULTITHREAD DEZE SHIT
$ah_products = json_decode($ah_output);
$dirk_products = json_decode($dirk_output);
$vomar_products = json_decode($vomar_output);
$deka_products = json_decode($deka_output);
// $jumbo_products = json_decode($jumbo_output);

$amt_of_results = count($ah_products->products) + count($dirk_products) + count($vomar_products) + count($deka_products);

$results = $amt_of_results . " resultaten, pagina " . $page . " van de " . round($amt_of_results / 24);

foreach ($ah_products->products as $i) {
    array_push($items, [
        "title" => $i->title, "description" => $i->descriptionHighlights, "brand" => $i->brand,
        "price" => $i->priceBeforeBonus, "image_url" => $i->images[2]->url, "unitSize" => $i->salesUnitSize, "shop" => "AH"
    ]);
    $pattern = '/\W*((?i)AH(?-i))/';
    if (str_contains($items[count($items) - 1]["title"], "AH")) {
        $items[count($items) - 1]["title"] = preg_replace($pattern, "Huismerk", $items[count($items) - 1]["title"]);
    }
    $cur_id = insertShopProduct($items[count($items) - 1], $conn, $cur_id);
}

foreach ($dirk_products as $i) {
    array_push($items, [
        "title" => $i->Brand . " " . $i->MainDescription, "description" => $i->MainDescription,
        "brand" => $i->Brand, "price" => $i->ProductPrices[0]->Price,
        "image_url" => $i->ProductPicture->Url . "?width=200&height=200&mode=crop", "unitSize" => $i->CommercialContent,
        "shop" => "DIRK"
    ]);
    if (!empty($i->SubDescription)) $items[count($items) - 1]["title"] .= " " . $i->SubDescription;
    $pattern = '/\W*((?i)1 de Beste(?-i))/';
    if (str_contains($items[count($items) - 1]["title"], "1 de Beste")) {
        $jemama = $items[count($items) - 1]["title"];
        $items[count($items) - 1]["title"] = preg_replace($pattern, "Huismerk", $items[count($items) - 1]["title"]);
    }
}

foreach ($vomar_products as $i) {
    array_push($items, [
        "title" => $i->detailedDescription, "description" => $i->description,
        "brand" => strtok($i->detailedDescription, " "), "price" => $i->price,
        "image_url" => "https://files.vomar.nl/articles/" .  $i->images[0]->imageUrl . "?width=200&height=200",
        "unitSize" => "", "shop" => "VOMAR"
    ]);
    $pattern = '/\W*((?i)Vomar(?-i))/';
    if (str_contains($items[count($items) - 1]["title"], "Vomar")) {
        $jemama = $items[count($items) - 1]["title"];
        $items[count($items) - 1]["title"] = preg_replace($pattern, "Huismerk", $items[count($items) - 1]["title"]);
    }
}

foreach ($deka_products as $i) {
    array_push($items, [
        "title" => $i->Brand . " " . $i->MainDescription, "description" => $i->MainDescription,
        "brand" => $i->Brand, "price" => $i->ProductPrices[0]->Price,
        "image_url" => $i->ProductPicture->Url . "?width=200&height=200&mode=crop",
        "unitSize" => $i->CommercialContent, "shop" => "DEKA"
    ]);
    if (!empty($i->SubDescription)) $items[count($items) - 1]["title"] .= " " . $i->SubDescription;
    $pattern = '/\W*((?i)1 de Beste(?-i))/';
    if (str_contains($items[count($items) - 1]["title"], "1 de Beste")) {
        $jemama = $items[count($items) - 1]["title"];
        $items[count($items) - 1]["title"] = preg_replace($pattern, "Huismerk", $items[count($items) - 1]["title"]);
    }
}

// foreach ($jumbo_products->products->data as $i) {
//     echo "hallo";
//     exit();
//     array_push($items, [
//         "title" => $i->title,
//         "brand" => "", "price" => $i->prices->price->amount / 100,
//         "image_url" => $i->imageInfo->primaryView[0]->url,
//         "unitSize" => $i->quantity, "shop" => "JUMBO"
//     ]);
//     $pattern = '/\W*((?i)Jumbo(?-i))/';
//     if (str_contains($items[count($items) - 1]["title"], "Jumbo")) {
//         $jemama = $items[count($items) - 1]["title"];
//         $items[count($items) - 1]["title"] = preg_replace($pattern, "Huismerk", $items[count($items) - 1]["title"]);
//     }
// }

$categorised_products = [];
$didnt_break = true;

function fix($str)
{
    $str_arr = str_split(str_replace(" ", "", strtolower($str)));
    sort($str_arr);
    return implode($str_arr);
}

// Dit moet met binary search
foreach ($items as $i) {
    foreach ($categorised_products as $j) {
        $first_title = fix($j["title"]);
        $second_title = fix($i["title"]);
        if (levenshtein($first_title, $second_title, 1, 2, 1) < 8) {
            if (strlen($j["description"]) < strlen($i["description"])) $j["description"] = $i["description"];
            if ($j["unitSize"] == "") $j["unitSize"] = $i["unitSize"];
            $didnt_break = false;
            $j["price"] = ($j["price"] * $j["amountOfShops"] + $i["price"]) / ($j["amountOfShops"] + 1);
            $j["amountOfShops"] += 1;
            break;
        }
    }
    if ($didnt_break) {
        $i["amountOfShops"] = 1;
        array_push($categorised_products, $i);
    }
    $didnt_break = true;
}

foreach ($categorised_products as $i) {
    $product_id = insertProduct($i, $query_id, $conn);
    $i["id"] = $product_id;
}

// usort($categorised_products, function ($a, $b) { //Sort the array using a user defined function
//     return $a["title"] > $b["title"] ? 1 : -1; //Compare the scores
// });

$categorised_products = array_slice($categorised_products, $result_num, 24);
array_push($categorised_products, $results);
echo json_encode($categorised_products);
