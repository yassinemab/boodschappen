<?php

define(
    "HEADERS",
    [
        'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10.15; rv:81.0) Gecko/20100101 Firefox/81.0'
    ]
);

$data = json_decode(file_get_contents("php://input"));
$query = $data->query;

if (!isset($query)) {
    exit();
}

function get_jumbo_products($query)
{
    $url = 'https://mobileapi.jumbo.com/v14/search';
    $params = ["query" => $query, "limit"=> 30];
    $request_url = $url . "?" .  http_build_query($params);
    $curl = curl_init($request_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, HEADERS);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

function jumbo_get_all_products($query)
{
    $products = get_jumbo_products($query);
    return $products;
}

jumbo_get_all_products($query);
