<?php
header('Cache-Control: no-store, private');

$token = $_COOKIE['IT490_SESSION'] ?? '';

if (!is_string($token) ||
    !preg_match('/\A[a-f0-9]{64}\z/', $token)) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/../path.inc';
require_once __DIR__ . '/../get_host_info.inc';
require_once __DIR__ . '/../rabbitMQLib.inc';

$client = new rabbitMQClient(
    __DIR__ . '/../testRabbitMQ.ini',
    'testServer'
);

$response = $client->send_request([
    'type' => 'validate_session',
    'token' => $token
]);

if (!is_array($response) ||
    ($response['ok'] ?? false) !== true ||
    !isset($response['username'])) {
    header('Location: login.php');
    exit();
}

$username = $response['username'];
$logoutCheck = hash_hmac('sha256', 'logout', $token);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Hello, <?= htmlspecialchars($username, ENT_QUOTES, 'UTF-8') ?>!</h1>

    <form action="logout.php" method="POST">
        <input type="hidden" name="csrf"
               value="<?= htmlspecialchars($logoutCheck, ENT_QUOTES, 'UTF-8') ?>">
        <button type="submit">Log out</button>
    </form>
</body>
</html>