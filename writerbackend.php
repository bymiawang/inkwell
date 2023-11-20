<?php
require_once 'config.php'; // Include your database configuration file
session_start();

// Check if user is logged in and is a writer or admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION["security_level"] != 0 && $_SESSION["security_level"] != 1)) {
    header("Location: loginpage.php"); // Redirect to login page
    exit;
}

$mysql = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

$sql = "SELECT * FROM submissions WHERE response_id IS NULL"; // Adjust the table and column names as per your schema
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
    <link rel="stylesheet" href="Stylesheets/result.css">
    <link rel="stylesheet" href="Stylesheets/writer.css">
    <title>Inkwell Writer </title>
</head>
<body>
<div class = navbar>
    <div id = logosearch>
        <a href="homepage.php" style="text-decoration: none; color: inherit;">
            <div id="inkwell"><em>Inkwell</em></div>
        </a>
        <a href="searchpage.php"><button type="button" class = searchbutton>
                Search
            </button></a>
    </div>
    <div class="profile">
        <div>pfp</div>
        <div>Username</div>
        <div>Role</div>

    </div>

</div>

<div class="resultcontainer">
    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="writercard">
                <div class="resultcard">
                    <div class="thumbnail">
                        <!-- Check if image URL exists, else show a default image -->
                        <img src="<?= htmlspecialchars($row['imageurl']) ?: 'Images/thumbnaildemo.png' ?>">
                    </div>
                    <div class="cardcontent">
                        <div class="carddate"><?= htmlspecialchars($row['submission_date']) ?></div>
                        <div class="card-title">
                            <?= htmlspecialchars($row['submission_title']) ?>
                        </div>
                        <div class="cardtext">
                            <!-- Truncate the text to a certain length and append '...' if longer -->
                            <?= nl2br(htmlspecialchars(substr($row['submission_text'], 0, 400) . (strlen($row['submission_text']) > 400 ? "..." : ""))) ?>
                        </div>
                        <div class="cardcategory">
                            <p class="caption"><?= htmlspecialchars($row['category_name']) ?></p>
                        </div>
                    </div>
                </div>
                <!-- Write a response button -->
                <div class="writeresponse">
                    <a href="writerresponse.php?id=<?= urlencode($row['submission_id']) ?>">
                        <button type="button" class="write">WRITE A RESPONSE</button>
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
<?php $mysql->close(); ?>
