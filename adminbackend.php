<?php
    // Start session in order to access current user info
    session_start();

    // Check for admin submitted, otherwise auto direct to homepage page.
    if(isset($_SESSION['security_level']) && $_SESSION['security_level'] != 0){
        header("Location: homepage.php");
    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="Stylesheets/style_main.css">
    <link rel="stylesheet" href="Stylesheets/navbar.css">
    <link rel="stylesheet" href="Stylesheets/homepage.css">
    <link rel="stylesheet" href="Stylesheets/cards.css">
    <link rel="stylesheet" href="Stylesheets/result.css">
    <link rel="stylesheet" href="Stylesheets/writer.css">
    <title>Inkwell Admin </title>
</head>
<body>
<div class="navbar" id="navbar_admin">
    <div id = "logosearch">
        <a href="homepage.php" style="text-decoration: none; color: inherit;">
            <div id="inkwell"><em>Inkwell</em></div>
        </a>
        <a href="searchpage.php"><button type="button" class = searchbutton>
            Search
        </button></a>
    </div>
    <div class="profile_nav">
        <div>pfp</div>
        <div>Username</div>
        <div>Role</div>
    </div>
</div>

<div class="resultcontainer">
    <div class="subtitle" style="margin-top:20px">
        Submissions without Responses
    </div>
    <div class="resultcards">
        <div class="writercard">
            <a href="detailpagebase.php?id=<?= urlencode($row['response_id']) ?>" class="card-anchor">
                <div class="resultcard">
                    <div class="thumbnail">
                        <img src="Images/thumbnaildemo.png">
                    </div>
                    <div class="cardcontent">
                        <div class="carddate">November 16, 2023</div>
                        <div class="card-title">
                            Is it a red flag if?
                        </div>
                        <div class="cardtext">
                            Dear writers, the other day I met this guy at a dorm party.
                            I’d heard of him before (friend of a friend or something)
                            but wasn’t particularly interested or anything like that.
                            We ended up talking for a fdafdfdsafdsafdsafdspretty long time,
                            and he was definitely flirting. I was still trying to decide...
                        </div>
                    </div>
                </div>
            </a>
            <div class="writeresponse">
                <button type="button" class = write>
                    WRITE A RESPONSE
                </button>
                <button type="button" class = delete>
                    DELETE
                </button>
            </div>
        </div>

    </div>

</div>

</body>
</html>

