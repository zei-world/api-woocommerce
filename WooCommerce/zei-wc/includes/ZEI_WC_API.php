<?php
/**
 * API module for ZEI WC.
 *
 * @package  ZEI_WC_API
 * @author   Nazim from ZEI
 */

if(!class_exists('ZEI_WC_API')):

class ZEI_WC_API {
    private $api = "http://zei.local/app_dev.php/api/";

    private $token = null;

    private $timeout = 2;

    public function requestToken($id, $secret) {
        $request = json_decode(file_get_contents($this->api.'token', false, stream_context_create([
            'http'=>[
                'method' => "GET", 'timeout' => $this->timeout,
                'header' => "id: ".$id."\r\nsecret: ".$secret."\r\n"]
        ])), true);
        if($request && $request['success'] && $request['token']) {
            $this->token = $request['token'];
            return true;
        }
        return false;
    }
}

endif;
