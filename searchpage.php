<?php
require_once 'config.php'; // Edit with your path to config.php file

$mysql = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($mysql->connect_error) {
    die("Connection failed: " . $mysql->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inkwell Search</title>
    <link rel="stylesheet" href="Stylesheets/style_main.css">
    <link rel="stylesheet" href="Stylesheets/navbar.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    <style>

        .flexcontainer {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: auto;
            margin-top: 16%;
            /* margin: auto;
            margin-top: 15%; */
        }

        /*
        .searchbar-shell{
            border: solid;
            border-color: red;
            margin-top: 1%;
        }
         */

        .searchbar {
            --padding: 14px;
            width: max-content;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--padding);
            border-radius: 28px;
            border: solid;
            border-color: var(--offwhiteshadow);
            background: white;
            width: 554px;
        }

        .search-input {
            font-size: 16px;
            margin-left: var(--padding);
            color: #333333;
            outline: none;
            border: none;
            background: transparent;
            flex: 1;
        }

        .search-input::placeholder {
            color: var(--brown);
        }

        .search-icon {
            color: var(--midtonebrown);
        }

        .header {
            font-family: Helvetica;
            font-size: 18px;
        }

    </style>


</head>
<body>
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