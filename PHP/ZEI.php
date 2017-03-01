<?php
/*
 *          @ @ @ @             d88888D d88888b d888888b       .d8b.  d8888b. d888888b
 *        @         @           YP  d8' 88'       `88'        d8' `8b 88  `8D   `88'
 *       @    @ @    @             d8'  88ooooo    88         88ooo88 88oodD'    88
 *      @   @     @    @          d8'   88~~~~~    88         88~~~88 88~~~      88
 *  @   @  @       @    @        d8' db 88.       .88.        88   88 88        .88.
 *  @   @  @   @    @   @       d88888P Y88888P Y888888P      YP   YP 88      Y888888P
 *   @   @  @ @    @   @
 *    @   @ @ @ @ @   @             Version 2.0 - PHP Edition
 *     @             @              Zero ecoimpact (https://zero-ecoimpact.org)
 *      @ @ @ @ @ @ @               Nazim from ZEI (nazim.lachter@zero-ecoimpact.org)
 */

/**
 * Class ZEI
 */
class ZEI {
    /**
     * Your Zero ecoimpact API id key
     * @var string
     */
    private $id = "";

    /**
     * Your Zero ecoimpact API secret key
     * @var string
     */
    private $secret = "";

    /**
     * Change this value to see errors when they appends
     * @var bool
     */
    static private $debug = true;

    /* ==============================================================================================================
     *            => FROM HERE YOU NO LONGER NEED TO EDIT THE FILE (UNLESS YOU KNOW WHAT YOU ARE DOING ;))
     * ============================================================================================================== */

    static private $api = "https://zero-ecoimpact.org/api/v2/";

    static private function request($path, $params = array()) {
        $request = new HttpRequest();
        $request->setUrl(self::$api.$path);
        $request->setMethod(HTTP_METH_GET);
        $request->setQueryData(array_merge(array('id' => self::$id, 'secret' => self::$secret), $params));
        try {
            $response = $request->send()->getBody();
            if($response['success']) return $response;
            if(self::$debug) var_dump('[ZEI] Server reached with an error : "'.$response['message'].'"');
        } catch(HttpException $e) {
            if(self::$debug) var_dump('[ZEI] Server not reached : "'.$e.'"');
        }
        return null;
    }

    static private function validateEntityString($entity) {
        $test = preg_match("/^(u|c|o)\/[0-9]+$/", $entity);
        if(!$test && self::$debug) var_dump('[ZEI] Entity syntax error : \"'.$entity.'\"');
        return $test;
    }

    /**
     * Returns HTML content for an object (on embed) HTML tag
     * Callback is automatically set with the right scheme (HTTP or HTTPS)
     * @param bool $b2b
     * @param bool $b2c
     * @param null $callback
     * @return string
     */
    static function getModuleUrl($b2c = true, $b2b = true, $callback = null) {
        if(!$b2b && !$b2c) return null;
        $params = '&b2c=' . ($b2c ? 1 : 0) . '&b2b=' . ($b2b ? 1 : 0);
        if($callback) {
            $params .= '&redirect_uri=http'.((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
                    || $_SERVER['SERVER_PORT'] == 443 || isset($_SERVER['HTTP_X_FORWARDED_PROTO'])
                    && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
                    ? 's' : '').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        }
        return self::$api.'module'.$params;
    }

    /**
     * Validate an offer with a PHP GET procedure
     * @param $offerId
     * @param $amount
     * @return bool
     */
    static function validateOffer($offerId, $entity, $amount = 1) {
        if(self::validateEntityString($entity)) {
            $response = self::request('validation/offer/'.$offerId.'/'.$entity, array('amount' => $amount));
            if(self::$debug) var_dump($response);
            return $response['success'];
        }
        return false;
    }

    static private function rewardRequest($code, $confirm = 0) {
        $response = self::request('validation/reward/'.$code, array('confirm' => $confirm));
        if(self::$debug) var_dump($response);
        return $response['success'];
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
