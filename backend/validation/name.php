<?php

$data = json_decode(file_get_contents("php://input"));
$name = $data->name;

validateName($name);

function validateName($name) {
    $pattern = '/^[a-zA-Z\s]*$/';
    if (trim($name) == '') {
        echo "Naam is verplicht";
        return false;
    } else if (!preg_match($pattern, $name)) {
        echo "De naam mag alleen letters bevatten";
        return false;
    } else if(strlen($name) > 128) {
        echo "De naam is te lang";
        return false;
    }
    return true;
}
