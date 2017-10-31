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

    private static $api = "api.zei-world.com/v3/";

    private static function request($path, $params = array()) {

        // WooCommerce options
        $options = get_option('woocommerce_zei-wc_settings');
        $id = $options['zei_api_key'];
        $secret = $options['zei_api_secret'];
        $scheme = array_key_exists('zei_api_https', $options) && $options['zei_api_https'] == "no" ? "http" : "https";

        $url = $scheme."://".self::$api.$path."?id=".$id."&secret=".$secret;
        foreach($params as $param => $value) $url .= "&".$param."=".$value;

        $original_errors = error_reporting();
        if(!self::$debug) error_reporting(0);

        if(function_exists('curl_version')) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::$timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, self::$timeout);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
        } else {
            $response = file_get_contents($url, false, stream_context_create([
                'http' => ['method' => "GET", 'timeout' => self::$timeout, 'ignore_errors' => true],
                'ssl' => ["verify_peer" => false, "verify_peer_name" => false]
            ]));
        }

        if(!self::$debug) error_reporting($original_errors);

        if($response) return json_decode($response, true);
        
        if(self::$debug) var_dump('[ZEI] Server not reached...');
        return null;
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
        $request = self::request('offers');
        if($request && $request['success'] && $request['message']) return $request['message'];
        return null; // Bad response or no success
    }

    static function validateOffer($offerId, $entity, $amount = 1) {
        $request = self::request('offers/'.$offerId.'/validate/'.$entity, array('units' => $amount));
        if($request && isset($request['success'])) return $request['success'];

        if(self::$debug) {
            var_dump('[ZEI] Invalid request reponse :');
            var_dump($request);
        }
        return null; // Invalid response
    }

    static function checkReward($code) {
        $request = self::request('rewardcodes/check/'.$code);
        if($request && isset($request['success'])) return $request;
        if(self::$debug) {
            var_dump('[ZEI] Invalid request reponse :');
            var_dump($request);
        }
        return null; // Invalid response
    }

    static function validateReward($code) {
        $request = self::request('rewardcodes/validate/'.$code);
        if($request && isset($request['success'])) return $request;
        if(self::$debug) {
            var_dump('[ZEI] Invalid request reponse :');
            var_dump($request);
        }
        return null; // Invalid response
    }

}

endif;
