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

class CURL
{

    const FORM_URLENCODED = "application/x-www-form-urlencoded";

    private $_curl = null;

    /**
     * Init.
     * 
     * @param string $url The url.
     * 
     * @return CURL
     */
    public static function init($url = "") 
    {
        return new CURL($url);
    }

    /**
     * Constructor.
     * 
     * @param string $url The url.
     */
    public function __construct($url) 
    {
        $this->_curl = curl_init($url);
        curl_setopt($this->_curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($this->_curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, true);  
    }

    /**
     * Exec call.
     * 
     * @return mixed
     */
    private function exec() 
    {
        $response = curl_exec($this->_curl);
        curl_close($this->_curl);
        return $response;
    }

    /**
     * Get.
     * 
     * @return mixed
     */
    public function get() 
    {
        $this->setPost(false);
        $response = $this->exec();
        return $response;
    }

    /**
     * Post.
     * 
     * @param array $params the post parameters.
     * 
     * @return mixed
     */
    public function post($params = array()) 
    {
        $query = "";
        foreach ($params as $key => $value) {
            if ($query != "") {
                $query .= "&";
            }
            $query .= $key . "=" . $value;
        }
        if ($query != "") {
            curl_setopt($this->_curl, CURLOPT_POSTFIELDS, $query);
        }
        $this->setPost(true);
        $response = $this->exec($this->_curl);
        return $response;
    }

    /**
     * Set the "post" option.
     * 
     * @param bool $post whether to set (true) or reset (false) the "post" option.
     * 
     * @return void
     */
    public function setPost($post = true) 
    {
        curl_setopt($this->_curl, CURLOPT_POST, $post);
    }

    /**
     * Set the content type
     * 
     * @param string $contentType the mime type.
     * 
     * @return void
     */
    public function setContentType($contentType) 
    {
        curl_setopt($this->_curl, CURLOPT_HTTPHEADER, array("Content-Type: " . $contentType));
    }

    /**
     * Forces the content type to 'application/x-www-form-urlencoded'
     * 
     * @return void
     */
    public function setContentTypeFormUrlEncoded() 
    {
        $this->setContentType(self::FORM_URLENCODED);
    }
}
