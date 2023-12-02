<?php
    require_once 'config.php'; // Edit with your path to config.php file

    $mysql = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($mysql->connect_error) {
        die("Connection failed: " . $mysql->connect_error);
    }

    // Start session in order to access current user info
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inkwell Search</title>
    <link rel="stylesheet" href="Stylesheets/style_main.css">
    <link rel="stylesheet" href="Stylesheets/navbar.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="Stylesheets/searchpage.css">
    <title>Inkwell Search</title>

    <style>
    </style>
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

<!-- Navbar -->
<?php include 'navbar.php';?>

<form action="resultpage.php" method="GET">
    <div class="flexcontainer">
        <div class="title">
            Search for Posts
        </div>
        <br><br>

        <div class="searchbar-shell">
            <div class="searchbar">
                <span class="search-icon material-symbols-outlined">search</span> <!-- This is the search icon "magnifying glass" -->
                <input class="search-input" type="search" name="keyword" placeholder="Search">
            </div>
        </div>

        <div class="searchbar-shell">
            <p class="header"><strong>Category</strong><br></p>
            <div class="searchbar">
                <select name="category" class="search-input">
                    <option value="ALL">ALL</option>
                    <?php

                    $sql = "SELECT DISTINCT category_name FROM inkwell_view";
                    $result = $mysql->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $categoryName = $row['category_name'];
                            echo "<option value='$categoryName'>$categoryName</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </div>

        <div class="searchbar-shell">
            <p class="header"><strong>Sort By</strong><br></p>
            <div class="searchbar">
                <select name="sortby" class="search-input">
                    <option value="latest">Latest Upload</option>
                    <option value="earliest">Earliest Upload</option>
                </select>
            </div>
        </div>
        <br>

        <input type="submit" class="login" value="SUBMIT">
    </div>

</form>

</body>
</html>