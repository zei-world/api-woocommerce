<?php
/**
 * ZEI WC. cart
 *
 * @package  ZEI_WC_Cart
 * @author   Nazim from ZEI
 */

if(!defined('ABSPATH')) exit;
if(!class_exists('ZEI_WC_Cart')):

include_once 'ZEI_WC_API.php';

class ZEI_WC_Cart {
    public function __construct() {
        // Module
        add_action('woocommerce_after_checkout_billing_form', array($this, "module"));

        // Token
        add_action('woocommerce_new_order', array($this, "token"));

        /* TODO : Modifier le tableau des détails avec les points gagnés sur ZEI */

        // Points validated
        add_action('woocommerce_order_status_completed', array($this, "completed"));
    }

    public function module() {
        global $woocommerce;
        if(isset($_SESSION['zeiToken'])) {
            $display = false;
            foreach($woocommerce->cart->get_cart() as $item) {
                if(get_post_meta($item['product_id'], '_zei_offer', true)) {
                    $display = true;
                    break;
                }
            }
            if($display) {
                $url = ZEI_WC_API::getModuleUrl($_SESSION['zeiToken'], true, true);
                if($url) echo "<object id=\"ZEI\" width=\"360px\" height=\"60px\" data=\""
                    .$url."\"></object>";
            }
        }
    }

    public function token($orderId) {
        update_post_meta($orderId, '_zei_token', $_SESSION['zeiToken']);
    }

    public function completed($orderId) {
        foreach(wc_get_order($orderId)->get_items() as $item) {
            $token = get_post_meta($orderId, '_zei_token', true);
            if($token) {
                $offerId = get_post_meta($item['product_id'], '_zei_offer', true);
                if($offerId) {
                    ZEI_WC_API::validateOffer($token, $offerId, $item['qty']);
                    unset($_SESSION['zeiToken']);
                }
            }
        }
    }
}

endif;
