<?php

$responseMessage = "";
$isSuccess = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $type = $_POST['type'] ?? '';

    if ($type === "login") {
        require_once(__DIR__ . '/../path.inc');
        require_once(__DIR__ . '/../get_host_info.inc');
        require_once(__DIR__ . '/../rabbitMQLib.inc');

        $client = new rabbitMQClient("testRabbitMQ.ini", "testServer");

        $request = array();
        $request['type'] = "login";
        $request['username'] = $username;
        $request['password'] = $password;
        $request['message'] = "Web form login attempt";

        $response = $client->send_request($request);

        if ($response == 1 && $response !== "0") {	
			session_start();
			$_SESSION['username'] = $username;
			$_SESSION['sessionId'] = $response;

			header("Location: dashboard.php");
			exit();
        } else {
            $responseMessage = "Login Failed. Invalid username or password.";
            $isSuccess = false;
        }
    }
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
</head>
<body>
	<h2>Login</h2>
	<form action="login.php" method="POST">
		<input type="hidden" name="type" value="login">

		<label for="username">Username:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

		<button type="submit">Log In</button>

		<p>Don't have an account? <a href="register.php">Register here</a></p>
	</form>
</body>
</html>