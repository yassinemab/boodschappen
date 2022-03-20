<?php

include_once "../config.php";
require_once 'webscraping/ah.php';
require_once 'webscraping/dirk.php';
require_once 'webscraping/vomar.php';
require_once 'webscraping/dekamarkt.php';
// require_once 'webscraping/jumbo.php';

function insertProduct($product, $conn)
{
    $query = "INSERT INTO products VALUES (?, ?, ?, ?, ?, ?, ?)";
    $brand = getBrandByName($product["brand"], $conn);
    $brand = $brand == -1 ? "" : $brand["id"];
    $stmt = $conn->prepare($query);
    $stmt->bind_param(
        "sssssss",
        $product["title"],
        $product["description"],
        $product["unitSize"],
        getBrandByName($product["brand"], $conn)["id"],
        $product["price"],
        $product["image_url"],
        $product["query_id"]
    );
    $stmt->execute();
}


//MULTITHREAD DEZE SHIT
//MULTITHREAD DEZE SHIT
//MULTITHREAD DEZE SHIT
//MULTITHREAD DEZE SHIT
//MULTITHREAD DEZE SHIT
//MULTITHREAD DEZE SHIT
//MULTITHREAD DEZE SHIT
//MULTITHREAD DEZE SHIT
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

// See if query already exists. If not, insert. If it does, see if it needs to be updated and get the products from the db.
$db_query = "SELECT * FROM queries WHERE name = '$query'";
$result = $conn->query(($db_query));
if ($result->num_rows > 0) {
    $response = $result->fetch_assoc();
    $now = time();
    $your_date = strtotime($response["query_id"]);
    $datediff = $now - $your_date;
    // This means the query needs to be updated.
    if (floor($datediff / (60 * 60 * 24)) >= 1) {
        $db_query = "UPDATE queries SET updated_at = $now";
        echo $now;
        $query_id = $response["id"];
        $db_query = "DELETE FROM products WHERE query_id = $query_id";
        // $conn->query()
        // fetch the shit from the apis
        // Update all the values in the db
    } else {
        // Just fetch from db
        $db_query = "SELECT * FROM products WHERE query_id = $query_id";
        $products = [];
        $result = $conn->query($db_query);
        while ($row = $result->fetch_assoc()) {
            $brand_result = getBrandById($row["brand_id"], $conn);
            $brand = $brand_result == -1 ? "" : $brand_result["name"];
            array_push(
                $products,
                [
                    "id" => $row["id"],
                    "title" => $row["title"],
                    "description" => $row["description"],
                    "unitSize" => $row["unit_size"],
                    "brand" => $brand,
                    "price" => $row["avg_price"],
                    "image_url" => $row["image_url"]
                ]
            );
        }
        echo json_encode($products);
    }
} else {
    // Never been in the db in the first place, fetch from api
}

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

$iets = count($ah_products->products) + count($dirk_products) + count($vomar_products) + count($deka_products);

$results = $iets . " resultaten, pagina " . $page . " van de " . round($iets / 24);

foreach ($ah_products->products as $i) {
    array_push($items, [
        "title" => $i->title, "description" => $i->descriptionHighlights, "brand" => $i->brand,
        "price" => $i->priceBeforeBonus, "image_url" => $i->images[2]->url, "unitSize" => $i->salesUnitSize, "shop" => "AH",
        "query_id" => $query_id
    ]);
    $pattern = '/\W*((?i)AH(?-i))/';
    if (str_contains($items[count($items) - 1]["title"], "AH")) {
        $items[count($items) - 1]["title"] = preg_replace($pattern, "Huismerk", $items[count($items) - 1]["title"]);
    }
}

foreach ($dirk_products as $i) {
    array_push($items, [
        "title" => $i->Brand . " " . $i->MainDescription, "description" => $i->MainDescription,
        "brand" => $i->Brand, "price" => $i->ProductPrices[0]->Price,
        "image_url" => $i->ProductPicture->Url . "?width=200&height=200&mode=crop", "unitSize" => $i->CommercialContent,
        "shop" => "DIRK",  "query_id" => $query_id
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
        "unitSize" => "", "shop" => "VOMAR",  "query_id" => $query_id
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
        "unitSize" => $i->CommercialContent, "shop" => "DEKA",  "query_id" => $query_id
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

foreach($items as $i) {
    insertProduct($i, $conn);
}

$result_num = ($page - 1) * 24;

// usort($categorised_products, function ($a, $b) { //Sort the array using a user defined function
//     return $a["title"] > $b["title"] ? 1 : -1; //Compare the scores
// });

$categorised_products = array_slice($categorised_products, $result_num, 24);
array_push($categorised_products, $results);
echo json_encode($categorised_products);
