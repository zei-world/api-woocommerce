<?php
/**
 * ZEI WC. debugger
 *
 * @package  ZEI_WC_Debugger
 * @author   Nazim from ZEI
 */

if(!defined('ABSPATH')) exit;
if(!class_exists('ZEI_WC_Debugger')):

include_once 'ZEI_WC_API.php';

class ZEI_WC_Debugger {
    static $isLoaded = false;
    static $hash = null;

    public function __construct() {
        $options = get_option('woocommerce_zei-wc_settings');
		if(array_key_exists('zei_api_debugger', $options) && $options['zei_api_debugger'] === "yes") {
            self::$isLoaded = true;
        }

        if(self::$isLoaded) {
            if(array_key_exists('zei_debugger' , $_COOKIE)) {
                self::$hash = $_COOKIE['zei_debugger'];
            } else {
                self::$hash = md5(uniqid(rand(), true));
                setcookie('zei_debugger', self::$hash);
                self::send("Init");
            }
        }
    }

    public static function send($title, $data = null) {
        $content = array('hash' => self::$hash, 'message' => $title . ($data !== null ? (" " . json_encode($data)) : ""));
        if(array_key_exists('zei', $_COOKIE)) {
            $content['user'] = $_COOKIE['zei'];
        }
        if(self::$isLoaded) {
            ZEI_WC_API::request('/v4/debugger', $content);
        }
    }

}

endif;
