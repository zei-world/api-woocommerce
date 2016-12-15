<?php
    require_once('ZEI.php');

    $token = $_GET['token'];

    $zei = new ZEI();
    $zei->setToken($token);
?>
<html>
<head>
    <title>ZEI API - Full PHP example</title>
</head>
<body>
    <h1>Zero ecoimpact API - PHP - Offer Validation</h1>

    <h2>Current token : <?=$token?></h2>

    <?php
        if($zei->validateOffer(2, 1)) {
            echo "Validation effectuée :)";
        } else {
            echo "Validation échouée :(";
        }
    ?>
</body>
</html>
