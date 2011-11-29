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
     * Add the collected generated data to the getSelf function
     *
     * @return The amassed data from this function
     */
    function getSelf()
    {
        $return = parent::getSelf();
        $return['arrArtistName'] = $this->getJson($this->strArtistName);
        $return['strArtistName'] = $this->preferredJson($this->strArtistName);
        $return['strArtistUrl'] = $this->preferredJson($this->strArtistUrl);
        $return['arrArtistUrl'] = $this->getJson($this->strArtistUrl);
        return $return;
    }

    /**
     * This function amends data supplied by the API or HTML forms to provide additional data to update an artist
     *
     * @return void
     */
    public function amendRecord()
    {
        $arrUri = UI::getUri();
        if (isset($arrUri['parameters']['strArtistName_preferred']) and $arrUri['parameters']['strArtistName_preferred'] != '') {
            $this->setpreferred_strArtistName($arrUri['parameters']['strArtistName_preferred']);
        }
        if (isset($arrUri['parameters']['strArtistName']) and $arrUri['parameters']['strArtistName'] != '') {
            $this->set_strArtistName($arrUri['parameters']['strArtistName']);
        }
        if (isset($arrUri['parameters']['del_strArtistName']) and $arrUri['parameters']['del_strArtistName'] != '') {
            $this->del_strArtistName($arrUri['parameters']['del_strArtistName']);
        }
        if (isset($arrUri['parameters']['strArtistNameSounds']) and $arrUri['parameters']['strArtistNameSounds'] != '') {
            $this->set_strArtistNameSounds($arrUri['parameters']['strArtistNameSounds']);
        }
        if (isset($arrUri['parameters']['strArtistUrl_preferred']) and $arrUri['parameters']['strArtistUrl_preferred'] != '') {
            $this->setpreferred_strArtistUrl($arrUri['parameters']['strArtistUrl_preferred']);
        }
        if (isset($arrUri['parameters']['strArtistUrl']) and $arrUri['parameters']['strArtistUrl'] != '') {
            $this->set_strArtistUrl($arrUri['parameters']['strArtistUrl']);
        }
        if (isset($arrUri['parameters']['del_strArtistUrl']) and $arrUri['parameters']['del_strArtistUrl'] != '') {
            $this->del_strArtistUrl($arrUri['parameters']['del_strArtistUrl']);
        }
        $this->write();
    }

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
     * Bypassing the subsequent JSON based modifiers, this lets you dump your details straight into the artist details.
     *
     * @param string $jsonArtistName JSON encoded list of Artist alternative names.
     *
     * @return void
     */
    function set_jsonArtistName($jsonArtistName = "")
    {
        if ($this->strArtistName != $jsonArtistName) {
            $this->strArtistName = $jsonArtistName;
            $this->arrChanges['strArtistName'] = true;
        }
    }

    /**
     * Set or amend the value of the strArtistName
     *
     * @param string $strArtistName The new artist name
     *
     * @return void
     */
    function set_strArtistName($strArtistName = "")
    {
        if ( ! $this->inJson($this->strArtistName, $strArtistName)) {
            $this->strArtistName = $this->addJson($this->strArtistName, $strArtistName);
            $this->arrChanges['strArtistName'] = true;
        }
    }

    /**
     * Set the preferred version of the artist's name
     *
     * @param string $strArtistName The preferred version of the Artist's name
     *
     * @return void
     */
    function setpreferred_strArtistName($strArtistName = '')
    {
        if ($this->preferredJson($this->strArtistName) != $strArtistName) {
            $this->strArtistName = $this->addJson($this->strArtistName, $strArtistName, true);
            $this->arrChanges['strArtistName'] = true;
        }
    }

    /**
     * Remove a value from the JSON array of Artist Names and update the change queue
     *
     * @param string $strArtistName The string to remove
     *
     * @return void
     */
    function del_strArtistName($strArtistName = "")
    {
        $this->strArtistName = $this->delJson($this->strArtistName, $strArtistName);
        $this->arrChanges['strArtistName'] = true;
    }

    /**
     * Set or amend the value of the strArtistNameSounds
     *
     * @param string $strArtistNameSounds The new way to pronounce the artist name
     *
     * @return void
     */
    function set_strArtistNameSounds($strArtistNameSounds = "")
    {
        if ($this->strArtistNameSounds != $strArtistNameSounds) {
            $this->strArtistNameSounds = $strArtistNameSounds;
            $this->arrChanges['strArtistNameSounds'] = true;
        }
    }

    /**
     * Bypassing the subsequent JSON based modifiers, this lets you dump your details straight into the artist details.
     *
     * @param string $jsonArtistUrl JSON encoded list of Artist alternative URLs.
     *
     * @return void
     */
    function set_jsonArtistUrl($jsonArtistUrl = "")
    {
        if ($this->strArtistUrl != $jsonArtistUrl) {
            $this->strArtistUrl = $jsonArtistUrl;
            $this->arrChanges['strArtistUrl'] = true;
        }
    }

    /**
     * Set or amend the value of the strArtistUrl
     *
     * @param string $strArtistUrl The new URL for the artist
     *
     * @return void
     */
    function set_strArtistUrl($strArtistUrl = "")
    {
        if ( ! $this->inJson($this->strArtistUrl, $strArtistUrl)) {
            $this->strArtistUrl = $this->addJson($this->strArtistUrl, $strArtistUrl);
            $this->arrChanges['strArtistUrl'] = true;
        }
    }

    /**
     * Set the preferred URL to find more details about the Artist
     *
     * @param string $strArtistUrl The preferred place to find out more about the Artist
     *
     * @return void
     */
    function setpreferred_strArtistUrl($strArtistUrl = '')
    {
        if ($this->preferredJson($this->strArtistUrl) != $strArtistUrl) {
            $this->strArtistUrl = $this->addJson($this->strArtistUrl, $strArtistUrl, true);
            $this->arrChanges['strArtistUrl'] = true;
        }
    }

    /**
     * Remove a value from the JSON array of URLs and update the change queue
     *
     * @param string $strArtistUrl The string to remove
     *
     * @return void
     */
    function del_strArtistUrl($strArtistUrl = "")
    {
        $this->strArtistUrl = $this->delJson($this->strArtistUrl, $strArtistUrl);
        $this->arrChanges['strArtistUrl'] = true;
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
