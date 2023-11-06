<?php
require_once 'config.php'; // Edit with your path to config.php file

$mysql = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

$category_sql = "SELECT DISTINCT category_name FROM inkwell_view WHERE response_date IS NOT NULL";
$category_result = $mysql->query($category_sql);

// Check for errors in category query execution
if (!$category_result) {
    die("Error retrieving categories: " . $mysql->error);
}

$sql = "SELECT * FROM inkwell_view ORDER BY response_date DESC LIMIT 4";
$result = $mysql->query($sql);

// Check for errors in execution
if (!$result) {
    die("Error retrieving results: " . $mysql->error);
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
    <title>Inkwell Home</title>
    <script>
        function showCurrentDate() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('currentDate').innerText = now.toLocaleDateString('en-US', options);
        }
    </script>
</head>
<body onload="showCurrentDate()">
<div class = navbar>
    <div id = logosearch>
        <div id = inkwell><em>Inkwell</em></div>
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
<div class = container>
    <div class = main>
        <div class = content>
            <div class = masthead>
                <div class = mastheadtext>
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
            <div class = "submission">
                <div class=subtitle>Need a word of advice?</div>
                <div>
                    <form class = "submission">
                        <div style="display: flex; flex-direction: column;">
                            <input type="text" class="submissiontitle" placeholder="Title">
                            <input type="text" class="submissionbody" placeholder="Tell us something...">
                        </div>
                        <div>
                            <input type="submit" value="Submit" class = "submitbutton">
                        </div>
                    </form>
                </div>
            </div>
            <div class="selectcatergories">
                <div class=subtitle>Or, pick a flavor...</div>
                <div class = "categoriesparent">
                    <?php if ($category_result->num_rows > 0): ?>
                        <?php while($category = $category_result->fetch_assoc()): ?>
                            <div class="cardcategory">
                                <p class="caption"><?= htmlspecialchars($category['category_name']) ?></p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p>No categories found.</p>
                    <?php endif; ?>
                    <p class="caption"> + MORE </p>
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
            <form class = "newslettersubmission">
                <div class = "newsletterform"   >
                    <input type="text" class="submitnewsletter" placeholder="What should we call you?">
                    <input type="text" class="submitnewsletter" placeholder="youremail@example.com">
                </div>
                <div>
                    <input type="submit" value="Submit" class = "submitbutton">
                </div>
            </form>
        </div>

    </div>




</body>
</html>