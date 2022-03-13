<?php
include_once '../config.php';
require_once 'webscraping/ah.php';
require_once 'webscraping/dirk.php';

$data = json_decode(file_get_contents("php://input"));
$query = $data->query;

if (!isset($query)) {
    exit();
}

$output_ah = ah_get_all_products($query, $token);


// echo ",";
// echo "<br>-----------------------------------------------<br>";
$output = dirk_get_all_products($query, $token);
// echo array("dirk_products" => $output);
// echo $output;
