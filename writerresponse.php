<?php
require_once 'config.php';
session_start();

// Get the submission ID from the URL
$submissionId = isset($_GET['id']) ? $_GET['id'] : null;

// Redirect back if no submission ID is provided
if (!$submissionId) {
    header("Location: adminbackend.php");
    exit;
}

// Check for admin submitted, otherwise auto direct to homepage page.
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION["security_level"] != 0 && $_SESSION["security_level"] != 1)) {
    header("Location: homepage.php"); // Redirect to login page
    exit;
}

$mysql = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

$sql = "SELECT * FROM view_unresponded_submissions WHERE submission_id = ?;";

$stmt = $mysql->prepare($sql);
$stmt->bind_param("i", $submissionId);
$stmt->execute();
$result = $stmt->get_result();
$submissionData = $result->fetch_assoc();


if (!$submissionData) {
    die("Error retrieving submission: " . $mysql->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="Stylesheets/style_main.css">
    <link rel="stylesheet" href="Stylesheets/navbar.css">
    <link rel="stylesheet" href="Stylesheets/homepage.css">
    <link rel="stylesheet" href="Stylesheets/cards.css">
    <link rel="stylesheet" href="Stylesheets/result.css">
    <link rel="stylesheet" href="Stylesheets/writer.css">
    <title>Inkwell Write Response </title>
</head>
<body>
<div class = navbar>
    <div id = logosearch>
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
                    <div class='pfp'></div>
                    <div>". $_SESSION['user_name'] . "</div>
                    <div><a href='adminbackend.php'>Admin</a></div>
                  </div>";
        }
        // If user is an writer, display their profile and backend button
        else if($_SESSION["security_level"] == 1){
            echo "<div class='profile_nav'>
                        <div class='pfp'></div>
                        <div>". $_SESSION['user_name'] ."</div>
                        <div>Writer</div>
                      </div>";
        }
        // If user is regular user, just display their profile
        else if($_SESSION["security_level"] == 2) {
            echo "<div class='profile_nav'>
                        <div class='pfp'></div>
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
<div class = container>
    <div style="padding: 20px 0px">
        <a href="adminbackend.php" style="text-decoration: none; color: inherit;">&lt; Back to all submissions</a>
    </div>
    <div class="submissionblock">
        <div class="cardcontent">
            <div class="carddate"><?= htmlspecialchars($submissionData['submission_date']) ?></div>
            <div class="card-title">
                <?= htmlspecialchars($submissionData['submission_title']) ?>
            </div>
            <div class="cardtext">
                <?= nl2br(htmlspecialchars($submissionData['submission_text'])) ?>
            </div>
        </div>
    </div>
    <div class="writerblock">
        <div>
            <form class="response" action="submit_response.php" method="post">
                <div style="display: flex; flex-direction: column;">
                    <input type="text" class="responsetitle" name="response_title" placeholder="Title">
                    <textarea class="responsebody" name="response_text">Tell us something...</textarea>
                    <input type="text" class="imageurl" name="imageurl" placeholder="Image URL">
                </div>
                <div>
                    <input type="hidden" name="submission_id" value="<?= htmlspecialchars($submissionId) ?>">
                    <input type="submit" value="Submit Response" class="submitbutton">
                </div>
            </form>
        </div>

    </div>
</div>

</body>
</html>