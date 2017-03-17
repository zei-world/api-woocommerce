<?php
/**
 * API module for ZEI WC.
 *
 * @package  ZEI_WC_API
 * @author   Nazim from ZEI
 */

if(!defined('ABSPATH')) exit;
if(!class_exists('ZEI_WC_API')):

class ZEI_WC_API {

    private static $debug = false;

    private static $timeout = 2;

    private static $api = "zei.local/app_dev.php/api/v2/";
    //private static $api = "zero-ecoimpact.org/api/v2/";

    private static function request($path, $params = array()) {

        // WooCommerce options
        $options = get_option('woocommerce_zei-wc_settings');
        $id = $options['zei_api_key'];
        $secret = $options['zei_api_secret'];
        $scheme = $options['zei_api_https'] == "no" ? "http" : "https";

        $url = $scheme."://".self::$api.$path."?id=".$id."&secret=".$secret;
        foreach($params as $param => $value) $url .= "&".$param."=".$value;
        $response = file_get_contents($url, false, stream_context_create([
            'http' => [ 'method' => "GET", 'timeout' => self::$timeout, 'ignore_errors' => true ],
            'ssl' => [ "verify_peer" => false, "verify_peer_name" => false ]
        ]));

        if($response) {
            $data = json_decode($response, true);
            if(isset($data['success']) && $data['success']) return $data;
            if(self::$debug) var_dump('[ZEI] Server reached with an error', $data);
        } else if(self::$debug) {
            var_dump('[ZEI] Server not reached...');
        }

        return false;
    }

    static function getScriptUrl($b2c = true, $b2b = true) {
        if(!$b2b && !$b2c) return null;

        // WooCommerce options
        $options = get_option('woocommerce_zei-wc_settings');
        $id = $options['zei_api_key'];

        return "//".self::$api.'script'.
            '?id=' . $id .
            '&b2c=' . ($b2c ? 1 : 0).
            '&b2b=' . ($b2b ? 1 : 0).
            '&redirect_uri=http'.((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
                || $_SERVER['SERVER_PORT'] == 443 || isset($_SERVER['HTTP_X_FORWARDED_PROTO'])
                && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
                ? 's' : '').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
            ;
    }

    static function getOffersList() {
        $request = self::request('company/offers');
        if($request && $request['success'] && $request['message']) return $request['message'];
        return null;
    }

    static function validateOffer($offerId, $entity, $amount = 1) {
        if(preg_match("/^(u|c|o)\/[0-9]+$/", $entity)) {
            return self::request('validation/offer/'.$offerId.'/'.$entity, array('amount' => $amount));
        }
        if(self::$debug) var_dump('[ZEI] Entity syntax error : \"'.$entity.'\"');
        return false;
    }

    private static function rewardRequest($code, $confirm = 0) {
        return self::request('validation/reward/'.$code, array('confirm' => $confirm));
    }

    static function checkReward($code) {
        return self::rewardRequest($code);
    }

    static function validateReward($code) {
        return self::rewardRequest($code, 1);
    }

}

endif;
