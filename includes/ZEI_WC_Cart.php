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
include_once 'ZEI_WC_Debugger.php';

class ZEI_WC_Cart {
    public function __construct() {
        // Coupon
        add_filter('woocommerce_get_shop_coupon_data', array($this, "wc_get_coupon_data"), 10, 2);

        // Module
        $options = get_option('woocommerce_zei-wc_settings');
        if(isset($options['zei_module_location']) && $options['zei_module_location'] == 1) {
            add_action('woocommerce_after_order_notes', array($this, "display_module"));
        } else {
            add_action('woocommerce_checkout_order_review', array($this, "display_module"));
        }

        // Points validated
        add_action('woocommerce_order_status_completed', array($this, "completed"));
        add_action('woocommerce_thankyou', array($this, "thankyou"));
    }

    private function couponExists($coupon) {
        global $wpdb;

        $result = $wpdb->get_var(
            "select count(ID) from $wpdb->posts where post_title = '".$coupon."' and post_status = 'publish'"
        );

        ZEI_WC_Debugger::send("[COUPON] [EXISTS]", $result);

        return $result > 0;
    }

    public function wc_get_coupon_data($valid, $coupon) {
        if(substr($coupon, 0, 11) == 'zei_reward_') return false;
        $reward = ZEI_WC_API::checkReward(strtoupper($coupon));
        if($reward['success'] && !$this->couponExists($coupon)) {
            $rewardId = $reward['reward'];
            if($this->couponExists("zei_reward_".$rewardId)) {
                $model = new WC_Coupon('zei_reward_'.$rewardId);
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
                    update_post_meta($new, '_zei_reward', $rewardId);
                    ZEI_WC_Debugger::send("[WORDPRESS] [COUPON]", "Copied from " . $rewardId . " for $coupon");
                    return true;
                }
                ZEI_WC_Debugger::send("[WORDPRESS] [COUPON] Model for $coupon not found");
            }
        }
        ZEI_WC_Debugger::send("[WORDPRESS] [COUPON] Already exists or empty API call");
        return $valid;
    }

    public function display_module() {
        global $woocommerce;

        $options = get_option('woocommerce_zei-wc_settings');
        $display = isset($options['zei_global_offer']) && $options['zei_global_offer'] != 0;

        if(!$display) {
            foreach($woocommerce->cart->get_cart() as $item) {
                if(get_post_meta($item['product_id'], '_zei_offer', true)) {
                    $display = true;
                    break;
                }
            }
        }

        if($display) {
            echo '<object id="ZEI"></object>';
            echo '<script type="text/javascript" src="'.ZEI_WC_API::getScriptUrl().'" async="true"></script>';
        }

        ZEI_WC_Debugger::send("[WORDPRESS] [MODULE] Module is " . ($display ? "" : "not ") . "displayed");
    }

    private function validateOffer($item, $entity) {
        $options = get_option('woocommerce_zei-wc_settings');

        // Offer Id : Global or item
        $post = get_post_meta($item['product_id'], '_zei_offer', true);
        //!\ $post est une string vide quand c'est une global offer
        $offerId = (isset($post) && $post !== 0 && $post !== '') ? $post : $options['zei_global_offer'];

        $response = false;

        if($offerId) {
            $response = ZEI_WC_API::validateOffer($offerId, $entity, $item['qty']);
        }

        ZEI_WC_Debugger::send("[WORDPRESS] [VALIDATION] Offer $offerId with " . ($response ? $response : "not valid response"));

        return $response;
    }
    
    public function thankyou($orderId) {
        if(isset($_COOKIE['zei'])) {
            $order = wc_get_order($orderId);
            $profile = $_COOKIE['zei'];
            if($profile && strlen(get_post_meta($order->id, '_zei_profile', true)) === 0) {
                add_post_meta($order->id, '_zei_profile', $profile);
                ZEI_WC_Debugger::send("[WORDPRESS] [CART] Profile $profile added to order $order");
            }
        }
    }

    public function completed($orderId) {
        $order = wc_get_order($orderId);

        // OFFERS
        $profile = get_post_meta($order->id, '_zei_profile', true);
        if(strlen($profile) > 0) {
            foreach($order->get_items() as $item) {
                $this->validateOffer($item, $profile);
                ZEI_WC_Debugger::send("[WORDPRESS] [CART] Item $item from order $orderId valiadted for profile $profile");
            }
        }

        // REWARDS
        foreach($order->get_used_coupons() as $code) {
            $coupon = new WC_Coupon($code);
            if($coupon->exists) {
                ZEI_WC_API::validateReward(strtoupper($code));
                ZEI_WC_Debugger::send("[WORDPRESS] [CART] Coupon $code used on order $orderId");
            }
        }
    }
}

endif;
