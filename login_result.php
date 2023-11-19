<?php

// THIS PAGE STILL NEEDS HTML LOL SORRY! -Elissa

// Check for email submitted, otherwise auto direct to login page.

require_once 'config.php'; // Edit with your path to config.php file

$mysql = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

// SQL
// Get the user who matches the username
$sql = "SELECT * FROM users WHERE user_email = '" . $_REQUEST["email"] . "'";
$results = $mysql->query($sql);

// Check for errors in execution
if (!$results) {
    die("Error retrieving results: " . $mysql->error);
}

if ($results->num_rows > 0) {
    # If there are results, that means that the user exists in the table
    $row = $results->fetch_assoc();

    # Compare the password in the database to the submitted password
    if ($row['password'] == $_REQUEST["password"]) {
        # Start a session
        session_start();
        $_SESSION["user_name"] = $row['user_name'];
        $_SESSION["logged_in"] = True;
        $_SESSION["security_level"] = $row['security_level'];

        echo "<a href='homepage.php?'>Logged in successfully! Go to Homepage</a>";
    }
    else {
        echo "Wrong password. Try again!";
    }

} else {
    // If there is no rows returned, that means the user is not in the users talbe
    echo "No user found. Create an account first!";
}


$mysql->close();




