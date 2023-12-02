<?php

echo '<div class = "navbar">
        <div id = "logosearch">
            <a href="homepage.php" style="text-decoration: none; color: inherit;">
                <div id="inkwell"><em>Inkwell</em></div>
            </a>
            <a href="searchpage.php"><button type="button" class = searchbutton>
                    <img src="Images/Search%20Icon.png" alt="search icon">
                </button></a>
        </div>';

// If user is logged in, hide the login buttons
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']){
    // If user is an admin, display their profile and backend button
    if($_SESSION["security_level"] == 0){
        echo "<div class='profile_nav'>
                <div><a href='editprofile.php'><div class='pfp'></div></a></div>
                <div>". $_SESSION['user_name'] . "</div>
                <div><a class='label' href='adminbackend.php'>Admin</a></div>
              </div>";
    }
    // If user is an writer, display their profile and backend button
    else if($_SESSION["security_level"] == 1){
        echo "<div class='profile_nav'>
                    <div><a href='editprofile.php'><div class='pfp'></div></a></div>
                    <div>". $_SESSION['user_name'] ."</div>
                    <div><a class='label' href='writerbackend.php'>Writer</a></div>
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

echo "</div>";


