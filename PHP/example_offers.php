<?php
    require_once('ZEI.php');
?>
<html>
<head>
    <title>ZEI API - Full PHP example</title>
</head>
<body>
    <h1>Zero ecoimpact API - PHP - Offers</h1>

    <?php
        $profile = $_COOKIE["zei"]; // The current user profile is stored in a cookie named "zei"

        if($profile) {
            if(ZEI::validateOffer(24, $profile, 2)) {
                echo "Validation of offer 24 with 2 units successful :)";
            } else {
                echo "Validation failed :(";
            }
        } else {
            echo "No ZEI profile yet selected !";
        }
    ?>
</body>
</html>
