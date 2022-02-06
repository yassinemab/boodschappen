<?php

$data = json_decode(file_get_contents("php://input"));
$password = $data->password;

validatePassword($password);

function validatePassword($password) {
    if (trim($password) == '') {
        echo "Wachtwoord is verplicht";
    } else if (strlen($password) < 8) {
        echo "Het wachtwoord moet minimaal 8 tekens bevatten";
    } else if(strlen($password) > 128) {
        echo "Het wachtwoord is te lang";
    }
}
