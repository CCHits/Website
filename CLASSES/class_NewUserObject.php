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
 * This class creates new User objects.
 *
 * @category Default
 * @package  Objects
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class NewUserObject extends UserObject
{
    /**
     * Establish the creation of the new item by setting the values and then calling the create function.
     *
     * @param string $data OpenID authentication typically is an http://url or an https://url,
     *                     whereas basic authentication, should, by this point be username:hash(password)
     *                     Anything else should be a cookie, and thus not set here. In case some rogue
     *                     code appears down the line, the construct function hands off these auth
     *                     mechanisms to the set_ function associated with that type. This means we can
     *                     manage this from within the main class, and not try to set it here.
     *
     * @return boolean Result from the creation action
     */
    public function __construct($data = "")
    {
        if (strpos($data, "http://") !== false or strpos($data, "https://") !== false) {
            $this->set_strOpenID($data);
            $this->create();
        } elseif ($data != "") {
            $this->set_sha1Pass($data);
            $this->create();
        } else {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $cookie_string = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $cookie_string = $_SERVER['REMOTE_ADDR'];
            }
            $cookie_string .= $_SERVER['HTTP_USER_AGENT'];
            if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
                $cookie_string .= $_SERVER['HTTP_ACCEPT_LANGUAGE'];
            }
            if (isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
                $cookie_string .= $_SERVER['HTTP_ACCEPT_ENCODING'];
            }
            if (isset($_SERVER['HTTP_ACCEPT_CHARSET'])) {
                $cookie_string .= $_SERVER['HTTP_ACCEPT_CHARSET'];
            }
            $this->set_strCookieID(sha1($cookie_string));
            $_SESSION['cookie'] = sha1($cookie_string);
            try {
                $db = Database::getConnection();
                $sql = "SELECT * FROM users WHERE strCookieID = ? LIMIT 1";
                $query = $db->prepare($sql);
                $query->execute(array($_SESSION['cookie']));
                // This section of code, thanks to code example here:
                // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
                if ($query->errorCode() != 0) {
                    throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>$_SESSION['cookie'], 'error'=>$query->errorInfo()), true), 1);
                }
                $user = $query->fetchObject('UserObject');
                if ($user == false) {
                    $this->create();
                } else {
                    $this->intUserID = $user->get_intUserID();
                    $this->strOpenID = $user->get_strOpenID();
                    $this->strEMail = $user->get_strEMail();
                    $this->strCookieID = $user->get_strCookieID();
                    $this->sha1Pass = $user->get_sha1Pass();
                    $this->isAuthorized = $user->get_isAuthorized();
                    $this->isUploader = $user->get_isUploader();
                    $this->isAdmin = $user->get_isAdmin();
                    $this->datLastSeen = $user->get_datLastSeen();
                    $this->strUserName = $user->get_strUserName();
                }
            } catch(Exception $e) {
                error_log($e);
                return false;
            }
        }
    }
}
