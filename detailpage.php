<?php

require_once 'config.php'; // Edit with your path to config.php file

// Get the response_id from the query parameter
$response_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

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
    $submission_text = $row['submission_text'];
    $submission_date = $row['submission_date'];
    $submission_title = $row['submission_title'];
} else {
    $submission_text = 'No text found for this submission.';
}

$stmt->close(); // change later
$mysql->close();

?>

<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="Stylesheets/style_main.css">
<link rel="stylesheet" href="Stylesheets/navbar.css">
<link rel="stylesheet" href="Stylesheets/homepage.css">
<link rel="stylesheet" href="Stylesheets/cards.css">
<link rel="stylesheet" href="Stylesheets/detail.css">
<link rel="stylesheet" href="Stylesheets/writer.css">
<title>Inkwell Write Response </title>
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
                    <div>[PFP]</div>
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

<div class="container">
    <a href="resultpage.php">< Back</a>
    <div class="banner-container">
        <img src="Images/thumbnaildemo.png" alt="Banner Image" class="banner-image">
    </div>
    <span class="date"><?php echo($submission_date); ?></span>
    <div class="subtitle" ><?php echo($submission_title); ?>></div>
    <div class="subtext">
        <?= nl2br(htmlspecialchars($submission_text)) ?></div>
    <br><hr><br>
    <div class="subtitle" >Writer Response</div>
    <div class="subtext">
        My wife and I have been married for two years and recently settled in my hometown and bought our first house. We previously lived in an apartment building in a big city with maintenance people who came to fix anything that broke. Shortly after we bought the house, I got a promotion at work and now work out of town a lot. Whenever anything goes wrong with the house, or car, usually my dad will come over to help out.
        <br><br>
        For instance, one day my wife got a flat tire, and it would take AAA an hour to get there, so I told her to text my dad and he'd come help her change it. More recently, a fuse blew and needed replacing so my dad came and fixed it. Whenever he comes over to fix something, he likes to walk my wife through how to do it so that if it happens again she'll know how. He has two sons and three daughters and this is how he taught all of us basic tasks like changing a tire, changing our own oil, replacing fuses, unclogging a drain, cleaning gutters, etc. My wife was never taught these things and spent her adult life having building maintenance do everything for her.
        <br><br>
        The thing is, this annoys my wife. She complains to me that he is "mansplaining" to her. I spoke to her on the phone after the fuse incident and made sure everything was taken care of and she said "yea, I just had to listen to your dad mansplain the whole time about how to do it." This is upsetting to me. I told her that was disrespectful-he was taking time out of his day to come help her and also it isn't "mansplaining" if she doesn't know how to do it-it is literally just explaining how to do it so she can do it herself in the future. She replied that she didn't need him to explain it to her because she could just Google it if she wanted to learn, so I told her next time to use Google instead of calling my dad.
        <br><br>
        Now she is mad at me and saying I am being "unsupportive" and I should talk to my dad and explain how he is making her feel. I told her I would not be doing that and if she wanted a maintenance guy that didn't talk, she could call one up the next time a fuse blew and sit in the dark until he found time to get to her. She hung up on me and our communication has been chilly since. I am not sure how to move forward. I think she is being rude and she says I am not "hearing" her and keeps talking about how harmful "mansplaining" is to women. I get the bigger picture of mansplaining but that's not what my dad is doing! I'm also a little dismayed that my wife has no interest in learning how to maintain our house. I don't expect her to learn how to rewire the whole thing but knowing how to light the pilot light on the hot water tank would be nice.
    </div>

</div>
</body>
</html>
