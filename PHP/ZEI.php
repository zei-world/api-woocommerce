<?php
/*
 *          @ @ @ @             d88888D d88888b d888888b       .d8b.  d8888b. d888888b
 *        @         @           YP  d8' 88'       `88'        d8' `8b 88  `8D   `88'
 *       @    @ @    @             d8'  88ooooo    88         88ooo88 88oodD'    88
 *      @   @     @    @          d8'   88~~~~~    88         88~~~88 88~~~      88
 *  @   @  @       @    @        d8' db 88.       .88.        88   88 88        .88.
 *  @   @  @   @    @   @       d88888P Y88888P Y888888P      YP   YP 88      Y888888P
 *   @   @  @ @    @   @
 *    @   @ @ @ @ @   @             Version 1.0 - PHP Edition
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
     * Request a token with a PHP GET procedure
     * requestToken()
     */
    function requestToken() {
        $request = json_decode(file_get_contents($this->api.'token', false, stream_context_create([
            'http'=>[
                'method' => "GET", 'timeout' => $this->timeout,
                'header' => "id: ".$this->id."\r\nsecret: ".$this->secret."\r\n"]
        ])), true);
        if($request)
            if($request['success'])
                if($request['token'])
                    $this->token = $request['token'];
                else $this->setError('Token missing into the request');
            else $this->setError('Server reached with an error : "'.$request['message'].'"');
        else $this->setError('Server not reached, error during initial request (Zero ecoimpact server\'s down ?)');
    }

    /**
     * Returns HTML content for an object (on embed) HTML tag
     * Callback is automatically set with the right scheme (HTTP or HTTPS)
     * @return string
     */
    function getModule() {
        if(!$this->token)
            return '" style="display:none"></object>'.$this->error.'<object style="display:none'; // Owwwwwyeaaahhhhhhhh
        $params = '?t='.$this->token;
        if(!$this->window) {
            $params .= '&c=http'.((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'
                    || $_SERVER['SERVER_PORT'] == 443 || isset($_SERVER['HTTP_X_FORWARDED_PROTO'])
                    && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
                    ? 's' : '').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        }
        return $this->api.'module'.$params;
    }

    /**
     * Validate an offer with a PHP GET procedure
     * @param $token
     * @param $offerId
     * @return bool
     */
    function validateOffer($token, $offerId) {
        $request = json_decode(file_get_contents($this->api.'company/offer', false, stream_context_create([
            'http'=>[
                'method' => "GET", 'timeout' => $this->timeout,
                'header' => "token: ".$token."\r\noffer: ".$offerId."\r\nlocale: ".$this->locale."\r\n"]
        ])), true);
        if($request)
            if($request['success'])
                return true;
            else $this->setError('Server reached with an error : "'.$request['message'].'"');
        else $this->setError('Server not reached, error during initial request (Zero ecoimpact server\'s down ?)');
        return false;
    }
}

/**
 * @return string
 */
function deploy() {
    $zei = new ZEI();
    $zei->requestToken();
    return $zei->getModule();
}

// Thank you for saving pandas ;)
