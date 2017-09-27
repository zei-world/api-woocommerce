<?php
/**
 * Integration ZEI WC.
 *
 * @package  ZEI_WC_Integration
 * @category Integration
 * @author   Nazim from ZEI
 */

if(!defined('ABSPATH')) exit;
if(!class_exists('ZEI_WC_Integration')):

class ZEI_WC_Integration extends WC_Integration {

    private $offers = [];

	/**
	 * Init and hook in the integration.
	 */
	public function __construct() {
		$this->id = 'zei-wc';
		$this->method_title = 'Zei';
		$this->method_description =
                "For your API credentials go to "
                ."<a href='https://zei-world.com' target='_blank'>Zei</a>"
                .", then you could find \"API\" in \"My Tools\" from your Company Profile.<br/>"
                ."After that, you'll be able to validate your offers and rewards from each WooCommerce product."
        .$this->getStatus();

		// Load the settings
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables
		$this->zei_api_key = $this->get_option('zei_api_key');
		$this->zei_api_secret = $this->get_option('zei_api_secret');
        $this->zei_global_offer = $this->get_option('zei_global_offer');

		// Actions
		add_action('woocommerce_update_options_integration_'.$this->id, array($this, 'process_admin_options'));

		// Filters
		add_filter('woocommerce_settings_api_sanitized_fields_'.$this->id, array($this, 'sanitize_settings'));
	}

	public function getStatus() {
	    if((!isset($_GET['page']) || $_GET['page'] !== "wc-settings")
            && (!isset($_GET['tab']) || $_GET['tab'] !== "integration")) return null;

        if(!$this->get_option('zei_api_key') || !$this->get_option('zei_api_secret')) return null;

	    $content = "<br/></br><strong>Status</strong><br/>";

        $offers = ZEI_WC_API::getOffersList();

        if($offers === null) {
            $https = $this->get_option('zei_api_https');
            if(!$https || $https === 'yes') {
                return $content."✗ Error while connecting to ZEI servers, <strong>try to disable HTTPS</strong><br/>";
            }
            return $content."✗ Error while connecting to servers, <strong>contact ZEI team</strong><br/>";
        }

        $content .= "✓ Connected to ZEI servers<br/>";

        if($offers === false) return $content."✗ Wrong API id and/or secret<br/>";

        $this->offers = isset($offers['message']) ? $offers['message'] : $offers;
	    return $content."✓ Found ".sizeof($this->offers)." offer(s)<br/>";
    }

	/**
	 * Initialize integration settings form fields.
	 *
	 * @return void
	 */
	public function init_form_fields() {
        $fields = array();

        $fields['zei_api_key'] = array(
            'title'             => __('API Key', 'woocommerce-zei-wc'),
            'type'              => 'text',
            'description'       => __('Enter your ZEI API Key from your company tools.', 'woocommerce-zei-wc'),
            'desc_tip'          => false,
            'default'           => ''
        );

        $fields['zei_api_secret'] = array(
            'title'             => __('API Secret', 'woocommerce-zei-wc'),
            'type'              => 'text',
            'description'       => __('Enter your ZEI API Secret from your company tools.', 'woocommerce-zei-wc'),
            'desc_tip'          => false,
            'default'           => ''
        );

        if($this->get_option('zei_api_key') && $this->get_option('zei_api_secret')) {
            $fields['zei_api_https'] = array(
                'title'             => __('Use HTTPS', 'woocommerce-zei-wc'),
                'type'              => 'checkbox',
                'description'       => __('Use or not secure API requests.', 'woocommerce-zei-wc'),
                'default'           => 'yes'
            );
        }

        if($this->offers && sizeof($this->offers) > 0) {

            $fields['zei_module_location'] = array(
                'title'             => __('Module location', 'woocommerce-zei-wc'),
                'type'              => 'select',
                'description'       => __('Location where ZEI account module must appear.', 'woocommerce-zei-wc'),
                'desc_tip'          => false,
                'options'           => array(
                    0 => 'After "Order Review" (recommended)',
                    1 => 'On "Additional Information"'
                ),
                'default'           => 0
            );

            $fields['zei_global_offer'] = array(
                'title'             => __('Global offer', 'woocommerce-zei-wc'),
                'type'              => 'select',
                'description'       => __('Use a ZEI offer for the whole store.', 'woocommerce-zei-wc'),
                'desc_tip'          => false,
                'options'           => array(0 => '') + $this->offers,
                'default'           => ''
            );

        }

        $this->form_fields = $fields;
	}

	/**
	 * Santize our settings
	 * @see process_admin_options()
	 */
	public function sanitize_settings($settings) {
		if(isset($settings) && isset($settings['zei_api_key']))
		    $settings['zei_api_key'] = strtolower($settings['zei_api_key']);
        if(isset($settings) && isset($settings['zei_api_secret']))
            $settings['zei_api_secret'] = strtolower($settings['zei_api_secret']);
		return $settings;
	}

	/**
	 * Validate the API key
	 * @see validate_settings_fields()
	 */
	public function validate_zei_api_key_field($key) {
		$value = $_POST[$this->plugin_id.$this->id.'_'.$key];
		if(isset($value) && 32 !== strlen($value))
            WC_Admin_Settings::add_error(esc_html__('Looks like you made a mistake with the API Key field. '
                .'Try again or contact us if the exact key isn\'t working.', 'woocommerce-zei-wc'));
		return $value;
	}

    /**
     * Validate the API secret
     * @see validate_settings_fields()
     */
    public function validate_zei_api_secret_field($key) {
        $value = $_POST[$this->plugin_id.$this->id.'_'.$key];
        if(isset($value) && 45 !== strlen($value))
            WC_Admin_Settings::add_error(esc_html__('Looks like you made a mistake with the API Secret field. '
                .'Try again or contact us if the exact key isn\'t working.', 'woocommerce-zei-wc'));
        return $value;
    }

    /**
     * Validate the module location
     * @see validate_settings_fields()
     */
    public function validate_zei_module_location_field($key) {
        if(isset($_POST[$this->plugin_id.$this->id.'_'.$key])) return $_POST[$this->plugin_id.$this->id.'_'.$key];
        return null;
    }

    /**
     * Validate the global offer
     * @see validate_settings_fields()
     */
    public function validate_zei_global_offer_field($key) {
        if(isset($_POST[$this->plugin_id.$this->id.'_'.$key])) return $_POST[$this->plugin_id.$this->id.'_'.$key];
        return null;
    }
}

endif;
