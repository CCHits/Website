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

class ConfigBroker
{
    protected static $config_handler = null;
    protected $arrConfig = array();
    protected $arrLocalConfig = array();

    /**
     * An internal function to make this a singleton
     *
     * @return object This class by itself.
     */
    private static function getHandler()
    {
        if (self::$config_handler == null) {
            self::$config_handler = new ConfigBroker();
        }
        return self::$config_handler;
    }

    /**
     * Return either the established configuration item, or the default
     *
     * @param string $strKey     The key we're searching for
     * @param string $strDefault The default response if it's not in the database
     *
     * @return string The value we're searching for, or the default if not found.
     */
    public function getConfig($strKey = "", $strDefault = "")
    {
        $handler = self::getHandler();
        if (!is_array($handler->arrConfig) or (is_array($handler->arrConfig) and count($handler->arrConfig) == 0)) {
            $db = Database::getConnection();
            try {
                $sql = "SELECT * FROM config";
                $query = $db->prepare($sql);
                $query->execute();
                $handler->arrConfig = $query->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP);
            } catch(Exception $e) {
                return $strDefault;
            }
        }
        if (isset($handler->arrConfig[$strKey])) {
            return $handler->arrConfig[$strKey][0];
        } else {
            return $strDefault;
        }
    }

    /**
     * Return either the established application-level configuration item, or the default
     *
     * @param string $strKey     The key we're searching for
     * @param string $strDefault The default response if it's not in the database
     *
     * @return string The value we're searching for, or the default if not found.
     */
    public function getAppConfig($strKey = "", $strDefault = "")
    {
        $handler = self::getHandler();
        if (!is_array($handler->arrLocalConfig) or (is_array($handler->arrLocalConfig) and count($handler->arrLocalConfig) == 0)) {
            include dirname(__FILE__) . '/../CONFIG/CONFIG_DEFAULT.php';
            if (isset($APPCONFIG)) {
                $handler->arrLocalConfig = $APPCONFIG;
            }
        }
        if (isset($handler->arrLocalConfig[$strKey])) {
            return $handler->arrLocalConfig[$strKey];
        } else {
            return $strDefault;
        }
    }
}
