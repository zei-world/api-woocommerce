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
            'http' => [ 'method' => "GET", 'timeout' => 2, 'header' => $header ]
        ])), true);
    }

    public static function requestToken($id, $secret) {
        $request = self::request('token', "id: ".$id."\r\nsecret: ".$secret."\r\n");
        if($request && $request['success'] && $request['token']) return $request['token'];
        return null;
    }

    public static function getOffersList($token) {
        $request = self::request('company/offers', "token: ".$token."\r\n");
        if($request && $request['success'] && $request['message']) return $request['message'];
        return null;
    }

    public static function getRewardsList($token) {
        $request = self::request('company/rewards', "token: ".$token."\r\n");
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
        //var_dump($r);die;
    }
}

endif;
