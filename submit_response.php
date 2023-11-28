<?php
// Start the session to access user information
require_once 'config.php';
session_start();

// Redirect to the login page if the user is not logged in or not an admin/writer
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION["security_level"] != 0 && $_SESSION["security_level"] != 1)) {
    header("Location: homepage.php");
    exit;
}

$mysql = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check for database connection error
if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

// Function to get user_id from user_name
function getUserId($mysql, $userName) {
    $stmt = $mysql->prepare("SELECT user_id FROM users WHERE user_name = ?");
    $stmt->bind_param("s", $userName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row['user_id'];
    } else {
        return null; // User not found
    }
}

// Check if the form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user_id from the session's user_name
    $userId = getUserId($mysql, $_SESSION['user_name']);

    // Check if user_id is found
    if ($userId === null) {
        die("User not found.");
    }

    // Get the other data from the POST request
    $submissionId = $_POST['submission_id'];
    $responseText = $_POST['response_text'];
    $responseTitle = $_POST['response_title']; // If you have a title column
    $imageUrl = $_POST['imageurl'];
    $responseDate = date('Y-m-d H:i:s'); // Current date and time

    // Start a transaction
    $mysql->begin_transaction();

    // Prepare SQL statement to insert response
    $stmt = $mysql->prepare("INSERT INTO responses (submission_id, user_id, response_text, response_date, imageurl) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $submissionId, $userId, $responseText, $responseDate, $imageUrl);

    // Execute the prepared statement
    if ($stmt->execute()) {
        // Update the 'responded' column in the submissions table
        $updateStmt = $mysql->prepare("UPDATE submissions SET responded = 1 WHERE submission_id = ?");
        $updateStmt->bind_param("i", $submissionId);

        if ($updateStmt->execute()) {
            // Commit the transaction if both queries are successful
            $mysql->commit();
            // Redirect to adminbackend.php after successful submission and update
            header("Location: adminbackend.php");
            exit;
        } else {
            // Rollback the transaction in case of error in the second query
            $mysql->rollback();
            echo "Error updating submission: " . $updateStmt->error;
        }

        $updateStmt->close();
    } else {
        echo "Error: " . $stmt->error;
        // Rollback the transaction in case of error in the first query
        $mysql->rollback();
    }

    // Close statement and connection
    $stmt->close();
}

$mysql->close();
?>
