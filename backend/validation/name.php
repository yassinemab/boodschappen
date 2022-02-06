<?php

$data = json_decode(file_get_contents("php://input"));
$name = $data->name;

validateName($name);

function validateName($name) {
    $pattern = '/^[a-zA-Z\s]*$/';
    if (trim($name) == '') {
        echo "Naam is verplicht";
    } else if (!preg_match($pattern, $name)) {
        echo "De naam mag alleen letters bevatten";
    } else if(strlen($name) > 128) {
        echo "De naam is te lang";
    }
}
