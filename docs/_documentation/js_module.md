---
title: NodeJS Module
position: 3
---

This example was written to explain how to use our NodeJS class.
To go fast, all you need is to retrieve the ZEI.js file and follow the instructions :

1. [Download](https://raw.githubusercontent.com/zeroecoimpact/API/master/NodeJS/ZEI.js) and put ZEI.js on your source
    files
2. Include it in your back-end pages and use the class (see examples)
3. When the user reaches the end of the process of the offer or the reward, validate it with the right class method

**Example to display the user module (and generate his token) :**

```html
<object id="ZEI"></object>

<script type="text/javascript" src="//zei-world.com/api/v2/script?id=[YOUR_API_KEY]" async="true"></script>
<!-- [YOUR_API_KEY] being your company ZEI API key -->
```

**It will gives you that (if you are connected and not connected on ZEI):**

![](/images/module.jpg "Zei account module")

**If you click on the button, it will open a window to select your ZEI profile :**

![](/images/window.jpg "Zei login and register page")

**Finally, validate your offer or your reward :**

```js
// (1A) Includes the main API class
var ZEI = require('./zei');

// (1B) If you used export (alternative)
import ZEI from './zei';

// (2A1) Get current ZEI profile id with the "zei" cookie
//       (previously stored by the module)
var profile = req.cookies.zei;
// "req" comes from your controller

// (2A2) Validate an offer with its id (here 42)
//       and the quantity of products purchased (here 4, default is 1)
ZEI.validateOffer(42, profile, 4, function(response) {
    console.log(response);
});

// (2B1) Retrieve the reward code
var code = "EXAMPLE_CODE";

// (2B2) Check a reward code (will tell you if it is usable)
ZEI.checkReward(code, function(response) {
    console.log(response);
});

// (2B3) Validate a reward code (will consume the code),
        you can combine 2B2 and 2B3 with callbacks)
ZEI.validateReward(code, function(response) {
    console.log(response);
});
```
