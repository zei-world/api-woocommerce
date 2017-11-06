<?php
    require_once('ZEI.php');
?>
<html>
<head>
    <title>ZEI API - Full PHP example</title>
</head>
<body>
    <h1>Zei API - PHP - Offers</h1>

    <?php
        $profile = $_COOKIE["zei"]; // The current user profile is stored in a cookie named "zei"
        $offerId = 42; // Offer ID
        $units = 4; // Number of units
        if($profile) {
            if(ZEI::validateOffer($offerId, $profile, $units)) {
                echo "Validation of offer ".$offerId." with ".$units." units successful :)";
            } else {
                echo "Validation failed :(";
            }
        } else {
            echo "No ZEI profile yet selected !";
        }
    ?>
</body>
</html>
