<?php

require_once 'config.php'; // Edit with your path to config.php file

// Get the response_id from the query parameter
$response_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$mysql = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

// SQL to get the full submission text
$sql = "SELECT submission_text FROM inkwell_view WHERE response_id = ?";
$stmt = $mysql->prepare($sql);
$stmt->bind_param('i', $response_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $submission_text = $row['submission_text'];
} else {
    $submission_text = 'No text found for this submission.';
}

$stmt->close();
$mysql->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submission Details</title>
    <!-- Include your stylesheets here -->
</head>
<body>
<h1>Submission Text</h1>
<p><?= nl2br(htmlspecialchars($submission_text)) ?></p>
<!-- Add navigation or other content here -->
</body>
</html>
