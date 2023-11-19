<?php

session_start();
unset($_SESSION["logged_in"]);
unset($_SESSION["user_name"]);
unset($_SESSION["security_level"]);
echo "You are successfully logged out!";
echo "<a href='homepage.php'>Back to homepage</a>";