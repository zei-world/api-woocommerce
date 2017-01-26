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
    private static $URLs = array(
        "https://zero-ecoimpact.org/api/",
        "http://zero-ecoimpact.org/api/"
    );

    private static function request($url, $headers) {
        $header = "";
        foreach($headers as $k => $v) $header .= $k.": ".$v."\r\n";
        $response = null;
        foreach(self::$URLs as $main) {
            $response = file_get_contents($main.$url, false, stream_context_create(array(
                'http' => array('method' => "GET", 'timeout' => 10, 'header' => $header),
                'ssl' => array("verify_peer" => false, "verify_peer_name" => false)
            )));
            if($response) break;
        }
        if(!$response) return null;
        return json_decode($response, true);
    }

    public static function requestToken($id, $secret) {
        $request = self::request('token', array('id' => $id, 'secret' => $secret));
        if($request && $request['success'] && $request['token']) return $request['token'];
        return null;
    }

    public static function getToken($start = true, $force = false) {
        if($start && !session_id()) session_start();
        if(!$force && isset($_SESSION['zeiToken'])) return $_SESSION['zeiToken'];

        $options = get_option('woocommerce_zei-wc_settings');
        $token = self::requestToken($options['zei_api_key'], $options['zei_api_secret']);

        if($token) {
            $_SESSION['zeiToken'] = $token;
            return $token;
        }
        return null;
    }

    public static function getOffersList($token) {
        $request = self::request('company/offers', array('token' => $token));
        if($request['message'] == '[OFFERS] Token has been used or not exist') self::getToken(false, true);
        if($request && $request['success'] && $request['message']) return $request['message'];
        return null;
    }

    public static function getRewardsList($token) {
        $request = self::request('company/rewards', array('token' => $token));
        if($request['message'] == '[REWARDS] Token has been used or not exist') self::getToken(false, true);
        if($request && $request['success'] && $request['message']) return $request['message'];
        return null;
    }

    public static function getModuleUrl($token, $b2b, $b2c) {
        $params = '?token='.$token;

        // Is B2B or/and B2C
        $params .= '&b2b=' . ($b2b ? 1 : 0) . '&b2c=' . ($b2c ? 1 : 0);

        // URL for object module
        return self::$URLs[0].'module'.$params;
    }

    public static function validateOffer($token, $offerId, $amount) {
        $r = self::request('company/offer', array('token' => $token, 'offer' => $offerId, 'amount' => $amount));
        if($r['message'] == '[OFFER] Token has been used or not exist') self::getToken(false, true);
        return $r['success'];
    }

    public static function validateReward($token, $rewardId, $amount) {
        $r = self::request('company/reward', array('token' => $token, 'reward' => $rewardId, 'amount' => $amount));
        if($r['message'] == '[REWARD] Token has been used or not exist') self::getToken(false, true);
        return $r['success'];
    }

    public static function codesValidate($code) {
        $request = self::request('company/codes', array('token' => self::getToken(false), 'code' => $code));
        if($request['message'] == '[CODES] Token has been used or not exist') self::getToken(false, true);
        if($request && $request['success'] && $request['message']) return $request['message'];
        return null;
    }
}

endif;
