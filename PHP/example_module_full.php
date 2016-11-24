<?php
    require_once('ZEI.php'); // (1) Includes the main API class
    $zei = new ZEI(); // (2) Creates main instance, must be UNIQUE
    $zei->requestToken(); // (3) Request a token action
?>
<html>
    <head>
        <title>ZEI API - Full PHP example</title>
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
        <h1>Zero ecoimpact API - PHP (full)</h1>
        <object id="ZEI" data="<?=$zei->getModule()?>"></object>
    </body>
</html>