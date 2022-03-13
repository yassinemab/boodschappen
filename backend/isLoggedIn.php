<?php

function isLoggedIn() {
    include_once 'config.php';
    if(!isset($_COOKIE['auth_token'])) {
        return false;
    }

    $token = $conn->real_escape_string($_COOKIE['auth_token']);
    $query = "SELECT * FROM authorization_tokens WHERE name = '$token'";
    $result = $conn->query($query);
    if ($result->num_rows < 1) {
        return false;
    }

    return true;
}
