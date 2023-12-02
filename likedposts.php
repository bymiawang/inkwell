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
    </head>

    <body>

    <!-- Navbar -->
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
                    <div><a href='adminbackend.php'>Admin</a></div>
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

    <div class="flexcontainer">
        <div class="title">
            liked posts
        </div>

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


    </body>

</html>
