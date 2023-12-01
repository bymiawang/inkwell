<?php
// Start session to access current user info
require_once 'config.php';
session_start();

// Check if the user is logged in and has the right security level (admin or writer)
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || ($_SESSION["security_level"] != 0 && $_SESSION["security_level"] != 1)) {
    header("Location: homepage.php"); // Redirect to homepage if not
    exit;
}

// Connect to the database
$mysql = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

// Check if a submission ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $submission_id = $mysql->real_escape_string($_GET['id']);

    // Prepare the SQL statement to delete the submission
    $sql = "DELETE FROM submissions WHERE submission_id = ?";

    // Prepare and execute the query
    if ($stmt = $mysql->prepare($sql)) {
        $stmt->bind_param("i", $submission_id);
        if ($stmt->execute()) {
            // Redirect to a confirmation page or back to the admin page
            header("Location: adminbackend.php?deletion=success");
        } else {
            // Handle error in execution
            echo "Error executing query: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Handle error in statement preparation
        echo "Error preparing query: " . $mysql->error;
    }
} else {
    // Handle the case where no submission ID is provided
    echo "No submission ID provided.";
}

$mysql->close();
?>
