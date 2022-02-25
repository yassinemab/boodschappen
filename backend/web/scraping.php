<?php
include_once '../config.php';
$data = json_decode(file_get_contents("php://input"));
$query = $data->query;

if (!isset($query)) {
    echo "FAIL";
    exit();
}

$command = escapeshellcmd('python3 products.py ' . $query);
$output = shell_exec($command);
echo json_encode($output);
