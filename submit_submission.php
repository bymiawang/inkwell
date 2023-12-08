<?php

session_start();

require_once 'config.php'; // Edit with your path to config.php file
$mysql = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check for connection errors
if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

// Check if the form was submitted and the user is logged in
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["user_id"])) {
    // Collect and sanitize input data
    $user_id = $_SESSION["user_id"];
    $submission_title = mysqli_real_escape_string($mysql, $_POST['submission_title']);
    $submission_text = mysqli_real_escape_string($mysql, $_POST['submission_text']);
    $category_id = mysqli_real_escape_string($mysql, $_POST['category_name']);
    $responded = 0; // Assuming 'responded' is a status flag and is set to 0 initially

    // Insert data into the database
    $stmt = $mysql->prepare("INSERT INTO submissions (user_id, submission_title, submission_text, submission_date, category_id, responded) VALUES (?, ?, ?, NOW(), ?, ?)");
    $stmt->bind_param("issii", $user_id, $submission_title, $submission_text, $category_id, $responded);

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo "New submission created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "You must be logged in to submit.";
}

// Query to get categories
$category_sql = "SELECT DISTINCT category_name FROM inkwell_view WHERE response_date IS NOT NULL";
$category_result = $mysql->query($category_sql);

// Error logging
if (!$category_result) {
    die("Error retrieving categories: " . $mysql->error);
}

// Check if a category is selected
if (isset($_GET['category'])) {
    $selectedCategory = $_GET['category'];
    // SQL query to filter by the selected category
    $sql = "SELECT * FROM inkwell_view WHERE category_name = ? AND response_date IS NOT NULL ORDER BY response_date DESC LIMIT 4";
    $stmt = $mysql->prepare($sql);
    $stmt->bind_param("s", $selectedCategory);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Original query if no category is selected
    $sql = "SELECT * FROM inkwell_view ORDER BY response_date DESC LIMIT 4";
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
</head>

<body onload="showCurrentDate()">
<!-- Navbar -->
<?php include 'navbar.php';?>

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
                    <?php if ($category_result->num_rows > 0): ?>
                        <?php while($category = $category_result->fetch_assoc()): ?>
                            <!-- Check if categories are displayed here -->
                            <div class="cardcategory">
                                <p class="caption"><?= htmlspecialchars($category['category_name']) ?></p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <!-- Check if this message shows up -->
                        <p>No categories found.</p>
                    <?php endif; ?>
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
