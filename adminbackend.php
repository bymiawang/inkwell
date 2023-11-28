<?php
    // Start session in order to access current user info
    session_start();

    // Check for admin submitted, otherwise auto direct to homepage page.
    if(isset($_SESSION['security_level']) && $_SESSION['security_level'] != 0){
        header("Location: homepage.php");
    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="Stylesheets/style_main.css">
    <link rel="stylesheet" href="Stylesheets/navbar.css">
    <link rel="stylesheet" href="Stylesheets/homepage.css">
    <link rel="stylesheet" href="Stylesheets/cards.css">
    <link rel="stylesheet" href="Stylesheets/result.css">
    <link rel="stylesheet" href="Stylesheets/writer.css">
    <title>Inkwell Admin </title>
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

<div class="resultcontainer">
    <div class="subtitle" style="margin-top:20px">
        Submissions without Responses
    </div>
    <div class="resultcards">
        <div class="writercard">
            <a href="detailpagebase.php?id=<?= urlencode($row['response_id']) ?>" class="card-anchor">
                <div class="resultcard">
                    <div class="thumbnail">
                        <img src="Images/thumbnaildemo.png">
                    </div>
                    <div class="cardcontent">
                        <div class="carddate">November 16, 2023</div>
                        <div class="card-title">
                            Is it a red flag if?
                        </div>
                        <div class="cardtext">
                            Dear writers, the other day I met this guy at a dorm party.
                            I’d heard of him before (friend of a friend or something)
                            but wasn’t particularly interested or anything like that.
                            We ended up talking for a fdafdfdsafdsafdsafdspretty long time,
                            and he was definitely flirting. I was still trying to decide...
                        </div>
                    </div>
                </div>
            </a>
            <div class="writeresponse">
                <button type="button" class = write>
                    WRITE A RESPONSE
                </button>
                <button type="button" class = delete>
                    DELETE
                </button>
            </div>
        </div>

    </div>

</div>

</body>
</html>

