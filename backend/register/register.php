<?php

include_once '../config.php';

function fail($msg)
{
    header('Location: /frontend/authorization/register/register.php?error=' . urlencode($msg));
    exit();
}

function validateEmail($email)
{
    if (trim($email) == '') {
        return false;
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    } else if (strlen($email) > 255) {
        return false;
    }
    return true;
}

function validatePassword($password)
{
    if (trim($password) == '') {
        return false;
    } else if (strlen($password) < 8) {
        return false;
    } else if (strlen($password) > 128) {
        return false;
    }
    return true;
}

function validateName($name)
{
    $pattern = '/^[a-zA-Z\s]*$/';
    if (trim($name) == '') {
        return false;
    } else if (!preg_match($pattern, $name)) {
        return false;
    } else if (strlen($name) > 128) {
        return false;
    }
    return true;
}


$name = $_POST["name"];
$lastName = $_POST["surname"];
$email = $_POST["email"];
$password = $_POST["password"];
$confirmPassword = $_POST["confirmPassword"];

if (!(validateName($name) && validateName($lastName) && validateEmail($email) && validatePassword($password) && validatePassword($confirmPassword) && $password == $confirmPassword)) {
    fail("Registreren mislukt");
}

$name = $conn->real_escape_string($name);
$lastName = $conn->real_escape_string($lastName);
$password = password_hash($conn->real_escape_string($password), PASSWORD_BCRYPT, ["cost" => 12]);
$email = $conn->real_escape_string($email);

$query = "SELECT email FROM users WHERE email = '$email'";
$result = $conn->query($query);
if ($conn->affected_rows > 0) {
    echo $conn->affected_rows;
    fail("Deze email bestaat al");
}

$query = "INSERT INTO users (first_name, last_name, email, password, active) VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);
$zero = 0;

$stmt->bind_param('ssssi', $name, $lastName, $email, $password, $zero);
$stmt->execute();
header('Location: /');
// if (!$result) echo $conn->error;
// print_r($result);
