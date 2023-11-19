/*$(document).ready(function(){

    // Get the security level
    var logged_in = "<?php echo $_SESSION['logged_in']?>";
    var security_level = "<?php echo $_SESSION['security_level']?>";

    console.log(logged_in);
    console.log(security_level);

    // If logged in, hide login buttons
    if (logged_in == "1") {
        $('.login_buttons').css("display", "none")
    }

    // If admin, display the admin navbar and hide the default
    if (security_level == "0"){
        $('.profile_nav').css("display", "flex")
    }

    // If writer, display the writer navbar and hide the default
    if (security_level == "1"){

        $('.profile_nav').css("display", "flex")
    }
}) */