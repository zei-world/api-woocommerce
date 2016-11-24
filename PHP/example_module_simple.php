<?php require_once('ZEI.php'); ?>
<html>
<head>
    <title>ZEI API - Simple PHP example</title>
    <style>
        #ZEI {
            /* Ratio : 2.5 */
            width: 320px; /* Max : 1375 */
            height: 128px; /* Max : 550 */
        }

        /* DEMO */
        body { margin: 60px; line-height: 20px; }
    </style>
</head>
<body>
    <h1>Zero ecoimpact API - PHP (simple)</h1>

    <object id="ZEI" data="<?=deploy()?>"></object>
</body>
</html>