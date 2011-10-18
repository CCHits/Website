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
 * This class knows how to do everything with User Objects
 *
 * @category Default
 * @package  Brokers
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class UserBroker
{
    protected static $handler = null;
    protected $thisUser = null;
    protected $arrUsers = array();

    /**
     * An internal function to make this a singleton
     *
     * @return object This class by itself.
     */
    private static function getHandler()
    {
        if (self::$handler == null) {
            self::$handler = new self();
        }
        return self::$handler;
    }


    /**
     * This function gets any details about the acting User
     *
     * @return object|false User object or false if the authentication failed
     */
    function getUser()
    {
        $objSelf = self::getHandler();
        if ($objSelf->thisUser != null) {
            return $objSelf->thisUser;
        }
        UI::start_session();
        if (isset($_SESSION['cookie']) AND $_SESSION['cookie'] != '') {
            $field = "strCookieID";
            $param = $_SESSION['cookie'];
        } elseif (isset($_SESSION['OPENID_AUTH']) AND $_SESSION['OPENID_AUTH'] != false) {
            $field = "strOpenID";
            $param = $_SESSION['OPENID_AUTH']['url'];
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION']) and $_SERVER['HTTP_AUTHORIZATION'] != '') {
            $auth_params = explode(":" , base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
            $username = $auth_params[0];
            unset($auth_params[0]);
            $password = sha1(implode('',$auth_params));
            $field = "sha1Pass";
            $param = "{$username}:{$password}";
        } elseif (isset($_SERVER['PHP_AUTH_USER']) and isset($_SERVER['PHP_AUTH_PW'])) {
            $username = $_SERVER['PHP_AUTH_USER'];
            $password = sha1($_SERVER['PHP_AUTH_PW']);
            $field = "sha1Pass";
            $param = "{$username}:{$password}";
        } else {
            $objSelf->thisUser = new NewUserObject();
            return $objSelf->thisUser;
        }

        if (isset($field) and isset($param)) {
            try {
                $db = Database::getConnection();
                $sql = "SELECT * FROM users WHERE $field = ? LIMIT 1";
                $query = $db->prepare($sql);
                $query->execute(array($param));
                $objSelf->thisUser = $query->fetchObject('UserObject');
                if ($objSelf->thisUser == false) {
                    $objSelf->thisUser = new NewUserObject($param);
                    if (isset($_SESSION['OPENID_AUTH']['email'])) {
                        $objSelf->thisUser->set_strEMail($_SESSION['OPENID_AUTH']['email']);
                        $objSelf->thisUser->write();
                    }
                    return $objSelf->thisUser;
                } else {
                    $objSelf->thisUser->set_datLastSeen(date("Y-m-d H:i:s"));
                    if (isset($_SESSION['OPENID_AUTH']['email'])) {
                        $objSelf->thisUser->set_strEMail($_SESSION['OPENID_AUTH']['email']);
                    }
                    $objSelf->thisUser->write();
                    return $objSelf->thisUser;
                }
            } catch(Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Get the User object for the intUserID
     *
     * @param integer $intUserID UserID to search for
     *
     * @return object UserObject for intUserID
     */
    function getUserByID($intUserID = 0)
    {
        $objSelf = self::getHandler();
        if (0 + $intUserID > 0) {
            if (isset($objSelf->arrUsers[$intUserID])) {
                return $objSelf->arrUsers[$intUserID];
            }
            try {
                $db = Database::getConnection();
                $sql = "SELECT * FROM users WHERE intUserID = ? LIMIT 1";
                $query = $db->prepare($sql);
                $query->execute(array($intUserID));
                $result = $query->fetchObject('UserObject');
                $objSelf->arrUsers[$intUserID] = $result;
                return $result;
            } catch(Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }
}
