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
 * This class provides the template for an artists work.
 *
 * @category Default
 * @package  Objects
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class ArtistObject extends GenericObject
{
    // Inherited Properties
    protected $arrDBItems = array('strArtistName'=>true, 'strArtistNameSounds'=>true, 'strArtistUrl'=>true);
    protected $strDBTable = "artists";
    protected $strDBKeyCol = "intArtistID";
    // Local Properties
    protected $intArtistID = 0;
    protected $strArtistName = "";
    protected $strArtistNameSounds = "";
    protected $strArtistUrl = "";
    protected $arrTracks = array();

    /**
     * Return the value of the intArtistID
     *
     * @return int $intArtistID
     */
    function get_intArtistID()
    {
        return $this->intArtistID;
    }

    /**
     * Return the value of the strArtistName
     *
     * @return string $strArtistName
     */
    function get_strArtistName()
    {
        return $this->strArtistName;
    }

    /**
     * Return the value of the strArtistNameSounds
     *
     * @return string $strArtistNameSounds
     */
    function get_strArtistNameSounds()
    {
        return $this->strArtistNameSounds;
    }

    /**
     * Return the value of the strArtistUrl
     *
     * @return string $strArtistUrl
     */
    function get_strArtistUrl()
    {
        return $this->strArtistUrl;
    }

    /**
     * Set or amend the value of the strArtistName
     *
     * @param string $strArtistName The new artist name
     *
     * @return true This will always return true
     */
    function set_strArtistName($strArtistName = "")
    {
        if ($this->strArtistName != $strArtistName) {
            $this->strArtistName = $strArtistName;
            $this->arrChanges[] = 'strArtistName';
        }
        return true;
    }

    /**
     * Set or amend the value of the strArtistNameSounds
     *
     * @param string $strArtistNameSounds The new way to pronounce the artist name
     *
     * @return true This will always return true
     */
    function set_strArtistNameSounds($strArtistNameSounds = "")
    {
        if ($this->strArtistNameSounds != $strArtistNameSounds) {
            $this->strArtistNameSounds = $strArtistNameSounds;
            $this->arrChanges[] = 'strArtistNameSounds';
        }
        return true;
    }

    /**
     * Set or amend the value of the strArtistUrl
     *
     * @param string $strArtistUrl The new URL for the artist
     *
     * @return true This will always return true
     */
    function set_strArtistUrl($strArtistUrl = "")
    {
        if ($this->strArtistUrl != $strArtistUrl) {
            $this->strArtistUrl = $strArtistUrl;
            $this->arrChanges[] = 'strArtistUrl';
        }
        return true;
    }

    /**
     * Output a nicely formatted version of the data we hold for this object
     *
     * @return string Response
     */
    function __toString()
    {
        return "<a href=\"{$this->strArtistUrl}\">{$this->strArtistName}</a>";
    }

    /**
     * Return an array of the tracks associated to this artist
     *
     * @return array TrackObjects
     */
    function get_arrTracks()
    {
        if (!is_array($this->arrTracks) or (is_array($this->arrTracks) and count($this->arrTracks) == 0)) {
            $this->arrTracks = TracksBroker::getTracksByArtistID($this->intArtistID);
        }
        return $this->arrTracks;
    }
}
