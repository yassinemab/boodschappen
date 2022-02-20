<?php

/* Server */
// $host = 'rdbms.strato.de';
// $database = 'DBS5427279';
// $username = 'DBU127871';
// $password = 'GhM2SXbL6P6WTfs';

/* Lokaal */
$host = 'localhost';
$database = 'boodschappen';
$username = 'root';
$password = '';

$succes = false;

$conn = new mysqli($host, $username, $password);

if ($conn->connect_error) die(mysqli_connect_error());
