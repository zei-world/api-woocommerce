---
title: PHP Module
position: 2
---

This example was written to explain how to use our PHP class.
All you need to do is to retrieve the ZEI.php file and follow the instructions :

1. [Download](https://raw.githubusercontent.com/zei-world/api/master/PHP/ZEI.php) and put ZEI.php on your source
    files
2. Include it in your back-end pages and use the class (see examples)
3. When the user reaches the end of the process of the offer or the reward, validate it with the right class method

**Example to display the user module (and generate his token) :**

```html
<?php require_once('ZEI.php'); ?>

<object id="ZEI"></object>

<script type="text/javascript" src="<?=ZEI::getScriptUrl()?>" async="true"></script>
<!--
    getModuleUrl() or getModuleUrl(true, true) will display all profiles (for B2C and B2B)
    getModuleUrl(false) or getModuleUrl(false, true) will only display profiles for B2B
    getModuleUrl(true, false) will only display profiles for B2C
-->
```

**You will obtain the following result (if you are connected and not connected on ZEI):**

![](/images/module.jpg "Zei account module")

**If you click on the button, it will open a window to select your ZEI profile :**

![](/images/window.jpg "Zei login and register page")

**Finally, validate your offer or your reward :**

```php
<?php
    // (1) Includes the main API class
    require_once('ZEI.php');
    
    // (2A1) Get current ZEI profile id with the "zei" cookie
    //       (previously stored by the module)
    $profile = $_COOKIE["zei"];
    
    // (2A2) Validate an offer with its id (here 42)
    //       and the quantity of products purchased (here 4, default is 1)
    ZEI::validateOffer(42, $profile, 4);
    
    // (2B1) Retrieve the reward code
    $code = "EXAMPLE_CODE";
    
    // (2B2) Check a reward code (will tell you if it is usable)
    ZEI::checkReward($code);
    
    // (2B3) Validate a reward code (will consume the code)
    ZEI::validateReward($code);
?>
```
