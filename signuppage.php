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

        <style>
            .flexcontainer-big {
                display: flex;
                flex-direction: row;

            }

            .flexcontainer-img {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-top: 12%;
                width: 780px;
                background-image: url("Images/signuppage.png");
                background-size: 70%;
                background-position: center;
                background-repeat: no-repeat;
            }

            .flexcontainer-form {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                margin-top: 12%;
                margin-left: 10%;
                width: 550px;
            }

            .entertext {
                --padding: 14px;
                width: max-content;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: var(--padding);
                border-radius: 28px;
                border: solid;
                border-color: var(--offwhiteshadow);
                background: white;
                width: 500px;
            }

            .search-input {
                font-size: 16px;
                margin-left: var(--padding);
                color: #333333;
                outline: none;
                border: none;
                background: transparent;
                flex: 1;
            }

            .inputbars {
                margin-top: -5%;
            }



        </style>
    </head>
    <body>
        <!-- Navbar -->
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
                    <div>[PFP]</div>
                    <div>". $_SESSION['user_name'] . "</div>
                    <div><a href='adminbackend.php'>Admin</a></div>
                  </div>";
                }
                // If user is an writer, display their profile and backend button
                else if($_SESSION["security_level"] == 1){
                    echo "<div class='profile_nav'>
                        <div>[PFP]</div>
                        <div>". $_SESSION['user_name'] ."</div>
                        <div>Writer</div>
                      </div>";
                }
                // If user is regular user, just display their profile
                else if($_SESSION["security_level"] == 2) {
                    echo "<div class='profile_nav'>
                        <div>[PFP]</div>
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
                    </div>

                    <input type="submit" class="login" value="SIGN UP">

                </form>

            </div>

            <div class="flexcontainer-img">
               <img src="Images/signuppage.png" style="width: 30%;">
            </div>

        </div>


    </body>
</htmL>