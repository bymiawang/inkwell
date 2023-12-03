<?php

// Start session in order to access current user info
session_start();

?>


<!DOCTYPE html>
<htmL>
    <head>
        <meta charset="UTF-8">
        <title>Inkwell Search</title>
        <link rel="stylesheet" href="Stylesheets/style_main.css">
        <link rel="stylesheet" href="Stylesheets/navbar.css">
        <link rel="stylesheet" href="Stylesheets/signuppage.css">

        <title>Inkwell Sign Up</title>

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


    <!-- Big flex container to place the form on the left and the image on the right -->
        <div class="flexcontainer-big">

            <!-- Form flex container -->
            <div class="flexcontainer-form">

                <form action="signup_result.php">

                    <div class="title">
                        create account
                    </div>
                    <br><br>

                    <div class="inputbars">
                        <!-- full name -->
                        <div>
                            <p class="header"><strong>full name</strong><br></p>
                            <div class="entertext">
                                <input class="search-input" type="search" name="user_name" >
                            </div>
                        </div>

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

                        <div class="centerbtn">
                            <input type="submit" class="login" value="SIGN UP">
                        </div>

                    </div>

                </form>

            </div>

            <div class="flexcontainer-img">
               <img src="Images/signuppage.png" style="width: 30%;">
            </div>

        </div>


    </body>
</htmL>