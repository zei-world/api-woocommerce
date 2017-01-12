<?php
/**
 * Plugin Name: Zero ecoimpact - WooCommerce
 * Plugin URI: https://zero-ecoimpact.org
 * Description: WooCommerce extension for Zero ecoimpact API
 * Version: 1.0
 * Author: Nazim from ZEI
 * Author URI: https://zero-ecoimpact.org/fr/profile/1
 * Requires at least: 4.4
 * Tested up to: 4.7
 *
 * Text Domain: zei-wc
 */

if(!class_exists('ZEI_WC')):

class ZEI_WC {
	/**
	* Construct the plugin.
	*/
	public function __construct() {
		add_action('plugins_loaded', array($this, 'init'));
	}

	/**
	* Initialize the plugin.
	*/
	public function init() {
		if(class_exists('WC_Integration')) { // Checks if WooCommerce is installed
			include_once 'includes/ZEI_WC_Integration.php';
			add_filter('woocommerce_integrations', array($this, 'add_integration'));
		}
	}

	/**
	 * Add a new integration to WooCommerce.
	 */
	public function add_integration($integrations) {
		$integrations[] = 'ZEI_WC_Integration';
		return $integrations;
	}
}

$ZEI_WC = new ZEI_WC(__FILE__);

endif;
