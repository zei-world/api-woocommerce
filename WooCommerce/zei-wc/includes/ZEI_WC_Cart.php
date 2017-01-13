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
        add_action('woocommerce_before_cart_totals', array($this, "module"));
    }

    public function module() {
        global $woocommerce;
        if(isset($_SESSION['zeiToken'])) {
            $display = false;
            foreach($woocommerce->cart->get_cart() as $elem) {
                if(get_post_meta($elem['product_id'], 'zei_offer', true)) {
                    $display = true;
                    break;
                }
            }
            if($display) {
                include_once 'ZEI_WC_API.php';
                $url = ZEI_WC_API::getModuleUrl($_SESSION['zeiToken'], true, true);
                if($url) echo "<object id=\"ZEI\" width=\"360px\" height=\"60px\" data=\""
                    .$url."\"></object>";
            }
        }
    }
}

endif;
