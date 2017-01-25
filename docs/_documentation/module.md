---
title: PHP Module
position: 2
---

This example was written to explain how to use our already written PHP library.
To go fast, all you need is to retrieve the ZEI.php file and follow the instructions :

1. [Download](https://raw.githubusercontent.com/zeroecoimpact/API/master/PHP/ZEI.php) and put ZEI.php on source files
2. Include it in your back-end pages and use the class (see examples)
4. Once the user module is deployed, you must keep the token in your navigation logic (use GET, POST or PHP sessions)
5. When the user reaches the end of the process of the offer or the reward, validate them with the right class method

**Example to display the user module (and generate his token) :**

```html
<?php
    require_once('ZEI.php'); // (1) Includes the main API class
    $zei = new ZEI(); // (2) Creates main instance, must be UNIQUE
    $_SESSION['zeiToken'] = $zei->requestToken(); // (3) Token request
?>

<!-- (4) Display the HTML user module (params : isB2B, isB2C, redirect_uri/callback) -->
<object id="ZEI" style="width:320px;height:128px" data="<?=$zei->getModuleUrl(true, true)?>"></object>
```

The module size could not be changed
{: .info }

**It will gives you that (if you are connected and not connected on ZEI):**

![](/images/module.jpg "Zero ecoimpact account module")

**If you click on the button, it will open a window to select your ZEI profile :**

![](/images/window.jpg "Zero ecoimpact login and register page")

**Finally, validate your offer or your reward :**

```php
<?php
    // (1) Includes the main API class
    require_once('ZEI.php');
    // (2) Creates main instance, must be UNIQUE
    $zei = new ZEI();
    // (3) Retreaves the token
    $zei->setToken($_SESSION['zeiToken']);
    
    // (4A) Validate an offer with its id (here 0) and the quantity of products purchased
    $zei->validateOffer(0, 1);
    
    // (4B) Validate a reward with its id (here 0) and the amount of products purchased
    $zei->validateReward(0, 1);
?>
```
