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
 * This class deals with user objects
 *
 * @category Default
 * @package  Objects
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */

class UserObject extends GenericObject
{
    // Inherited Properties
    protected $arrDBItems = array(
        'strOpenID'=>true,
        'strCookieID'=>true,
        'sha1Pass'=>true,
        'isAuthorized'=>true,
        'isUploader'=>true,
        'isAdmin'=>true,
        'datLastSeen'=>true
    );
    protected $strDBTable = "users";
    protected $strDBKeyCol = "intUserID";
    // Local Properties
    protected $intUserID = 0;
    protected $strOpenID = "";
    protected $strCookieID = "";
    protected $sha1Pass = "";
    protected $isAuthorized = 0;
    protected $isUploader = 0;
    protected $isAdmin = 0;
    protected $datLastSeen = "";

    /**
     * Set the OpenID authenticator for this user account
     *
     * @param string $strOpenID OpenID Endpoint to use for this account
     *
     * @return void
     */
    function set_strOpenID($strOpenID = "")
    {
        if ($this->strOpenID != $strOpenID) {
            $this->strOpenID = $strOpenID;
            $this->arrChanges[] = 'strOpenID';
        }
    }

    /**
     * Set the Cookie string we're using for this account
     *
     * @param string $strCookieID Cookie String to use for this account
     *
     * @return void
     */
    function set_strCookieID($strCookieID = "")
    {
        if ($this->strCookieID != $strCookieID) {
            $this->strCookieID = $strCookieID;
            $this->arrChanges[] = 'strCookieID';
        }
    }

    /**
     * Set the username and password used to authenticate this account in API calls
     *
     * @param string $sha1Pass Username and sha1(password) for this account
     *
     * @return void
     */
    function set_sha1Pass($sha1Pass = "")
    {
        if ($this->sha1Pass != $sha1Pass) {
            $this->sha1Pass = $sha1Pass;
            $this->arrChanges[] = 'sha1Pass';
        }
    }

    /**
     * Set the Authorized to commit directly without checking status of this account
     *
     * @param boolean $isAuthorized Is this account able to insert tracks without peer review
     *
     * @return void
     */
    function set_isAuthorized($isAuthorized = false)
    {
        if ($this->isAuthorized != $isAuthorized) {
            $this->isAuthorized = $isAuthorized;
            $this->arrChanges[] = 'isAuthorized';
        }
    }

    /**
     * Set whether this user is allowed to upload to the site
     *
     * @param boolean $isUploader Is this account allowed to upload
     *
     * @return void
     */
    function set_isUploader($isUploader = false)
    {
        if ($this->isUploader != $isUploader) {
            $this->isUploader = $isUploader;
            $this->arrChanges[] = 'isUploader';
        }
    }

    /**
     * Set the administrative status of this user
     *
     * @param boolean $isAdmin Is this account able to create shows and perform peer reviews?
     *
     * @return void
     */
    function set_isAdmin($isAdmin = false)
    {
        if ($this->isAdmin != $isAdmin) {
            $this->isAdmin = $isAdmin;
            $this->arrChanges[] = 'isAdmin';
        }
    }

    /**
     * Set the date they were last seen on the site
     *
     * @param datetime $datLastSeen Last time this account was seen on the site
     *
     * @return void
     */
    function set_datLastSeen($datLastSeen = null)
    {
        if ($this->datLastSeen != $datLastSeen) {
            $this->datLastSeen = $datLastSeen;
            $this->arrChanges[] = 'datLastSeen';
        }
    }
    
    /**
     * Return the intUserID
     *
     * @return integer The user ID
     */
    function get_intUserID() 
    {
        Debug::Log(get_class() . "::get_intUserID()", "NOISY");
        return $this->intUserID;
    }
    
    /**
     * Return the strOpenID
     *
     * @return string The OpenID Endpoint
     */
    function get_strOpenID()
    {
        Debug::Log(get_class() . "::get_strOpenID()", "NOISY");
        return $this->strOpenID;
    }

    /**
     * Return the strCookieID
     *
     * @return string The Cookie string being used by this session/user 
     */
    function get_strCookieID()
    {
        Debug::Log(get_class() . "::get_strCookieID()", "NOISY");
        return $this->strCookieID;
    }

    /**
     * Return the username and hashed password
     *
     * @return string The basic authentication details
     */
    function get_sha1Pass()
    {
        Debug::Log(get_class() . "::get_sha1Pass()", "NOISY");
        return $this->sha1Pass;
    }

    /**
     * Return the state of this user
     *
     * @return boolean Is Authorized
     */
    function get_isAuthorized()
    {
        Debug::Log(get_class() . "::get_isAuthorized()", "NOISY");
        return $this->isAuthorized;
    }

    /**
     * Return the state of this user
     *
     * @return boolean Is allowed to upload
     */
    function get_isUploader()
    {
        Debug::Log(get_class() . "::get_isUploader()", "NOISY");
        return $this->isUploader;
    }

    /**
     * Return the state of this user
     *
     * @return boolean Can create shows and peer review
     */
    function get_isAdmin()
    {
        Debug::Log(get_class() . "::get_isAdmin()", "NOISY");
        return $this->isAdmin;
    }

    /**
     * Return the last time this user was seen
     *
     * @return datetime The last time the user was seen
     */
    function get_datLastSeen()
    {
        Debug::Log(get_class() . "::get_datLastSeen()", "NOISY");
        return $this->datLastSeen;
    }
}
