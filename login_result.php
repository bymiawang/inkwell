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
<div class = "navbar">
    <div id = "logosearch">
        <a href="homepage.php" style="text-decoration: none; color: inherit;">
            <div id="inkwell"><em>Inkwell</em></div>
        </a>
        <a href="searchpage.php"><button type="button" class = searchbutton>
                <img src="Images/Search%20Icon.png" alt="search icon">
            </button></a>
    </div>

    <?php

    // If user is logged in, hide the login buttons
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']){
        // If user is an admin, display their profile and backend button
        if($_SESSION["security_level"] == 0){
            echo "<div class='profile_nav'>
        <div><a href='editprofile.php'><div class='pfp'></div></a></div>
        <div>". $_SESSION['user_name'] . "</div>
        <div class='role'>(Admin)</div>
        <div><a href='adminbackend.php'><img src='Images/Vector.svg' class='edit'></a></div>
    </div>";
        }
        // If user is an writer, display their profile and backend button
        else if($_SESSION["security_level"] == 1){
            echo "<div class='profile_nav'>
        <div><a href='editprofile.php'><div class='pfp'></div></a></div>
        <div>". $_SESSION['user_name'] ."</div>
        <div>Writer</div>
    </div>";
        }
        // If user is regular user, just display their profile
        else if($_SESSION["security_level"] == 2) {
            echo "<div class='profile_nav'>
        <div><a href='editprofile.php'><div class='pfp'></div></a></div>
        <div>". $_SESSION['user_name'] ."</div>
    </div>";
        }

        echo "<a href='logout.php'><button type='button' class = signup>
        LOGOUT
    </button></a>";
    }
    else {
        echo "<div class='login_buttons'>
        <a href='signuppage.php'><button type='button' class = signup>
            SIGN UP
        </button></a>
        <a href='loginpage.php'><button type='button' class = login>
            LOG IN
        </button></a>
    </div>";
    }
    ?>

</div>
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

