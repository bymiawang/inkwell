<?php
// Start session in order to access current user info
require_once 'config.php';
session_start();

// Check for admin submitted, otherwise auto direct to homepage page.
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION["security_level"] != 0 && $_SESSION["security_level"] != 1)) {
    header("Location: homepage.php"); // Redirect to login page
    exit;
}

$mysql = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

$sql = "SELECT * FROM view_unresponded_submissions;";

$result = $mysql->query($sql);

if (!$result) {
    die("Error retrieving submissions: " . $mysql->error);
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
    <title>Inkwell Home</title>

    <script>
        function showCurrentDate() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('currentDate').innerText = now.toLocaleDateString('en-US', options);
        }
    </script>
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
<?php include 'navbar.php';?>
<div class="googlecontainer">
    <div class="subtitle">
        Screenshots of Google Analytics
    </div>
    <div>
        <img src="Images/google1.jpeg" class="google">
        <img src="Images/google2.jpeg" class="google">
        <img src="Images/google3.jpeg" class="google">
    </div>


</div>

</body>
</html>