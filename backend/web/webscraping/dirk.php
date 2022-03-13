<?php

function get_products($query)
{
    $url = 'https://api.dirk.nl/v1/assortmentcache/search/66';
    $params = ["api_key" => '6d3a42a3-6d93-4f98-838d-bcc0ab2307fd', "search" => $query];
    $request_url = $url . "?" .  http_build_query($params);
    $curl = curl_init($request_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, []);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

function dirk_get_all_products($query)
{
    $products = get_products($query);
    return $products;
}
