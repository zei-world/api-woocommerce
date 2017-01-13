<?php
/**
 * Offers for ZEI WC.
 *
 * @package  ZEI_WC_Offers
 * @author   Nazim from ZEI
 */

if(!class_exists('ZEI_WC_Offers')):

class ZEI_WC_Offers {
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
                    echo '<div class="options_group">';

                    woocommerce_wp_text_input(
                        array(
                            'id'          => 'zei_offer',
                            'label'       => __( 'Zero ecoimpact offer id', 'woocommerce' ),
                            'placeholder' => '',
                            'desc_tip'    => 'true',
                            'description' => __( 'Enter the id of your offer', 'woocommerce' )
                        )
                    );

                    echo '</div>';
                }
            }
        }

        function zei_offers_save_fields() {
            die("SAVE");
        }
    }
}

endif;
