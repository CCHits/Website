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
 * This class provides all the functions for a bumper track
 *
 * @category Default
 * @package  Objects
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */

class BumperTrackObject extends GenericObject
{
    // Inherited Properties
    protected $arrDBItems = array('intTrackID'=>true, 'enumBumperType'=>true, 'enumPosition'=>true, 'fileSource'=>true, 'md5FileHash'=>true);
    protected $strDBTable = "bumpers_track";
    protected $strDBKeyCol = "intTrackBumperID";
    // Local Properties
    protected $intTrackBumperID = 0;
    protected $intTrackID = 0;
    protected $enumBumperType = null;
    protected $enumPosition = null;
    protected $fileSource = "";
    protected $md5FileHash = "";
    protected $arrChanges = array();

    /**
     * Return the intTrackBumperID
     *
     * @return integer The intTrackBumperID
     */
    function get_intTrackBumperID()
    {
        return $this->intTrackBumperID;
    }

    /**
     * Return the intTrackID
     *
     * @return integer The intTrackID
     */
    function get_intTrackID()
    {
        return $this->intTrackID;
    }

    /**
     * Set the intTrackID
     *
     * @param int $intTrackID The ID
     *
     * @return void
     */
    function set_intTrackID($intTrackID = 0)
    {
        if ($this->intTrackID != $intTrackID) {
            $this->intTrackID = $intTrackID;
            $this->arrChanges[] = 'intTrackID';
        }
    }
    
    /**
     * Return the enumBumperType
     *
     * @return string The enumBumperType
     */
    function get_enumBumperType()
    {
        return $this->enumBumperType;
    }

    /**
     * Set the enumBumperType
     *
     * @param string $enumBumperType The Bumper Type
     *
     * @return void
     */
    function set_enumBumperType($enumBumperType = '')
    {
        if ($this->enumBumperType != $enumBumperType) {
            $this->enumBumperType = $enumBumperType;
            $this->arrChanges[] = 'enumBumperType';
        }
    }
    
    /**
     * Return the fileName for the file
     *
     * @return string The fileName
     */
    function get_fileName()
    {
        return $this->fileName;
    }

    /**
     * Set the fileName
     *
     * @param string $fileName The File name for the bumper
     *
     * @return void
     */
    function set_fileName($fileName = '')
    {
        if ($this->fileName != $fileName) {
            $this->fileName = $fileName;
            $this->arrChanges[] = 'fileName';
        }
    }
    
    /**
     * Return the md5FileHash for the bumper
     *
     * @return string The md5FileHash
     */
    function get_md5FileHash()
    {
        return $this->md5FileHash;
    }

    /**
     * Set the md5FileHash
     *
     * @param string $md5FileHash The md5 hash for the bumper
     *
     * @return void
     */
    function set_md5FileHash($md5FileHash = '')
    {
        if ($this->md5FileHash != $md5FileHash) {
            $this->md5FileHash = $md5FileHash;
            $this->arrChanges[] = 'md5FileHash';
        }
    }
}