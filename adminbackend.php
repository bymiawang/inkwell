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

<!-- Navbar -->
<?php include 'navbar.php';?>


<div class="resultcontainer">
    <a href="googleanalytics.php"><div class="subtitle" style="text-decoration: underline">Page to Google Analytics</div></a>
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="writercard">
                <div class="resultcard">
                    <div class="thumbnail">
                        <img src="<?= htmlspecialchars($row['imageurl']) ?: 'Images/thumbnaildemo.png' ?>">
                    </div>
                    <div class="cardcontent">
                        <div class="carddate"><?= htmlspecialchars($row['submission_date']) ?></div>
                        <div class="card-title">
                            <?= htmlspecialchars($row['submission_title']) ?>
                        </div>
                        <div class="cardtext">
                            <?= nl2br(htmlspecialchars(substr($row['submission_text'], 0, 400) . (strlen($row['submission_text']) > 400 ? "..." : ""))) ?>
                        </div>
                        <div class="cardcategory">
                            <p class="caption"><?= htmlspecialchars($row['category_name']) ?></p>
                        </div>
                    </div>
                </div>
                <!-- Write a response button -->
                <div class="response-button-container">
                    <a href="writerresponse.php?id=<?= urlencode($row['submission_id']) ?>">
                        <button type="button" class="write">WRITE A RESPONSE</button>
                    </a>
                    <a href="deletesubmission.php?id=<?= urlencode($row['submission_id']) ?>">
                        <button type="button" class="delete">DELETE SUBMISSION</button>
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No submissions found.</p>
    <?php endif; ?>
</div>

</body>
</html>

