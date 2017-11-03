<?php
    require_once('ZEI.php');

    $code = "EXAMPLE_CODE";
?>
<html>
<head>
    <title>ZEI API - Full PHP example</title>
</head>
<body>
    <h1>Zei API - PHP - Rewards</h1>

    <h2>Part 1 : Check if a code is valid</h2>
    <?php
        if(ZEI::checkReward($code)) {
            echo "Code is valid";
        } else {
            echo "Code is invalid (or server error)";
        }
    ?>

    <h2>Part 2 : Validate the reward code</h2>
    <?php
    if(ZEI::validateReward($code)) {
        echo "Reward code validated :)";
    } else {
        echo "Validation failed :(";
    }
    ?>
</body>
</html>