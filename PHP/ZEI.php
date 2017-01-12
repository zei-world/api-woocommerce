<?php
/*
 *          @ @ @ @             d88888D d88888b d888888b       .d8b.  d8888b. d888888b
 *        @         @           YP  d8' 88'       `88'        d8' `8b 88  `8D   `88'
 *       @    @ @    @             d8'  88ooooo    88         88ooo88 88oodD'    88
 *      @   @     @    @          d8'   88~~~~~    88         88~~~88 88~~~      88
 *  @   @  @       @    @        d8' db 88.       .88.        88   88 88        .88.
 *  @   @  @   @    @   @       d88888P Y88888P Y888888P      YP   YP 88      Y888888P
 *   @   @  @ @    @   @
 *    @   @ @ @ @ @   @             Version 1.1 - PHP Edition
 *     @             @              Zero ecoimpact (https://zero-ecoimpact.org)
 *      @ @ @ @ @ @ @
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
     * Use Javascript and open a window for logging (/!\ your POST data will be lost)
     * @var bool
     */
    private $window = true;

    /**
     * Default displayed language ("fr" or "en"), can be changed in constructor if changes
     * @var null|string
     */
    private $locale = "fr";

    /**
     * Change this value to update the maximum delay - in seconds - waiting for a Zero ecoimpact's servers response
     * Default timeout is set to 2 seconds
     * @var int
     */
    private $timeout = 2;

    //
    /**
     * Change this value to see errors when they appends
     * @var bool
     */
    private $debug = false;

    /* ==============================================================================================================
     *            => FROM HERE YOU NO LONGER NEED TO EDIT THE FILE (UNLESS YOU KNOW WHAT YOU ARE DOING ;))
     * ============================================================================================================== */

    /**
     * @var string
     */
    private $api = "https://zero-ecoimpact.org/api/";

    /**
     * @var null
     */
    private $token = null;

    /**
     * @var string
     */
    private $error = "";

    /**
     * Constructor
     * @param null $locale
     */
    function __construct($locale = null) {
        if($locale) $this->locale = $locale;
    }

    /**
     * Request a token with a PHP GET procedure
     * requestToken()
     * @return string token or null
     */
    function requestToken() {
        $request = json_decode(file_get_contents($this->api.'token', false, stream_context_create([
            'http'=>[
                'method' => "GET", 'timeout' => $this->timeout,
                'header' => "id: ".$this->id."\r\nsecret: ".$this->secret."\r\n"]
        ])), true);
        if($request)
            if($request['success'])
                if($request['token']) {
                    $this->token = $request['token'];
                    return $request['token'];
                } else $this->setError('Token missing into the request');
            else $this->setError('Server reached with an error : "'.$request['message'].'"');
        else $this->setError('Server not reached, error during initial request (Zero ecoimpact server\'s down ?)');
        if($this->debug) var_dump($this->error);
        return null;
    }

    /**
     * Returns HTML content for an object (on embed) HTML tag
     * Callback is automatically set with the right scheme (HTTP or HTTPS)
     * @param bool $b2b
     * @param bool $b2c
     * @param null $callback
     * @return string
     */
    function getModuleUrl($b2b = true, $b2c = true, $callback = null) {
        if($this->error) return '';
        // Set id
        $params = '?token='.$this->token;

        // Is B2B or/and B2C
        $params .= '&b2b=' . ($b2b ? 1 : 0) . '&b2c=' . ($b2c ? 1 : 0);

        // Set callback
        if($callback === null && !$this->window) {
            $params .= '&redirect_uri=http'.((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
                    || $_SERVER['SERVER_PORT'] == 443 || isset($_SERVER['HTTP_X_FORWARDED_PROTO'])
                    && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
                    ? 's' : '').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        }

        // URL for object module
        return $this->api.'module'.$params;
    }

    /**
     * Validate an offer with a PHP GET procedure
     * @param $token
     * @param $offerId
     * @param $amount
     * @return bool
     */
    function validateOffer($offerId, $amount) {
        $request = json_decode(file_get_contents($this->api.'company/offer', false, stream_context_create([
            'http'=>[
                'method' => "GET", 'timeout' => $this->timeout,
                'header' => "token: ".$this->token."\r\noffer: ".$offerId."\r\namount: ".$amount."\r\nlocale: ".$this->locale."\r\n"]
        ])), true);
        if($request)
            if($request['success'])
                return true;
            else $this->setError('Server reached with an error : "'.$request['message'].'"');
        else $this->setError('Server not reached, error during initial request (Zero ecoimpact server\'s down ?)');
        return false;
    }

    /**
     * Set the error var only if you enabled debug
     * @param $message
     */
    private function setError($message) {
        if($this->debug) $this->error = $message;
    }

    /**
     * Set token
     * @param $token
     */
    function setToken($token) {
        $this->token = $token;
    }

    /**
     * Get token
     */
    function getToken() {
        return $this->token;
    }
}

// Thank you for saving pandas ;)
