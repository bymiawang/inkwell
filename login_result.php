<?php
session_start();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="Stylesheets/style_main.css">
    <link rel="stylesheet" href="Stylesheets/navbar.css">
    <link rel="stylesheet" href="Stylesheets/homepage.css">
    <link rel="stylesheet" href="Stylesheets/cards.css">
    <link rel="stylesheet" href="Stylesheets/detail1.css">
    <link rel="stylesheet" href="Stylesheets/writer.css">
    <title>Inkwell Write Response </title>

    <!-- Google tag (gtag.js) for Google Analytics tracking -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-ERQ31ZK60Y"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-ERQ31ZK60Y');
    </script>

</head>
<body>

<!-- Navbar -->
<?php include 'navbar.php';?>

<?php

// THIS PAGE STILL NEEDS HTML LOL SORRY! -Elissa

// Check for email submitted, otherwise auto direct to login page.
if(empty($_REQUEST['email'])){
    header("Location: loginpage.php");
}


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

        $_SESSION["user_name"] = $row['user_name'];
        $_SESSION["user_id"] = $row['user_id'];
        $_SESSION["logged_in"] = True;
        $_SESSION["security_level"] = $row['security_level'];

        echo "<a href='homepage.php?'><div class='subtitle success'>Logged in successfully!<br><div style='text-decoration: underline'>Go to  Homepage</div></div></a>";
    }
    else {
        echo "Wrong password. Try again!";
    }

} else {
    // If there is no rows returned, that means the user is not in the users talbe
    echo "No user found. Create an account first!";
}


$mysql->close();


?>

</body>

