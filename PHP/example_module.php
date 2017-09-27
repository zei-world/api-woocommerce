<?php
    require_once('ZEI.php');
?>
<html>
    <head>
        <title>ZEI API - Full PHP example</title>
    </head>
    <body style="margin: 60px; line-height: 20px;">
        <h1>Zei API - PHP - User module</h1>

        <object id="ZEI"></object>

        <script type="text/javascript" src="<?=ZEI::getScriptUrl()?>" async="true"></script>
        <!--
            getScriptUrl() or getScriptUrl(true, true) will display all profiles (for B2C and B2B)
            getScriptUrl(false) or getScriptUrl(false, true) will only display profiles for B2B
            getScriptUrl(true, false) will only display profiles for B2C
        -->
    </body>
</html>