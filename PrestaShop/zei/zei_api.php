<?php

if(!defined('_PS_VERSION_')) exit;

class zei_api {
    private static $URLs = array(
        "https" => "https://zero-ecoimpact.org/api/",
        "http" => "http://zero-ecoimpact.org/api/"
    );

    private static function request($url, $headers) {
        $header = "";
        foreach($headers as $k => $v) $header .= $k.": ".$v."\r\n";
        $response = null;

        foreach(self::$URLs as $current) {
            $response = file_get_contents($current.$url, false, stream_context_create(array(
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

    public static function getToken($id, $secret) {
        //if(!isset($_SESSION)) session_start();
        if(isset($_SESSION['zeiToken'])) return $_SESSION['zeiToken'];
        $token = self::requestToken($id, $secret);
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

}