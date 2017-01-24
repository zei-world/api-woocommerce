---
title: PHP Module
position: 2
---

```html
<?php
    require_once('ZEI.php'); // (1) Includes the main API class
    $zei = new ZEI(); // (2) Creates main instance, must be UNIQUE
    $_SESSION['zeiToken'] = $zei->requestToken(); // (3) Token request
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
    <!-- (4) Display the HTML user module (params : isB2B, isB2C, redirect_uri/callback) -->
    <object id="ZEI" data="<?=$zei->getModuleUrl(true, true)?>"></object>
</body>
</html>
```

![](/images/module.jpg "Zero ecoimpact account module")

![](/images/window.jpg "Zero ecoimpact login and register page")

```php
<?php
    // (1) Includes the main API class
    require_once('ZEI.php');
    // (2) Creates main instance, must be UNIQUE
    $zei = new ZEI();
    // (3) Retreaves the token
    $zei->setToken($_SESSION['zeiToken']);
    // (4) Validate offer with its id (here 0) and the amount of products purchased
    $zei->validateOffer(0, 1);
?>
```
