/*
 *          @ @ @ @             d88888D d88888b d888888b       .d8b.  d8888b. d888888b
 *        @         @           YP  d8' 88'       `88'        d8' `8b 88  `8D   `88'
 *       @    @ @    @             d8'  88ooooo    88         88ooo88 88oodD'    88
 *      @   @     @    @          d8'   88~~~~~    88         88~~~88 88~~~      88
 *  @   @  @       @    @        d8' db 88.       .88.        88   88 88        .88.
 *  @   @  @   @    @   @       d88888P Y88888P Y888888P      YP   YP 88      Y888888P
 *   @   @  @ @    @   @
 *    @   @ @ @ @ @   @             Version 1.0 - NodeJS Edition
 *     @             @              Zero ecoimpact (https://zero-ecoimpact.org)
 *      @ @ @ @ @ @ @               Nazim from ZEI (nazim.lachter@zero-ecoimpact.org)
 */

var REQUEST = require("request"); // NodeJS Request
//var HTTP = require("http"); // NodeJS Native (alternative)

//  /---------- BE CAREFUL ---------\
//  |  This must be a SERVER class  |
//  |    NOT publicly accessible    |
//  \-------------------------------/

/*
 * Your Zero ecoimpact API id key
 */
var ID = "";

/*
 * Your Zero ecoimpact API secret key
 */
var SECRET = "";

/**
 * Change this value to update the maximum delay - in seconds - waiting for a Zero ecoimpact's servers response
 * Default timeout is set to 2 seconds
 */
var TIMEOUT = 2;

/**
 * Change this value to see errors when they appends
 */
var DEBUG = false;

/* ==============================================================================================================
 *            => FROM HERE YOU NO LONGER NEED TO EDIT THE FILE (UNLESS YOU KNOW WHAT YOU ARE DOING ;))
 * ============================================================================================================== */

var HOST = "zero-ecoimpact.org";

var API = "https://" + HOST + "/api/v2/";

/**
 * Class ZEI
 */
class ZEI {

	// NodeJS Request
	static request(path, params = {}, callback) {
		params['id'] = ID;
		params['secret'] = SECRET;
		var options = { method: 'GET', url: path, qs: params };
		REQUEST(options, function(error, response, body) {
			if(DEBUG) console.log(error, response, body);
  			if(callback) callback(body);
		});
	}

	// NodeJS Native (alternative)
	/*static request(path, params = {}, callback) {
		var path += "?id=" + ID + "&secret=" + SECRET;
		for(var key in params) path += "&" + key + "=" + params[key];
		var options = { 'method': 'GET', 'hostname': HOST, 'port': null, 'path': path, 'headers': {} };
		var request = HTTP.request(options, function(response) {
  			var chunks = [];
  			response.on('data', function(chunk) { chunks.push(chunk); });
  			response.on('end', function() {
    			var body = Buffer.concat(chunks);
    			if(DEBUG) console.log(body, chunks);
    			if(callback) callback(body);
  			});
		});
		request.end();
	}*/

	static rewardRequest(code, confirm = 0, callback) {
		this.request('validation/reward/' + code, { 'confirm': confirm }, callback);
	}

	// Returns JS content for the object (on embed) HTML tag with ZEI id
	static getScriptUrl(b2c = true, b2b = true) {
		if(!b2b && !b2c) return null;
        return API + 'script' + '?id=' + ID + '&b2c=' + (b2c ? 1 : 0) + '&b2b=' + (b2b ? 1 : 0);
	}

	// Validate an offer with a PHP GET procedure
	static validateOffer(offerId, entity, amount = 1, callback) {
		if(offerId && entity) {
			this.request('validation/offer/' + offerId + '/' + entity, { 'amount': amount }, callback);
		}
    }

	// Check a reward code with a PHP GET procedure
	static checkReward(code, callback) {
		this.rewardRequest(code, 0, callback);
	}

	// Validate a reward with a PHP GET procedure
	static validateReward(code, callback) {
        this.rewardRequest(code, 1, callback);
    }

}

module.exports = ZEI; // NodeJS modules
// export default ZEI; // Javascript export (alternative)

// Thank you for saving pandas ;)
