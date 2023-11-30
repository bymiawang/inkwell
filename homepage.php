<?php

require_once 'config.php'; // Path to your config.php file

// Database connection
$mysql = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

// Query to get categories
$category_sql = "SELECT DISTINCT category_name FROM inkwell_view WHERE response_date IS NOT NULL";
$category_result = $mysql->query($category_sql);
$category_result2 = $mysql->query($category_sql);


// Error logging
if (!$category_result) {
    die("Error retrieving categories: " . $mysql->error);
}

// Check if a category is selected
if (isset($_GET['category'])) {
    $selectedCategory = $_REQUEST['category'];
    // SQL query to filter by the selected category, maximum 5 results for the given category
    $sql = "SELECT * FROM inkwell_view WHERE category_name = '" . $selectedCategory . "' AND response_date IS NOT NULL ORDER BY response_date DESC LIMIT 5";
    //$stmt = $mysql->prepare($sql);
    //$stmt->bind_param("s", $selectedCategory);
    //$stmt->execute();
    $result = $mysql->query($sql);
} else {
    // Original query if no category is selected
    $sql = "SELECT * FROM inkwell_view ORDER BY response_date DESC LIMIT 5";
    $result = $mysql->query($sql);
}

if (!$result) {
    die("Error retrieving results: " . $mysql->error);
}

session_start();
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
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="Scripts/home.js"></script>
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

<body onload="showCurrentDate()">
<div class="navbar">
    <!-- Navbar content -->
    <div id="logosearch">
        <a href="homepage.php" style="text-decoration: none; color: inherit;">
            <div id="inkwell"><em>Inkwell</em></div>
        </a>
        <a href="searchpage.php"><button type="button" class="searchbutton">
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

        echo "<a href='logout.php'><button type='button' class='signup'>
                                LOGOUT
                            </button></a>";
    }
    else {
        echo "<div class='login_buttons'>
                        <a href='signuppage.php'><button type='button' class='signup'>
                                SIGN UP
                            </button></a>
                        <a href='loginpage.php'><button type='button' class='login'>
                                LOG IN
                            </button></a>
                    </div>";
    }
    ?>
</div>

<?php //echo $_REQUEST['category']  ?>

<div class="container">
    <div class="main">
        <div class="content">
            <div class="masthead">
                <div class="mastheadtext">
                    <div id="currentDate"></div>
                    <div class="title-thin">
                        feeling thirsty?<br/>
                        here’s some <em>tea.</em>
                    </div>
                </div>
            </div>
            <div class="cards">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <a href="detailpage.php?id=<?= urlencode($row['response_id']) ?>" class="card-anchor">
                            <div class="card">
                                <div class="thumbnail">
                                    <img src="<?= htmlspecialchars($row['imageurl']) ?>" alt="Thumbnail">
                                </div>
                                <div class="cardcontent">
                                    <div class="carddate"><?= htmlspecialchars($row['formatted_response_date']) ?></div>
                                    <div class="card-title">
                                        <?= htmlspecialchars($row['submission_title']) ?>
                                    </div>
                                    <div class="cardtext">
                                        <?= nl2br(htmlspecialchars(substr($row['submission_text'], 0, 200))) ?>
                                        <?= strlen($row['submission_text']) > 200 ? "..." : "" ?>
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
                    <p>No entries found.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="sidebar">
            <div class="submission">
                <div class="subtitle">Need a word of advice?</div>
                <form class="submission" action="submit_submission.php" method="post">
                    <div style="display: flex; flex-direction: column;">
                        <input type="text" name="submission_title" class="submissiontitle" placeholder="Title">
                        <textarea name="submission_text" class="submissionbody" placeholder="Tell us something..."></textarea>
                        <select name="category_name" class="submissioncategory">
                            <?php if ($category_result->num_rows > 0): ?>
                                <?php while($category = $category_result->fetch_assoc()): ?>
                                    <option value="<?= htmlspecialchars($category['category_name']) ?>">
                                        <?= htmlspecialchars($category['category_name']) ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <option>No categories found</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <input type="submit" value="Submit" class="submitbutton">
                    </div>
                </form>
            </div>
            <div class="selectcatergories">
                <div class="subtitle">Or, pick a flavor...</div>
                <div class="categoriesparent">
                    <?php
                        if ($category_result->num_rows > 0){
                            while($category = $category_result2->fetch_assoc()){
                                echo "<a href='homepage.php?category=". $category['category_name'] . "'><div class='cardcategory'><p class='caption'>" . $category['category_name'] . "</p></div></a>";
                            }
                        }
                        else{
                            echo "<p>No categories found.</p>";
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="newsletter">
    <div class="title-thin" style="color: var(--offwhite)">
        can’t get enough? <br/>
        sign up for our newsletter.
    </div>
    <div>
        <form class="newslettersubmission">
            <div class="newsletterform">
                <input type="text" class="submitnewsletter" placeholder="What should we call you?">
                <input type="text" class="submitnewsletter" placeholder="youremail@example.com">
            </div>
            <div>
                <input type="submit" value="Submit" class="submitbutton">
            </div>
        </form>
    </div>
</div>
</body>
</html>
