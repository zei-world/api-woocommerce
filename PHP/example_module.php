<?php
    require_once('ZEI.php'); // (1) Includes the main API class
    $zei = new ZEI(); // (2) Creates main instance, must be UNIQUE
    $zei->requestToken(); // (3) Request a token for the session
?>
<html>
    <head>
        <title>ZEI API - Full PHP example</title>
        <style>
            #ZEI {
                /* Ratio : 4.(1/3) */
                width: 360px;
                height: 60px;
            }
            /* DEMO */
            body { margin: 60px; line-height: 20px; }
        </style>
    </head>
    <body>
        <h1>Zero ecoimpact API - PHP - User module</h1>
        <!-- (4) Display the HTML user module (params : isB2B, isB2C, redirect_uri/callback) -->
        <object id="ZEI" data="<?=$zei->getModuleUrl(true, true)?>"></object>
    </body>
</html>