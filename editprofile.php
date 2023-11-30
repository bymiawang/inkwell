<?php

session_start();
require_once 'config.php';

$mysql = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check for database connection error
if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

// Profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_name'])) {
        echo "Session user_name not set";
    }
    else {
        $username = $_SESSION['user_name']; // Use the username stored in the session
        $newUsername = $_POST['user_name'];
        $newPassword = $_POST['password'];

        // First, get the user_id from the database based on the username
        $userIdQuery = "SELECT user_id FROM users WHERE user_name = ?";
        if ($stmt = $mysql->prepare($userIdQuery)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row) {
                $userId = $row['user_id'];

                // SQL to update user's data
                $updateSql = "UPDATE users SET user_name = ?, password = ? WHERE user_id = ?";
                if ($stmt = $mysql->prepare($updateSql)) {
                    $stmt->bind_param("ssi", $newUsername, $newPassword, $userId);
                    if ($stmt->execute()) {
                        $_SESSION['user_name'] = $newUsername;
                    } else {
                        echo "Error updating profile: " . $stmt->error . "<br />";
                    }
                } else {
                    echo "Error preparing update statement: " . $mysql->error;
                }
            } else {
                echo "User not found";
            }
        } else {
            echo "Error preparing user ID query: " . $mysql->error;
        }
    }
}
else{
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inkwell Edit Profile</title>
    <link rel="stylesheet" href="Stylesheets/style_main.css">
    <link rel="stylesheet" href="Stylesheets/navbar.css">
    <link rel="stylesheet" href="Stylesheets/editprofile.css">
    <style>
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
                    <div><a href='editprofile.php'><div class='pfp'></div></a></div>
                    <div>". $_SESSION['user_name'] . "</div>
                    <div><a href='adminbackend.php'>Admin</a></div>
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

<!-- Form -->
<form method="post" action="editprofile.php">
    <div class="flexcontainer">
        <div class="title">
            edit profile
        </div>
        <br><br>
        <div class="inputbars">
            <!-- email -->
            <div>
                <div class="entertext">
                    <input class="search-input" type="search" name="user_name" placeholder="username" >
                </div>
            </div>
            <!-- password -->
            <div>
                <div class="entertext">
                    <input class="search-input" type="search" name="password" placeholder="password" >
                </div>
            </div>
            <br>
        </div>
        <input type="submit" class="login" value="SUBMIT">
    </div>
</form>

</body>
</html>