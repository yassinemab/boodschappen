<?php

$data = json_decode(file_get_contents("php://input"));
$email = $data->email;

validateEmail($email);

function validateEmail($email)
{
    if (trim($email) == '') {
        echo "Email is verplicht";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "De email is ongeldig";
    } else if (strlen($email) > 255) {
        echo "De email is te lang";
    }
}
