---
title: WooCommerce (EN)
position: 1
---

**You use WordPress with WooCommerce plugin ? Follow those steps !**

1. Install the plugin in WordPress
  * [Dowload this zip archive](https://github.com/zeroecoimpact/API/blob/master/WooCommerce/zei-woocommerce-latest.zip?raw=true)
  and install it on WordPress : "Plugins" > "Add New" > "Upload Plugin"
  * Or use the link and install it directly from WordPress (**coming soon**)
  * Or upload the "zei-wc" directory
  ([from the repository](https://github.com/zeroecoimpact/API/tree/master/WooCommerce){:target="_blank"})
  on "/wp-content/plugins/" from your WordPress installation (by FTP for example)
2. Go to [Zero ecoimpact](https://zero-ecoimpact.org){:target="_blank"} > your company Public Profile > "My Tools" > API
3. Then, in your WordPress admin panel, go to "WooCommerce" > "Settings" > "Integration" and fill in your ZEI API credentials (API key and API secret)
4. From there you'll be able to manage your offers directly from each concerned products : in a product from "Products", in "General" tab, you will find "Zero ecoimpact offer" option which will allow you to link this product to a corresponding specific offer on ZEI
5. For your rewards, refer to the next section below
5. Let the magic do !

**Coupon codes for rewards**

Edited codes on ZEI must work on your online shop, so you need to edit some WooCommerce coupon codes : 

1. First of all, you need to know your reward id (they are listed in your API page on ZEI)
2. In your WordPress admin panel, go to "WooCommerce" > "Coupons" > "Add Coupon"
3. Name it EXACTLY as following : "zei_reward_42" (42 being your reward id)
4. Edit the coupon like you want (according to your ZEI reward)
5. Let the magic do again !
