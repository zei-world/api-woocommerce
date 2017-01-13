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
    private $api = "https://zero-ecoimpact.org/api/";

    private $token = null;

    private $timeout = 2;

    private function request($url, $header) {
        return json_decode(file_get_contents($this->api.$url, false, stream_context_create([
            'http' => [ 'method' => "GET", 'timeout' => $this->timeout, 'header' => $header ]
        ])), true);
    }

    public function requestToken($id, $secret) {
        $request = $this->request('token', "id: ".$id."\r\nsecret: ".$secret."\r\n");
        if($request && $request['success'] && $request['token']) {
            $this->token = $request['token'];
            return true;
        }
        return false;
    }

    public function getOffersList() {
        $request = $this->request('company/offers', "token: ".$this->token."\r\n");
        if($request && $request['success'] && $request['message']) return $request['message'];
        return null;
    }

    public function getRewardsList() {
        $request = $this->request('company/rewards', "token: ".$this->token."\r\n");
        if($request && $request['success'] && $request['message']) return $request['message'];
        return null;
    }
}

endif;
