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
        list($username, $password) = UI::getAuth();
        if (isset($_SESSION['cookie']) AND $_SESSION['cookie'] != '') {
            $field = "strCookieID";
            $param = $_SESSION['cookie'];
        } elseif (isset($_SESSION['OPENID_AUTH']) AND $_SESSION['OPENID_AUTH'] != false) {
            $field = "strOpenID";
            $param = $_SESSION['OPENID_AUTH']['url'];
        } elseif ($username !== null && $password !== null) {
            $field = "sha1Pass";
            $sha1password = sha1($password);
            $param = "{$username}:{$sha1password}";
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
                // This section of code, thanks to code example here:
                // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
                if ($query->errorCode() != 0) {
                    throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>$param, 'error'=>$query->errorInfo()), true), 1);
                }
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
                // This section of code, thanks to code example here:
                // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
                if ($query->errorCode() != 0) {
                    throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>$intUserID, 'error'=>$query->errorInfo()), true), 1);
                }
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
