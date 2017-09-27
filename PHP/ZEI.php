<?php
/*
 *          @ @ @ @             d88888D d88888b d888888b       .d8b.  d8888b. d888888b
 *        @         @           YP  d8' 88'       `88'        d8' `8b 88  `8D   `88'
 *       @    @ @    @             d8'  88ooooo    88         88ooo88 88oodD'    88
 *      @   @     @    @          d8'   88~~~~~    88         88~~~88 88~~~      88
 *  @   @  @       @    @        d8' db 88.       .88.        88   88 88        .88.
 *  @   @  @   @    @   @       d88888P Y88888P Y888888P      YP   YP 88      Y888888P
 *   @   @  @ @    @   @
 *    @   @ @ @ @ @   @             Version 2.1 - PHP Edition
 *     @             @              Zei (https://zei-world.com)
 *      @ @ @ @ @ @ @               Nazim from Zei (nazim.lachter@zero-ecoimpact.org)
 */

/**
 * Class ZEI
 */
class ZEI {
    /**
     * Your Zei API id key
     * @var string
     */
    private static $id = "";

    /**
     * Your Zei API secret key
     * @var string
     */
    private static $secret = "";

    /**
     * Change this value to update the maximum delay - in seconds - waiting for a Zei's servers response
     * Default timeout is set to 2 seconds
     * @var int
     */
    private static $timeout = 2;

    /**
     * Change this value to see errors when they appends
     * @var bool
     */
    private static $debug = false;

    /* ==============================================================================================================
     *            => FROM HERE YOU NO LONGER NEED TO EDIT THE FILE (UNLESS YOU KNOW WHAT YOU ARE DOING ;))
     * ============================================================================================================== */

    private static $api = "https://zei-world.com/api/v2/";

    private static function request($path, $params = array()) {
        $url = self::$api.$path."?id=".self::$id."&secret=".self::$secret;
        foreach($params as $param => $value) $url .= "&".$param."=".$value;

        $response = file_get_contents($url, false, stream_context_create([
            'http' => [ 'method' => "GET", 'timeout' => self::$timeout, 'ignore_errors' => true ],
            'ssl' => [ "verify_peer" => false, "verify_peer_name" => false ]
        ]));

        if($response) {
            $data = json_decode($response, true);
            if(isset($data['success']) && $data['success']) return true;
            if(self::$debug) var_dump('[ZEI] Server reached with an error', $data);
        } else if(self::$debug) {
            var_dump('[ZEI] Server not reached...');
        }

        return false;
    }

    /**
     * Returns JS content for the object (on embed) HTML tag with ZEI id
     * @param bool $b2b
     * @param bool $b2c
     * @param null $callback
     * @return string
     */
    static function getScriptUrl($b2c = true, $b2b = true) {
        if(!$b2b && !$b2c) return null;
        return self::$api.'script'.
            '?id=' . self::$id .
            '&b2c=' . ($b2c ? 1 : 0).
            '&b2b=' . ($b2b ? 1 : 0).
            '&redirect_uri=http'.((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
                || $_SERVER['SERVER_PORT'] == 443 || isset($_SERVER['HTTP_X_FORWARDED_PROTO'])
                && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
                ? 's' : '').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']
        ;
    }

    /**
     * Validate an offer with a PHP GET procedure
     * @param $offerId
     * @param $amount
     * @return bool
     */
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

    /**
     * Check a reward code with a PHP GET procedure
     * @param $code
     * @return bool
     */
    static function checkReward($code) {
        return self::rewardRequest($code);
    }

    /**
     * Validate a reward with a PHP GET procedure
     * @param $code
     * @return bool
     */
    static function validateReward($code) {
        return self::rewardRequest($code, 1);
    }
}

// Thank you for saving pandas ;)
