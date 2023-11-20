<?php
require_once 'config.php';
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

$submissionId = isset($_GET['id']) ? $_GET['id'] : null;

// Fetch the submission details if needed for display
// Assuming you want to display the submission details on the response page
$submission_sql = "SELECT * FROM submissions WHERE submission_id = ?";
$stmt = $mysql->prepare($submission_sql);
$stmt->bind_param("i", $submissionId);
$stmt->execute();
$submission_result = $stmt->get_result();
$submission = $submission_result->fetch_assoc();
$stmt->close();
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
                Search
            </button></a>
    </div>
    <div>
        <a href="signup.html"><button type="button" class = signup>
                SIGN UP
            </button></a>
        <a href="login.html"><button type="button" class = login>
                LOG IN
            </button></a>
    </div>
</div>

<div class="container">
    <?php if ($submission): ?>
        <div class="submissionblock">
            <div class="cardcontent">
                <div class="carddate"><?= htmlspecialchars($submission['submission_date']) ?></div>
                <div class="card-title">
                    <?= htmlspecialchars($submission['submission_title']) ?>
                </div>
                <div class="cardtext">
                    <?= nl2br(htmlspecialchars($submission['submission_text'])) ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Response submission form -->
    <div class="writerblock">
        <form action="submit_response.php" method="post">
            <input type="hidden" name="submission_id" value="<?= htmlspecialchars($submissionId) ?>">
            <input type="text" name="title" placeholder="Title">
            <textarea name="response">Tell us something...</textarea>
            <!-- Add more fields as needed -->
            <input type="submit" value="Submit Response">
        </form>
    </div>
</div>
</body>
</html>
<?php $mysql->close(); ?>
