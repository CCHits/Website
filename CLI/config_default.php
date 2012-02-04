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
* @link     http://code.cchits.net Developers Web Site
* @link     http://gitorious.net/cchits-net Version Control Service
*/
/**
* This class provides all the Config functions
*
* @category Default
* @package  Brokers
* @author   Jon Spriggs <jon@sprig.gs>
* @license  http://www.gnu.org/licenses/agpl.html AGPLv3
* @link     http://cchits.net Actual web service
* @link     http://code.cchits.net Developers Web Site
* @link     http://gitorious.net/cchits-net Version Control Service
*/

class Configuration
{
    protected static $config_handler = null;

    private $username       = 'admin';
    private $password       = 'admin';
    private $url_base       = 'cchits.net/api';
    private $media_base     = '/media';
    private $protocol       = 'http';
    private $api            = '';
    private $WorkingDir     = '';
    private $StaticDir      = '';
    private $StatusNet      = '';
    private $StatusNetProto = 'http';
    private $StatusNetUrl   = 'cchits.net/comments/api/statuses/';
    private $StatusNetUser  = '';
    private $StatusNetPass  = '';

    /**
    * An internal function to make this a singleton
    *
    * @return object This class by itself.
    */
    private static function getHandler()
    {
        if (self::$config_handler == null) {
            self::$config_handler = new self();
        }
        return self::$config_handler;
    }

    /**
     * Import all the appropriate details here and parse them into formats we need.
     *
     * @return void
     */
    function __construct()
    {
        $this->WorkingDir = dirname(__FILE__) . '/TEMP';
        $this->StaticDir = dirname(__FILE__) . '/STATIC';

        if (file_exists(dirname(__FILE__) . "/config_local.php")) {
            include dirname(__FILE__) . "/config_local.php";
        }

        $this->api = $this->protocol . '://' . $this->username . ':' . $this->password . '@' . $this->url_base;
        $this->StatusNet = $this->StatusNetProto . '://' . $this->StatusNetUser . ':' . $this->StatusNetPass . '@' . $this->StatusNetUrl;
    }

    /**
     * Return the value of the API variable
     *
     * @return string API path.
     */
    function getAPI()
    {
        $handler = self::getHandler();
        return $handler->api;
    }

    /**
     * Return the value of the WorkingDir variable
     *
     * @return string Working Directory path
     */
    function getWorkingDir()
    {
        $handler = self::getHandler();
        return $handler->WorkingDir;
    }

    /**
     * Return the value of the StaticDir variable
     *
     * @return string Static Directory path
     */
    function getStaticDir()
    {
        $handler = self::getHandler();
        return $handler->StaticDir;
    }
    
    /**
     * Return the value of the StatusNet variable
     *
     * @return string StatusNet URL
     */
    function getStatusNet()
    {
        $handler = self::getHandler();
        return $handler->StatusNet;
    }

    /**
     * Return the value of the StatusNetUser variable
     *
     * @return string StatusNetUser Username
     */
    function getStatusNetUser()
    {
        $handler = self::getHandler();
        return $handler->StatusNetUser;
    }

}