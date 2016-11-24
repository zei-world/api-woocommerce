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
<pre>
&lt;?php
    require_once('ZEI.php'); // (1) Includes the main API class
    $zei = new ZEI(); // (2) Creates main instance, must be UNIQUE
    $zei = new ZEI(); // (3) Creates main instance, must be UNIQUE
?&gt;
&lt;html&gt;
&lt;head&gt;
    &lt;style&gt;
        #ZEI {
            /* Ratio : 2.5 */
            width: 320px; /* Max : 1375 */
            height: 128px; /* Max : 550 */
        }
    &lt;/style&gt;
&lt;/head&gt;
&lt;body&gt;
    <!-- (4) Display the HTML module -->
    &lt;object id="ZEI" data="&lt;?=$zei-&gt;getModule()?&gt;"&gt;&lt;/object&gt;
&lt;/body&gt;
&lt;/html&gt;
</pre>
    </body>
</html>