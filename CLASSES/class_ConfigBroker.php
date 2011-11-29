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
     * A function to retrieve all configuration values stored in the config table of the database
     *
     * @return array Configuration values stored in an array($key=>$value) format
     */
    public function getAllConfig()
    {
        $handler = self::getHandler();
        if (!is_array($handler->arrConfig) or (is_array($handler->arrConfig) and count($handler->arrConfig) == 0)) {
            $db = Database::getConnection();
            try {
                $sql = "SELECT * FROM config";
                $query = $db->prepare($sql);
                $query->execute();
                // This section of code, thanks to code example here:
                // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
                if ($query->errorCode() != 0) {
                    throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'error'=>$query->errorInfo()), true), 1);
                }
                $handler->arrConfig = $query->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP);
                return $handler->arrConfig;
            } catch(Exception $e) {
                error_log($e);
                return false;
            }
        } else {
            return $handler->arrConfig;
        }
    }

    /**
     * A function to retrieve all configuration values stored in the appconfig values in the config files
     *
     * @return array Configuration values stored in an array($key=>$value) format
     */
    public function getAllAppConfig()
    {
        $handler = self::getHandler();
        if (!is_array($handler->arrLocalConfig) or (is_array($handler->arrLocalConfig) and count($handler->arrLocalConfig) == 0)) {
            include dirname(__FILE__) . '/../CONFIG/CONFIG_DEFAULT.php';
            if (isset($APPCONFIG)) {
                $handler->arrLocalConfig = $APPCONFIG;
                return $handler->arrLocalConfig;
            } else {
                return false;
            }
        } else {
            return $handler->arrLocalConfig;
        }
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
        $config = self::getAllConfig();
        if ($config == false) {
            return $strDefault;
        } elseif (is_array($config) and isset($config[$strKey]) and $config[$strKey] != '') {
            return $config[$strKey][0];
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
        $config = self::getAllAppConfig();
        if ($config == false) {
            return $strDefault;
        } elseif (is_array($config) and isset($config[$strKey]) and $config[$strKey] != '') {
            return $config[$strKey];
        } else {
            return $strDefault;
        }
    }
}
