<?php
/**
 * TODO: Merge this config_default into the AppConfig aspects of the main site
 */
class Configuration
{
    protected static $config_handler = null;

    private $username   = 'admin';
    private $password   = 'admin';
    private $url_base   = 'cchits.net/api';
    private $media_base = '/media';
    private $protocol   = 'http';
    private $api        = '';
    private $WorkingDir = '';
    private $StaticDir  = '';

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

    function __construct()
    {
        if (file_exists(dirname(__FILE__) . "/config_local.php")) {
            include dirname(__FILE__) . "/config_local.php";
        }

        $this->api = $this->protocol . '://' . $this->username . ':' . $this->password . '@' . $this->url_base;
        $this->WorkingDir = dirname(__FILE__) . '/TEMP';
        $this->StaticDir = dirname(__FILE__) . '/STATIC';
    }

    function getAPI()
    {
        $handler = self::getHandler();
        return $handler->api;
    }

    function getWorkingDir()
    {
        $handler = self::getHandler();
        return $handler->WorkingDir;
    }

    function getStaticDir()
    {
        $handler = self::getHandler();
        return $handler->StaticDir;
    }
}