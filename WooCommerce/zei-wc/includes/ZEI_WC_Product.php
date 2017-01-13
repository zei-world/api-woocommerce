<?php
/**
 * Offers for ZEI WC.
 *
 * @package  ZEI_WC_Product
 * @author   Nazim from ZEI
 */

if(!defined('ABSPATH')) exit;
if(!class_exists('ZEI_WC_Product')):

class ZEI_WC_Product {
    public function __construct() {
        // Display Fields
        add_action('woocommerce_product_options_general_product_data', 'zei_offers_add_fields');

        // Save Fields
        add_action('woocommerce_process_product_meta', 'zei_offers_save_fields');

        function zei_offers_add_fields() {
            global $woocommerce, $post;

            $options = get_option('woocommerce_zei-wc_settings');
            if($options && $options['zei_api_key'] && $options['zei_api_secret']) {
                // API
                include_once 'ZEI_WC_API.php';
                $api = new ZEI_WC_API();

                // Token
                if($api->requestToken($options['zei_api_key'], $options['zei_api_secret'])) {
                    $group = false;

                    // OFFERS
                    $offers = $api->getOffersList();
                    if($offers) {
                        echo '<div class="options_group">'; $group = true;
                        woocommerce_wp_select(
                            array(
                                'id'      => 'zei_offer',
                                'label'   => __('Zero ecoimpact offer', 'woocommerce'),
                                'options' => ["disabled" => ""] + $offers
                            )
                        );
                    }

                    // REWARDS
                    $rewards = $api->getRewardsList();
                    if($rewards) {
                        if(!$group) {
                            echo '<div class="options_group">';
                            $group = true;
                        }
                        woocommerce_wp_select(
                            array(
                                'id'      => 'zei_reward',
                                'label'   => __('Zero ecoimpact reward', 'woocommerce'),
                                'options' => ["disabled" => ""] + $rewards
                            )
                        );
                    }

                    if($group) echo '</div>';
                }
            }
        }

        function zei_offers_save_fields($postId) {
            $offer = $_POST['zei_offer'];
            if(!empty($offer)) {
                if($offer === "disabled") $offer = "";
                update_post_meta($postId, 'zei_offer', esc_attr($offer));
            }
            $reward = $_POST['zei_reward'];
            if(!empty($reward)) {
                if($reward === "disabled") $reward = "";
                update_post_meta($postId, 'zei_reward', esc_attr($reward));
            }
        }
    }
}

endif;
