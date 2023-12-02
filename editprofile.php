<?php

session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in'])){
    header("Location: loginpage.php"); // Redirect to login page
    exit;
}

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
<?php include 'navbar.php';?>


<div class="flexcontainer">
    <a href='likedposts.php'>
        <button type='button' class='signup'> VIEW LIKED POSTS </button>
    </a>
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