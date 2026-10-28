<?php

class loginDB
{
private $logindb;

public function __construct()
{
	$this->logindb = new mysqli("127.0.0.1","admin","@d445m1nP455","login");

	if ($this->logindb->connect_errno != 0)
	{
		echo "Error connecting to database: ".$this->logindb->connect_error.PHP_EOL;
		exit(1);
	}
	echo "correctly connected to database".PHP_EOL;
}

public function validateLogin($username,$password)
{
	$un = $this->logindb->real_escape_string($username);
	$pw = $this->logindb->real_escape_string($password);
	$statement = "select * from users where screenname = '$un'";
	$response = $this->logindb->query($statement);

	while ($row = $response->fetch_assoc())
	{
		echo "checking password for $username".PHP_EOL;
		if ($row["password"] == $pw)
		{
			return 1;
		}
		echo "passwords did not match for $username".PHP_EOL;
	}
	return 0;
}

public function registerUser($name,$username,$email,$password)
{
	$nm = $this->logindb->real_escape_string($name);
    $un = $this->logindb->real_escape_string($username);
	$em = $this->logindb->real_escape_string($email);
    $pw = $this->logindb->real_escape_string($password);

    $checkStmt = "SELECT * FROM users WHERE screenname = '$un'";
    $result = $this->logindb->query($checkStmt);

    if ($result && $result->num_rows > 0)
    {
        echo "Registration failed: Username '$username' already exists.".PHP_EOL;
        return 0;
    }

    $insertStmt = "INSERT INTO users (name, screenname, email, password) VALUES ('$nm', '$un', '$em', '$pw')";
        
    if ($this->logindb->query($insertStmt))
    {
        echo "Successfully registered new user: $username".PHP_EOL;
        return 1;
    }

    echo "Error inserting user into database.".PHP_EOL;
    return 0;
}
}
?>
