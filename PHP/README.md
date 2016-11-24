PHP Edition
===========

1. Upload ZEI.php to your server
2. Include this file in your page (or in your backend)
3. Use the class : see examples
4. Once the module deployed, you must keep the token in your navigation logic
5. When the user reaches the end of the process of the offer or reward, validate them with the class methods

API requests with their parameters are available on the main README.md of the repo.

If an update of the ZEI.php file is available, replace the old one with the new version and do not forget to put again
you API id and secret.

You are free to don't use the ZEI.php class and do it your wait :)

Simple version
------------
Call the deploy() function located in the ZEI class
```html
<?php require_once('ZEI.php'); ?>
<html>
<head>
   <style>
       #ZEI {
           /* Ratio : 2.5 */
           width: 320px; /* Max : 1375 */
           height: 128px; /* Max : 550 */
       }
   </style>
</head>
<body>
   <object id="ZEI" data="<?=deploy()?>"></object>
</body>
</html>
```

Full version
------------
The ZEI instance is active on the page
```html
<?php
   require_once('ZEI.php'); // (1) Includes the main API class
   $zei = new ZEI(); // (2) Creates main instance, must be UNIQUE
   $zei->requestToken(); // (3) Request a token
?>
<html>
<head>
   <style>
       #ZEI {
           /* Ratio : 2.5 */
           width: 320px; /* Max : 1375 */
           height: 128px; /* Max : 550 */
       }
   </style>
</head>
<body>
   <!-- (4) Display the HTML module -->
   <object id="ZEI" data="<?=$zei->getModule()?>"></object>
</body>
</html>
```
