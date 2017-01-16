<?php
/**
 * ZEI WC. product page
 *
 * @package  ZEI_WC_Product
 * @author   Nazim from ZEI
 */

if(!defined('ABSPATH')) exit;
if(!class_exists('ZEI_WC_Product')):

include_once 'ZEI_WC_API.php';

class ZEI_WC_Product {
    public function __construct() {
        // Display Fields
        add_action('woocommerce_product_options_general_product_data', array($this, 'zei_offers_add_fields'));

        // Save Fields
        add_action('woocommerce_process_product_meta', array($this, 'zei_offers_save_fields'));
    }

    public function zei_offers_add_fields() {
        global $woocommerce, $post;

        $token = ZEI_WC_API::getToken();

        if($token) {
            $group = false;

            // OFFERS
            $offers = ZEI_WC_API::getOffersList($token);
            if($offers) {
                echo '<div class="options_group">'; $group = true;
                woocommerce_wp_select(array(
                    'id'      => '_zei_offer',
                    'label'   => __('Zero ecoimpact offer', 'woocommerce'),
                    'options' => ["disabled" => ""] + $offers
                ));
            }

            // REWARDS
            $rewards = ZEI_WC_API::getRewardsList($token);
            if($rewards) {
                if(!$group) {
                    echo '<div class="options_group">';
                    $group = true;
                }
                woocommerce_wp_select(array(
                    'id'      => '_zei_reward',
                    'label'   => __('Zero ecoimpact reward', 'woocommerce'),
                    'options' => ["disabled" => ""] + $rewards
                ));
            }

            if($group) echo '</div>';
        }
    }

    public function zei_offers_save_fields($postId) {
        $offer = $_POST['_zei_offer'];
        if(!empty($offer)) {
            if($offer === "disabled") $offer = "";
            update_post_meta($postId, '_zei_offer', esc_attr($offer));
        }
        $reward = $_POST['_zei_reward'];
        if(!empty($reward)) {
            if($reward === "disabled") $reward = "";
            update_post_meta($postId, '_zei_reward', esc_attr($reward));
        }
    }
}

endif;
