<?php

$data = json_decode(file_get_contents("php://input"));
$query = $data->query;

if (!isset($query)) {
    exit();
}

function get_vomar_products($query)
{
    $url = 'https://api.vomar.nl/api/v1/article/search';
    $params = ["searchString" => $query, "limit" => 30];
    $request_url = $url . "?" .  http_build_query($params);
    $curl = curl_init($request_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, []);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

function vomar_get_all_products($query)
{
    $products = get_vomar_products($query);
    return $products;
}