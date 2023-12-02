<?php

require_once 'config.php'; // Edit with your path to config.php file

// Get the response_id from the query parameter
$response_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$currenturl = 'detailpage.php?id=' . $response_id;

$mysql = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

// Start session in order to access current user info
session_start();

// SQL to get the full submission text

// VIEW (detail_view) CODE
// SELECT submissions.submission_id, submissions.submission_title, submissions.submission_text, submissions.submission_date, submissions.responded, responses.response_text, responses.response_date, responses.user_id, responses.response_id FROM submissions, responses WHERE submissions.responded = 1 AND responses.submission_id = submissions.submission_id;

$sql = "SELECT * FROM responded_view WHERE response_id = ?";
$stmt = $mysql->prepare($sql);
$stmt->bind_param('i', $response_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $submission_id = $row['submission_id'];
    $submission_text = $row['submission_text'];
    $submission_date = $row['submission_date'];
    $submission_title = $row['submission_title'];
    $response_text = $row['response_text'];
    $response_date = $row['response_date'];
} else {
    $submission_id = '';
    $submission_title = 'No submission title.';
    $submission_date = '';
    $response_date = '';
    $response_text = '';
    $submission_text = 'No text found for this submission.';
}

$stmt->close(); // change later

?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="Stylesheets/style_main.css">
    <link rel="stylesheet" href="Stylesheets/navbar.css">
    <link rel="stylesheet" href="Stylesheets/homepage.css">
    <link rel="stylesheet" href="Stylesheets/cards.css">
    <link rel="stylesheet" href="Stylesheets/detail1.css">
    <link rel="stylesheet" href="Stylesheets/writer.css">
    <title>Inkwell Write Response </title>

    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="Scripts/home.js"></script>

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
                    <div class='role'>(Admin)</div>
                        <div><a href='adminbackend.php'><img src='Images/Vector.svg' class='edit'></a></div>
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

<div class="container">

    <a href="resultpage.php">< Back to Search</a>

    <!-- EMAIL FUNCTIONALITY -->
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // EMAIL FUNCTIONALITY
        if (isset($_POST['submit'])){
            if (!empty($_POST['email'])) {
                $email = $_POST['email'];
                $submission= $submission_text;
                $response = $response_text;

                $message = "Submission Text:\n$submission\n\nResponse Text:\n$response";
                $subject = "Details from Inkwell";
                $headers = "From: your_email@example.com";

                if (mail($email, $subject, $message, $headers)) {
                    echo "Email sent successfully!";
                } else {
                    echo "Failed to send email. Please try again.";
                }
            } else {
                echo "Email field is empty.";
            }
        }

    }
    ?>


    <div class="banner-container">
        <img src="Images/thumbnaildemo.png" alt="Banner Image" class="banner-image">
    </div>
    <div class="date"><?php echo($submission_date); ?></div>

    <!-- LIKING FUNCTIONALITY -->
    <?php
    // Check if user has already liked this post or not
    $likedalready_sql = "SELECT COUNT(*) from liked_posts WHERE user_id=" . $_SESSION['user_id'] . " AND submission_id=" . $submission_id;
    $likedlaready = $mysql->query($likedalready_sql);
    $likedlareadydata = $likedlaready->fetch_assoc();

    // If they've already liked the post, just print out a filled heart
    // Implement unliking functionality in the future
    if ($likedlareadydata['COUNT(*)'] > 0){
        $image = 'Images/heart.png';
        echo "<img src='" . $image . "'>";
    }
    // If they haven't already liked the post, print out a unfilled heart and let them like the post
    else {
        $image = 'Images/heart-outline.png';

        // Only print out the like button if the user is logged in
        if (isset($_SESSION['logged_in'])){
            // If the like button has been pressed (post & liked variable is set), then add that post
            // to the users liked posts in the database.
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['liked'])) {
                // Only update the database if they haven't already liked this post
                 if (!$likedlareadydata['COUNT(*)'] > 0){
                    $updateSql = "INSERT INTO `liked_posts` (`like_id`, `user_id`, `submission_id`) VALUES (NULL, '" . $_SESSION['user_id'] . "', '" . $submission_id .  "');";
                    $results = $mysql->query($updateSql);
                    if (!$results) {
                        echo "Error liking post: " . $stmt->error . "<br />";
                    }

                }
            }

            echo "<div><form method='post' action='" . $currenturl . "&liked=1'>"
                . "<input class='likebutton' type='image' src='"
                . $image
                . "' alt='Submit'>"
                . "</form></div>";
        }
        // If not logged in
        else {
            echo "(Sign in to like a post!)";
        }
    }
    ?>


    <!-- LIKE COUNT -->
    <?php
        // Get likecounts for posts with likes
        $countSql = "SELECT COUNT(*) as likecount from liked_posts WHERE submission_id=" . $submission_id;
        $likesresult = $mysql->query($countSql);
        if ($likesresult->num_rows > 0) {
            $row = $likesresult->fetch_assoc();
            echo $row['likecount'] . " likes";
        }
    ?>

    <div class="subtitle" ><?php echo($submission_title); ?></div>
    <div class="subtext">
        <?= nl2br(htmlspecialchars($submission_text)) ?></div>
    <br><hr><br>
    <div class="subtitle" >Writer Response</div>
    <div class="date"><?php echo($response_date); ?></div>
    <div class="subtext">
        <?php echo($response_text); ?>
    </div>

    <br><hr><br/>

    <form method="post" action="<?php echo $currenturl ?>">
        <label for="email" class="subtitle">Send this column to your email here! </label>
        <br>
        <input type="email" id="email" name="email" required>
        <input type="submit" name="submit" value="Send" class="submitbutton">
    </form>
</div>


</body>
</html>
