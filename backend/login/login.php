<?php

include_once 'backend/config.php';

function random_str(
    int $length,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces[] = $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}

function fail($msg)
{
    header('Location: /frontend/authorization/login/login.php?error=' . urlencode($msg));
    exit();
}

if (!isset($_POST['password']) || trim($_POST['password']) == '' || !isset($_POST['email'])) {
    fail("Er ontbreken gegevens");
}

if (isset($_COOKIE['auth_token'])) {
    header("location: /");
}

$email = $conn->real_escape_string($_POST['email']);
$password = $conn->real_escape_string($_POST['password']);

$query = "SELECT id, password, active FROM users WHERE email = '$email'";
$result = $conn->query($query);
if ($result->num_rows < 1) {
    fail("Email bestaat niet");
}

$data = $result->fetch_assoc();

if ($data['active'] == 0) {
    fail("Dit account is nog niet geverifieerd.");
}

$user_id = $data['id'];
$real_password = $data['password'];
if (!password_verify($password, $real_password)) {
    fail("Onjuist wachtwoord");
}

$token = random_str(128);
setcookie("auth_token", $token, time() + (86400 * 100), '/', "", True);

$query = "SELECT * FROM authorization_tokens WHERE user_id = '$user_id'";
$result = $conn->query($query);
if ($result->num_rows > 0) {
    $query = "DELETE FROM authorization_tokens WHERE user_id = '$user_id'";
    $conn->query($query);
}
$query = "INSERT INTO authorization_tokens (name, user_id) VALUES (?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $token, $user_id);
$stmt->execute();
header("Location: /");
