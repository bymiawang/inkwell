<?php

// THIS PAGE STILL NEEDS HTML LOL SORRY! -Elissa

// Check for email submitted, otherwise auto direct to login page.
if(empty($_REQUEST['email']) || empty($_REQUEST['password'])){
    header("Location: loginpage.php");
}


require_once 'config.php'; // Edit with your path to config.php file

$mysql = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

// SQL
// Get the user who matches the username
$sql = "INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `security_level`, `password`) VALUES (NULL, '"
    . $_REQUEST["user_name"] . "','"
    . $_REQUEST["email"] . "', '2', '"
    . $_REQUEST["password"]
    . "');";


$results = $mysql->query($sql);

// Check for errors in execution
if (!$results) {
    die("Something went wrong. <br />" . $mysql->error);
}
else {
    # Start a session
    session_start();
    $_SESSION["user_name"] = $_REQUEST['user_name'];
    $_SESSION["logged_in"] = True;
    $_SESSION["security_level"] = 2;

    echo "<a href='homepage.php?'>Signed up in successfully! Go to Homepage</a>";
}

$mysql->close();