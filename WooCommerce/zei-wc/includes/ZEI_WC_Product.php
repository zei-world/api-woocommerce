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
        if($offers = ZEI_WC_API::getOffersList()) {
            echo '<div class="options_group">';
            woocommerce_wp_select(array(
                'id'      => '_zei_offer',
                'label'   => __('Zero ecoimpact offer', 'woocommerce'),
                'options' => array("disabled" => "") + $offers
            ));
            echo '</div>';
        }
    }

    public function zei_offers_save_fields($postId) {
        $offer = $_POST['_zei_offer'];
        if(!empty($offer)) {
            if($offer === "disabled") $offer = "";
            update_post_meta($postId, '_zei_offer', esc_attr($offer));
        }
    }
}

endif;
