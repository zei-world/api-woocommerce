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
    private static $api = "https://zero-ecoimpact.org/api/";

    private static function request($url, $header) {
        return json_decode(file_get_contents(self::$api.$url, false, stream_context_create([
            'http' => [ 'method' => "GET", 'timeout' => 2, 'header' => $header ],
            'ssl' => [ "verify_peer" => false, "verify_peer_name" => false ]
        ])), true);
    }

    public static function requestToken($id, $secret) {
        $request = self::request('token', "id: ".$id."\r\nsecret: ".$secret."\r\n");
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
        $request = self::request('company/offers', "token: ".$token."\r\n");
        if($request['message'] == '[OFFERS] Token has been used or not exist') self::getToken(false, true);
        if($request && $request['success'] && $request['message']) return $request['message'];
        return null;
    }

    public static function getRewardsList($token) {
        $request = self::request('company/rewards', "token: ".$token."\r\n");
        if($request['message'] == '[REWARDS] Token has been used or not exist') self::getToken(false, true);
        if($request && $request['success'] && $request['message']) return $request['message'];
        return null;
    }

    public static function getModuleUrl($token, $b2b, $b2c) {
        $params = '?token='.$token;

        // Is B2B or/and B2C
        $params .= '&b2b=' . ($b2b ? 1 : 0) . '&b2c=' . ($b2c ? 1 : 0);

        // URL for object module
        return self::$api.'module'.$params;
    }

    public static function validateOffer($token, $offerId, $amount) {
        $r = self::request('company/offer', "token: ".$token."\r\noffer: ".$offerId."\r\namount: ".$amount."\r\n");
        if($r['message'] == '[OFFER] Token has been used or not exist') self::getToken(false, true);
    }

    public static function validateReward($token, $rewardId, $amount = null) {
        $r = self::request('company/reward', "token: ".$token."\r\nreward: ".$rewardId."\r\namount: ".$amount."\r\n");
        if($r['message'] == '[REWARD] Token has been used or not exist') self::getToken(false, true);
    }

    public static function codesValidate($code) {
        $request = self::request('company/codes', "token: ".self::getToken(false)."\r\ncode: ".$code."\r\n");
        if($request['message'] == '[CODES] Token has been used or not exist') self::getToken(false, true);
        if($request && $request['success'] && $request['message']) return $request['message'];
        return null;
    }
}

endif;
