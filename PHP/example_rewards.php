<?php
    require_once('ZEI.php');
?>
<html>
<head>
    <title>ZEI API - Full PHP example</title>
</head>
<body>
    <h1>Zero ecoimpact API - PHP - Rewards</h1>

    <h2>Part 1 : Check if a code is valid</h2>
    <?php
        if(ZEI::checkReward('EXAMPLE_CODE')) {
            echo "Code is valid";
        } else {
            echo "Code is invalid (or server error)";
        }
    ?>

    <h2>Part 2 : Validate the reward code</h2>
    <?php
    if(ZEI::validateReward('EXAMPLE_CODE')) {
        echo "Reward validated :)";
    } else {
        echo "Validation failed :(";
    }
    ?>
</body>
</html>