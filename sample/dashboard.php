<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <a href="logout.php">Log Out</a>
</body>
</html>