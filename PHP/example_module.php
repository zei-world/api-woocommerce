<?php
    require_once('ZEI.php');
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
        <object id="ZEI" data="<?=ZEI::getModuleUrl()?>"></object>
        <!--
            getModuleUrl() or getModuleUrl(true, true) will display all profiles (for B2C and B2B)
            getModuleUrl(false) or getModuleUrl(false, true) will only display profiles for B2B
            getModuleUrl(true, false) will only display profiles for B2C
        -->
    </body>
</html>