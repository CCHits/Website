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
 * This class is the connection factory, and is based on a code snippet found
 * on StackOverflow.com
 *
 * @category Default
 * @package  Helpers
 * @author   Jon Raphaelson <unknown@unknown.com>
 * @license  Unknown - suspect Public Domain
 * @link     http://stackoverflow.com/users/27546/jon-raphaelson
 */
class CF
{
    protected static $factory = null;
    protected $db = null;

    /**
     * This function creates or returns an instance of the factory.
     *
     * @return object $factory The Factory object
     */
    public static function getFactory()
    {
        Debug::Log(get_class() . "::getFactory()", "VERBOSE");
        if (self::$factory == null) {
            Debug::Log("Creating new Connection Factory (CF)", "VERBOSE");
            self::$factory = new CF();
        }
        return self::$factory;
    }

    /**
     * Because we're using a factory here, if we need to do cool stuff with
     * creating connections to the database - this is where we do it.
     *
     * @return object PDO A PDO instance for the factory.
     */
    public function getConnection()
    {
        if ($this->db == null) {
            include_once dirname(__FILE__) . '/../CONFIG/CONFIG_DEFAULT.php';
            try {
                $this->db = new PDO($DSN['string'], $DSN['user'], $DSN['pass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
            } catch (Exception $e) {
                echo "Error connecting: " . $e->getMessage();
                die();
            }
        }
        return $this->db;
    }
}
