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
        if(ZEI::validateOffer(24, 2)) {
            echo "Validation of offer 24 with 2 units successful :)";
        } else {
            echo "Validation failed :(";
        }
    ?>
</body>
</html>
