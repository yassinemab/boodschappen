<?php

// $data = json_decode(file_get_contents("php://input"));
// $query = $data->query;

// if (!isset($query)) {
//     exit();
// }

define(
    "HEADERS",
    [
        'Host: api.ah.nl',
        'x-dynatrace: MT_3_4_772337796_1_fae7f753-3422-4a18-83c1-b8e8d21caace_0_1589_109',
        'x-application: AHWEBSHOP',
        'user-agent: Appie/8.8.2 Model/phone Android/7.0-API24',
        'content-type: application/json; charset=UTF-8',
    ]
);

$token = explode('"', get_token())[3];

function get_products_by_page($page, $token, $query)
{
    $url = 'https://api.ah.nl/mobile-services/product/search/v2';
    $params = ["sortOn" => 'RELEVANCE', "page" => $page, "size" => 50, "query" => $query];
    $request_url = $url . "?" .  http_build_query($params);
    $curl = curl_init($request_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Host: api.ah.nl',
        'x-dynatrace: MT_3_4_772337796_1_fae7f753-3422-4a18-83c1-b8e8d21caace_0_1589_109',
        'x-application: AHWEBSHOP',
        'user-agent: Appie/8.8.2 Model/phone Android/7.0-API24',
        'content-type: application/json; charset=UTF-8',
        'Authorization: Bearer ' . $token
    ]);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

function ah_get_all_products($query, $token)
{
    // echo $token;
    $products = get_products_by_page(0, $token, $query);
    return $products;
    // $all_products = [];
    // array_push($all_products, $products);
    // for ($i = 1; $i < $products['page']['totalPages']; $i++) {
    //     array_push($all_products, get_products_by_page($i, $token, $query));
    // }
    // return $all_products;
}

function get_token()
{
    $url = 'https://api.ah.nl/mobile-auth/v1/auth/token/anonymous';
    $data = ['clientId' => 'appie'];
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS,  json_encode($data));
    curl_setopt($curl, CURLOPT_HTTPHEADER, HEADERS);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

// ah_get_all_products($query, $token);
