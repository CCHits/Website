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
        } elseif ($data != "") {
            $this->set_sha1Pass($data);
        } else {        
            $this->set_strCookieID(sha1(strtotime("now")));
        }
        return $this->create();
    }
}
