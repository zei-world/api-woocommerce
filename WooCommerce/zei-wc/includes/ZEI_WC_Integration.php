<?php
/**
 * Integration ZEI WC.
 *
 * @package  ZEI_WC_Integration
 * @category Integration
 * @author   Nazim from ZEI
 */

if(!class_exists('ZEI_WC_Integration')):

class ZEI_WC_Integration extends WC_Integration {
	/**
	 * Init and hook in the integration.
	 */
	public function __construct() {
		global $woocommerce;

		$this->id = 'zei-wc';
		$this->method_title = __('Zero ecoimpact', 'woocommerce-zei-wc');
		$this->method_description = __(
                "For your API credentials go to "
                ."<a href='https://zero-ecoimpact.org' target='_blank'>Zero ecoimpact</a>"
                .", then you could find \"API\" in \"My Tools\" from your Company Profile.<br/>"
                ."After that, you'll be able to validate your offers and rewards from each WooCommerce product.",
            'woocommerce-zei-wc');

		// Load the settings
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables
		$this->api_key = $this->get_option('api_key');
		$this->api_secret = $this->get_option('api_secret');

		// Actions
		add_action('woocommerce_update_options_integration_'.$this->id, array($this, 'process_admin_options'));

		// Filters
		add_filter('woocommerce_settings_api_sanitized_fields_'.$this->id, array($this, 'sanitize_settings'));
	}

	/**
	 * Initialize integration settings form fields.
	 *
	 * @return void
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'api_key' => array(
				'title'             => __('API Key', 'woocommerce-zei-wc'),
				'type'              => 'text',
				'description'       => __('Enter your ZEI API Key from your company tools.', 'woocommerce-zei-wc'),
				'desc_tip'          => true,
				'default'           => ''
			),
            'api_secret' => array(
                'title'             => __('API Secret', 'woocommerce-zei-wc'),
                'type'              => 'text',
                'description'       => __('Enter your ZEI API Secret from your company tools.', 'woocommerce-zei-wc'),
                'desc_tip'          => true,
                'default'           => '')
		);
	}

	/**
	 * Santize our settings
	 * @see process_admin_options()
	 */
	public function sanitize_settings($settings) {
		if(isset($settings) && isset($settings['api_key']))
		    $settings['api_key'] = strtolower($settings['api_key']);
        if(isset($settings) && isset($settings['api_secret']))
            $settings['api_secret'] = strtolower($settings['api_secret']);
		return $settings;
	}

	/**
	 * Validate the API key
	 * @see validate_settings_fields()
	 */
	public function validate_api_key_field($key) {
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
    public function validate_api_secret_field($key) {
        $value = $_POST[$this->plugin_id.$this->id.'_'.$key];
        if(isset($value) && 45 !== strlen($value))
            WC_Admin_Settings::add_error(esc_html__('Looks like you made a mistake with the API Secret field. '
                .'Try again or contact us if the exact key isn\'t working.', 'woocommerce-zei-wc'));
        return $value;
    }
}

endif;
