<?php
include_once '../config.php';
$data = json_decode(file_get_contents("php://input"));
$query = $data->query;

if (!isset($query)) {
    exit();
}

$command = exec('python3 products.py ' . $query, $output);

echo json_encode($output);