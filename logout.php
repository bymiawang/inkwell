<?php
session_start();
?>

<?php

unset($_SESSION["logged_in"]);
unset($_SESSION["user_name"]);
unset($_SESSION["security_level"]);


?>

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="Stylesheets/style_main.css">
    <link rel="stylesheet" href="Stylesheets/navbar.css">
    <link rel="stylesheet" href="Stylesheets/homepage.css">
    <link rel="stylesheet" href="Stylesheets/cards.css">
    <link rel="stylesheet" href="Stylesheets/detail1.css">
    <link rel="stylesheet" href="Stylesheets/writer.css">
    <title>Inkwell Write Response </title>

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

<?php
echo "<div class='subtitle success'>You are successfully logged out!";
echo "<a href='homepage.php'><div style='text-decoration: underline'>Back to homepage<div></div></a></div>";
?>

</body>
