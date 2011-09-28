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
class Database
{
    protected static $handler = null;
    protected $rw_db = null;
    protected $ro_db = null;

    /**
     * This function creates or returns an instance of this class.
     *
     * @return object $handler The Handler object
     */
    private static function getHandler()
    {
        if (self::$handler == null) {
            self::$handler = new self();
        }
        return self::$handler;
    }

    /**
     * This creates or returns the database object - depending on RO/RW requirements.
     *
     * @param boolean $RequireWrite Does this connection require write access?
     *
     * @return object PDO A PDO instance for the query.
     */
    public function getConnection($RequireWrite = false)
    {
        $self = self::getHandler();
        if (($RequireWrite == true AND $self->rw_db != null) OR ($RequireWrite == false AND $self->ro_db != null)) {
            if ($RequireWrite == true) {
                return $self->rw_db;
            } else {
                return $self->ro_db;
            }
        } else {
            include dirname(__FILE__) . '/../CONFIG/CONFIG_DEFAULT.php';
            try {
                if (!isset($RO_DSN)) {
                    $RequireWrite = true;
                    $self->ro_db = &$self->rw_db;
                }
                if ($RequireWrite == true) {
                    $self->rw_db = new PDO($RW_DSN['string'], $RW_DSN['user'], $RW_DSN['pass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                    return $self->rw_db;
                } else {
                    $self->ro_db = new PDO($RO_DSN['string'], $RO_DSN['user'], $RO_DSN['pass'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
                    return $self->ro_db;
                }
            } catch (Exception $e) {
                echo "Error connecting: " . $e->getMessage();
                die();
            }
        }
    }
}
