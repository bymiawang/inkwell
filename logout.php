<?php
session_start();
?>

<?php

unset($_SESSION["logged_in"]);
unset($_SESSION["user_name"]);
unset($_SESSION["security_level"]);


?>

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
echo "<div class='subtitle success'>You are successfully logged out!";
echo "<a href='homepage.php'><div style='text-decoration: underline'>Back to homepage<div></div></a></div>";
?>

</body>
