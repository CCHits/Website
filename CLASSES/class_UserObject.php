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
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */
/**
 * This class deals with user objects
 *
 * @category Default
 * @package  Objects
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */

class UserObject extends GenericObject
{
    // Inherited Properties
    protected $arrDBItems = array(
        'strOpenID'=>true,
        'strCookieID'=>true,
        'strEMail'=>true,
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
    protected $strOpenID = null;
    protected $strEMail = null;
    protected $strCookieID = null;
    protected $sha1Pass = null;
    protected $isAuthorized = 0;
    protected $isUploader = 0;
    protected $isAdmin = 0;
    protected $datLastSeen = null;
    protected $strUserName = "";

    /**
     * A basic handler to extract the username from the sha1Password field of the database.
     *
     * @return void
     */
    function __construct()
    {
        if (isset($this->sha1Pass) and $this->sha1Pass != '' and preg_match('/(.*):/', $this->sha1Pass, $match) > 0) {
            $this->strUserName = $match[1];
        }
    }

    /**
     * Add the generated data to the getSelf function
     *
     * @return The amassed data from this function
     */
    function getSelf()
    {
        $return = parent::getSelf();
        $return['strUserName'] = $this->strUserName;
        return $return;
    }

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
            $this->arrChanges['strOpenID'] = true;
        }
    }

    /**
     * Set the OpenID provided e-mail address for this user account.
     *
     * @param string $strEMail The e-mail address to set for this user account
     *
     * @return void
     */
    function set_strEMail($strEMail = "")
    {
        if ($this->strEMail != $strEMail) {
            $this->strEMail = $strEMail;
            $this->arrChanges['strEMail'] = true;
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
            $this->arrChanges['strCookieID'] = true;
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
            $this->arrChanges['sha1Pass'] = true;
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
            $this->arrChanges['isAuthorized'] = true;
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
            $this->arrChanges['isUploader'] = true;
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
            $this->arrChanges['isAdmin'] = true;
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
            $this->arrChanges['datLastSeen'] = true;
        }
    }

    /**
     * Return the intUserID
     *
     * @return integer The user ID
     */
    function get_intUserID()
    {
        return $this->intUserID;
    }

    /**
     * Return the strOpenID
     *
     * @return string The OpenID Endpoint
     */
    function get_strOpenID()
    {
        return $this->strOpenID;
    }

    /**
     * Return the user's e-mail. Only used when re-using the UserObject in NewUserObject
     *
     * @return string The User's e-mail address
     */
    function get_strEMail()
    {
        return $this->strEMail;
    }

    /**
     * Return the strCookieID
     *
     * @return string The Cookie string being used by this session/user
     */
    function get_strCookieID()
    {
        return $this->strCookieID;
    }

    /**
     * Return the username and hashed password
     *
     * @return string The basic authentication details
     */
    function get_sha1Pass()
    {
        return $this->sha1Pass;
    }

    /**
     * Return the derived username
     *
     * @return string The username
     */
    function get_strUserName()
    {
        return $this->strUserName;
    }

    /**
     * Return the state of this user
     *
     * @return boolean Is Authorized to submit tracks directly into the system without being pre-vetted
     */
    function get_isAuthorized()
    {
        return $this->asBoolean($this->isAuthorized);
    }

    /**
     * Return the state of this user
     *
     * @return boolean Is allowed to upload
     */
    function get_isUploader()
    {
        return $this->asBoolean($this->isUploader);
    }

    /**
     * Return the state of this user
     *
     * @return boolean Can create shows and peer review
     */
    function get_isAdmin()
    {
        return $this->asBoolean($this->isAdmin);
    }

    /**
     * Return the last time this user was seen
     *
     * @return datetime The last time the user was seen
     */
    function get_datLastSeen()
    {
        return $this->datLastSeen;
    }
}
