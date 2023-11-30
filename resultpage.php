<?php

require_once 'config.php'; // Edit with your path to config.php file

$mysql = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}

// Retrieve the GET parameters
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : 'ALL';
$sortOrder = isset($_GET['sortby']) ? $_GET['sortby'] : 'latest';

// Determine sort direction based on user selection
$sortDirection = $sortOrder === 'latest' ? 'DESC' : 'ASC';

// Create base SQL query
$baseSql = "SELECT * FROM inkwell_view WHERE 1=1";

// Filter by keyword and category if set
if (!empty($keyword)) {
    $baseSql .= " AND submission_title LIKE '%" . $mysql->real_escape_string($keyword) . "%'";
}

if ($category !== 'ALL') {
    $baseSql .= " AND category_name = '" . $mysql->real_escape_string($category) . "'";
}

// Pagination settings
$resultsPerPage = 5; // number of results per page
$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$startFrom = ($currentPage - 1) * $resultsPerPage;

// Find out the number of results stored in the database
$totalSql = "SELECT COUNT(*) as total FROM ($baseSql) AS sub";
$totalResult = $mysql->query($totalSql);
if (!$totalResult) {
    die("Error calculating total results: " . $mysql->error);
}
$row = $totalResult->fetch_assoc();
$totalResults = $row['total'];
$totalPages = ceil($totalResults / $resultsPerPage);

// Add sorting and pagination to the base SQL query
$sql = $baseSql . " ORDER BY response_date " . $sortDirection;
$sql .= " LIMIT $startFrom, $resultsPerPage";


// Execute the query with limit for pagination
$result = $mysql->query($sql);

// Check for errors in execution
if (!$result) {
    die("Error retrieving results: " . $mysql->error);
}

// Start session in order to access current user info
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
    <link rel="stylesheet" href="Stylesheets/result.css">
    <title>Inkwell Result </title>

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
                    <div><a href='editprofile.php'>[PFP]</a></div>
                    <div>". $_SESSION['user_name'] . "</div>
                    <div><a href='adminbackend.php'>Admin</a></div>
                  </div>";
        }
        // If user is an writer, display their profile and backend button
        else if($_SESSION["security_level"] == 1){
            echo "<div class='profile_nav'>
                        <div>[PFP]</div>
                        <div>". $_SESSION['user_name'] ."</div>
                        <div>Writer</div>
                      </div>";
        }
        // If user is regular user, just display their profile
        else if($_SESSION["security_level"] == 2) {
            echo "<div class='profile_nav'>
                        <div>[PFP]</div>
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
<div class="resultcontainer">
    <div class="resultsheader">
        <p class="card-title">You searched for:
            <?= !empty($keyword) ? htmlspecialchars($keyword) : 'Search' ?>
        </p>
        <a href="searchpage.php">
            <button type="button" class = searchagain>
                SEARCH AGAIN
            </button>
        </a>
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
    <!-- Pagination Links -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php
            // Display link to the previous page if not on the first page
            if ($currentPage > 1): ?>
                <a href="?page=<?= $currentPage - 1 ?>&keyword=<?= urlencode($keyword) ?>&category=<?= urlencode($category) ?>&sortby=<?= urlencode($sortby) ?>">Previous</a>
            <?php endif; ?>

            <?php
            // Display links for individual pages
            for ($page = 1; $page <= $totalPages; $page++): ?>
                <a href="?page=<?= $page ?>&keyword=<?= urlencode($keyword) ?>&category=<?= urlencode($category) ?>&sortby=<?= urlencode($sortby) ?>" <?= $page == $currentPage ? 'class="active"' : '' ?>>
                    <?= $page ?>
                </a>
            <?php endfor; ?>

            <?php
            // Display link to the next page if not on the last page
            if ($currentPage < $totalPages): ?>
                <a href="?page=<?= $currentPage + 1 ?>&keyword=<?= urlencode($keyword) ?>&category=<?= urlencode($category) ?>&sortby=<?= urlencode($sortby) ?>">Next</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>

