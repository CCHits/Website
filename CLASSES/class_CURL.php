<?php
/**
 * CCHits.net is a website designed to promote Creative Commons Music,
 * the artists who produce it and anyone or anywhere that plays it.
 * These files are used to generate the site.
 *
 * PHP version 5
 *
 * @category Default
 * @package  CCHitsClass
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */
/**
 * This class provides a wrapper around curl
 *
 * @category Default
 * @package  Utilities
 * @author   Yannick Mauray <yannick.mauray@euterpia-radio.fr>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */

class CURL {

	const FORM_URLENCODED = "application/x-www-form-urlencoded";

	private $curl = null;

	public static function init($url = "") {
		return new CURL($url);
	}

	public function __construct($url) {
        $this->curl = curl_init($url);
        curl_setopt($this->curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);  
	}

	private function exec() {
		$response = curl_exec($this->curl);
		curl_close($this->curl);
		return $response;
	}

	public function get() {
		$this->setPost(false);
		$response = $this->exec();
		return $response;
	}

	public function post($params = array()) {
		$query = "";
		foreach ($params as $key => $value) {
			if ($query != "") $query .= "&";
			$query .= $key . "=" . $value;
		}
		if ($query != "") {
        	curl_setopt($this->curl, CURLOPT_POSTFIELDS, $query);
        }
		$this->setPost(true);
        $response = $this->exec($this->curl);
        return $response;
	}

	public function setPost($post = true) {
		curl_setopt($this->curl, CURLOPT_POST, $post);
	}

	public function setContentType($contentType) {
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array("Content-Type: " . $contentType));
	}

	public function setContentTypeFormUrlEncoded() {
		$this->setContentType(self::FORM_URLENCODED);
	}

}
