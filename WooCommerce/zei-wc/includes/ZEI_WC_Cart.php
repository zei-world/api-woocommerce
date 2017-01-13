<?php
/**
 * ZEI WC. cart
 *
 * @package  ZEI_WC_Cart
 * @author   Nazim from ZEI
 */

if(!defined('ABSPATH')) exit;
if(!class_exists('ZEI_WC_Cart')):

class ZEI_WC_Cart {
    public function __construct() {
        add_action('woocommerce_after_cart_table', array($this, "module"));
    }

    public function module() {
        if(isset($_SESSION['zeiToken'])) {
            include_once 'ZEI_WC_API.php';
            $url = ZEI_WC_API::getModuleUrl($_SESSION['zeiToken'], true, true);
            if($url) echo "<object id=\"ZEI\" width=\"360px\" height=\"60px\" data=\"".$url."\"></object>";
        }
    }
}

endif;
