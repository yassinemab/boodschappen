<?php
include_once '../config.php';

if(isset($_COOKIE['auth_token'])) {
    $token = $conn->real_escape_string($_COOKIE['auth_token']);

    $query = "DELETE FROM authorization_tokens WHERE name = '$token'";
    $conn->query($query);
    unset($_COOKIE['auth_token']);
    setcookie('auth_token', null, -1, '/');
}