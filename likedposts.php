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

    $sql = "SELECT * FROM inkwell_view, liked_posts WHERE inkwell_view.submission_id = liked_posts.submission_id AND liked_posts.user_id=" . $_SESSION['user_id'];
    //SELECT * FROM inkwell_view, liked_posts WHERE inkwell_view.submission_id = liked_posts.submission_id AND liked_posts.user_id=1;

    // Execute the query with limit for pagination
    $result = $mysql->query($sql);

    // Check for errors in execution
    if (!$result) {
        die("Error retrieving results: " . $mysql->error);
    }
?>

<html>

    <head>
        <meta charset="UTF-8">
        <title>Inkwell Edit Profile</title>
        <link rel="stylesheet" href="Stylesheets/style_main.css">
        <link rel="stylesheet" href="Stylesheets/navbar.css">
        <link rel="stylesheet" href="Stylesheets/editprofile.css">
        <link rel="stylesheet" href="Stylesheets/result.css">
        <link rel="stylesheet" href="Stylesheets/cards.css">
        <style>
        </style>
        <title> Liked Posts </title>
    </head>

    <body>

    <!-- Navbar -->
    <?php include 'navbar.php';?>


    <div class="flexcontainer">
        <div class="title">
            liked posts
        </div>
        <div class="resultcontainer">
            <div class="resultcards">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <!--                --><?php //// Debugging line to see raw data
//                echo '<pre>' . print_r($row, true) . '</pre>'; ?>
                    <a href="detailpage.php?id=<?= urlencode($row['response_id']) ?>" class="card-anchor">
                        <div class="resultcard">
                            <div class="thumbnail">
                                <img src="<?= htmlspecialchars($row['imageurl']) ?: 'Images/thumbnaildemo.png' ?>">
                            </div>
                            <div class="cardcontent">
                                <div class="carddate"><?= htmlspecialchars($row['formatted_response_date']) ?></div>
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
                    </a>
                    <hr />
                <?php endwhile; ?>
            <?php else: ?>
                <p>No results found.</p>
            <?php endif; ?>
        </div>
        </div>

    </div>


    </body>

</html>
