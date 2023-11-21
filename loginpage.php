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

    <!-- Adding js/jquery for login/signup functionality -->
    <script src="http://code.jquery.com/jquery.js"></script>

    <style>

        .flexcontainer {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: auto;
            margin-top: 16%;
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

        .signup-prompt {
            font-family: "PP Editorial New";
            font-size: 14pt;
        }

        .signup-button {
            border: none;
            background: none;
            margin-left: -2.5%;
            font-family: "PP Editorial New";
            font-size: 14pt;
            text-decoration: underline;
            color: #333333;
        }

            .signup-button:hover {
                text-decoration: none;
                color: #554b41;
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

        .header {
            font-family: Helvetica;
            font-size: 18px;
        }

        .inputbars {
            margin-top: -2%;
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