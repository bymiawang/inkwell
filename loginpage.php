<?php

// Start session in order to access current user info
session_start();

?>

<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">
    <title>Inkwell Search</title>
    <link rel="stylesheet" href="Stylesheets/style_main.css">
    <link rel="stylesheet" href="Stylesheets/navbar.css">
    <link rel="stylesheet" href="Stylesheets/loginpage.css">
    <title>Inkwell Login</title>

    <!-- Adding js/jquery for login/signup functionality -->
    <script src="http://code.jquery.com/jquery.js"></script>

    <!-- Google tag (gtag.js) for Google Analytics tracking -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-ERQ31ZK60Y"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-ERQ31ZK60Y');
    </script>

    <style>
    </style>
</head>

<body>

<!-- Navbar -->
<?php include 'navbar.php';?>


<!-- Form -->
<form action="login_result.php">
    <div class="flexcontainer">
        <div class="title">
            welcome back!
        </div>
        <br><br>

        <div class="inputbars">
            <!-- email -->
            <div>
                <p class="header"><strong>email</strong><br></p>
                <div class="entertext">
                    <input class="search-input" type="search" name="email" >
                </div>
            </div>

            <!-- password -->
            <div>
                <p class="header"><strong>password</strong><br></p>
                <div class="entertext">
                    <input class="search-input" type="search" name="password" >
                </div>
            </div>
        <br>
        </div>

        <input type="submit" class="login" value="LOG IN">

        <p class="signup-prompt">
            don't have an account?
            <a href="signuppage.php"><button type="button" class="signup-button">
            sign up
            </button></a>
        </p>

    </div>

</form>



</body>
</html>