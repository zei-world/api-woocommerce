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
        // Coupon
        add_filter('woocommerce_get_shop_coupon_data', array($this, "coupon"), 10, 2);

        // Module
        add_action('woocommerce_after_checkout_billing_form', array($this, "module"));

        // Token
        add_action('woocommerce_new_order', array($this, "token"));

        /* TODO : Modifier le tableau des détails avec les points gagnés sur ZEI */

        // Points validated
        add_action('woocommerce_order_status_completed', array($this, "completed"));
    }

    private function couponExists($coupon) {
        global $wpdb;
        return 0 < $wpdb->get_var("select count(ID) from $wpdb->posts"
            ." where post_title = '".$coupon."' and post_status = 'publish'"
        );
    }

    public function coupon($valid, $coupon) {
        if(substr($coupon, 0, 11) === 'zei_reward_') return false;
        $reward = ZEI_WC_API::codesValidate(strtoupper($coupon));
        if($reward && !$this->couponExists($coupon)) {
            if($this->couponExists("zei_reward_".$reward)) {
                $model = new WC_Coupon('zei_reward_'.$reward);
                if($model->exists) {
                    $original = get_post($model->id);
                    $new = wp_insert_post(array(
                        'post_title' => $coupon,
                        'post_type' => 'shop_coupon',
                        'to_ping' => $original->to_ping,
                        'menu_order' => $original->menu_order,
                        'post_content' => '',
                        'post_excerpt' => $original->post_excerpt,
                        'post_name' => $coupon,
                        'post_parent' => $original->post_parent,
                        'post_password' => $original->post_password,
                        'post_status' => 'publish',
                        'comment_status' => $original->comment_status,
                        'ping_status' => $original->ping_status,
                        'post_author' => $original->post_author
                    ));
                    foreach(get_post_meta($model->id) as $key => $value) add_post_meta($new, $key, $value[0]);
                    update_post_meta($new, 'usage_limit', 1);
                    update_post_meta($new, '_zei_reward', $reward);
                    return true;
                }
            }
        }
        return $valid;
    }

    public function module() {
        global $woocommerce;

        $token = ZEI_WC_API::getToken();

        if($token) {
            $display = false;
            foreach($woocommerce->cart->get_cart() as $item) {
                if(get_post_meta($item['product_id'], '_zei_offer', true)) {
                    $display = true;
                    break;
                }
            }
            if($display) {
                $url = ZEI_WC_API::getModuleUrl($token, true, true);
                if($url) echo "<object id=\"ZEI\" width=\"360px\" height=\"60px\" data=\""
                    .$url."\"></object>";
            }
        }
    }

    public function token($orderId) {
        $token = ZEI_WC_API::getToken();
        if($token) update_post_meta($orderId, '_zei_token', $token);
    }

    public function completed($orderId) {
        if(!session_id()) session_start();
        $order = wc_get_order($orderId);
        foreach($order->get_items() as $item) {
            $token = get_post_meta($orderId, '_zei_token', true);
            if($token) {
                // OFFERS
                $offerId = get_post_meta($item['product_id'], '_zei_offer', true);
                if($offerId) ZEI_WC_API::validateOffer($token, $offerId, $item['qty']);

                // REWARDS
                foreach($order->get_used_coupons() as $code) {
                    $coupon = new WC_Coupon($code);
                    if($coupon->exists) {
                        $meta = get_post_meta($coupon->id, '_zei_reward', true);
                        if($meta) {
                            $reward = ZEI_WC_API::codesValidate(strtoupper($code));
                            if($reward == $meta) ZEI_WC_API::validateReward($token, $reward, $order->get_total());
                        }
                    }
                }

                // END OF TOKEN
                unset($_SESSION['zeiToken']);
            }
        }
    }
}

endif;
