---
title: PHP Module
position: 2
---

This example was written to explain how to use our already written PHP library.
To go fast, all you need is to retrieve the ZEI.php file and follow the instructions :

1. [Download](https://raw.githubusercontent.com/zeroecoimpact/API/master/PHP/ZEI.php) and put ZEI.php on source files
2. Include it in your back-end pages and use the class (see examples)
3. When the user reaches the end of the process of the offer or the reward, validate it with the right class method

**Example to display the user module (and generate his token) :**

```html
<?php require_once('ZEI.php'); ?>

<object
    id="ZEI"
    style="width:320px;height:128px"
    data="<?=ZEI::getModuleUrl()?>"
></object>

<!--
    getModuleUrl() or getModuleUrl(true, true) will display all profiles (for B2C and B2B)
    getModuleUrl(false) or getModuleUrl(false, true) will only display profiles for B2B
    getModuleUrl(true, false) will only display profiles for B2C
-->
```

The module size could NOT be changed
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
    
    // (2A) Validate an offer with its id (here 42)
    //      and the quantity of products purchased (here 4, default is 1)
    ZEI::validateOffer(42, 4);
    
    // (2B1) Check a reward code
    ZEI::checkReward("EXAMPLE_CODE");
    
    // (2B2) Validate a reward code
    ZEI::validateReward("EXAMPLE_CODE");
?>
```
