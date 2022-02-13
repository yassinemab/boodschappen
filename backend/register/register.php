<?php


include_once '../config.php';
include '../validation/email.php';
include '../validation/name.php';
include '../validation/password.php';

$data = json_decode(file_get_contents("php://input"));
echo $_POST['name'];
echo $data->name . $data->lastName. $data->password . $data->confirmPassword. $data->email;
if(!(validateName($data->name) && validateName($data->surname) && validateEmail($data->email) && validatePassword($data->password) && validatePassword($data->confirmPassword) && $data->password != $data->confirmPassword)) {
    $msg = "Registreren mislukt";
    header('Location: /frontend/authorization/register/register.php?error=' . urlencode($msg));
    exit();
}

print_r($conn);