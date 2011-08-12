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
 * This class provides all the functions for a track
 *
 * @category Default
 * @package  Objects
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */

class TrackObject extends GenericObject
{
    // Inherited Properties
    protected $arrDBItems = array('intArtistID'=>true, 'strTrackName'=>true, 'strTrackNameSounds'=>true, 'strTrackUrl'=>true, 'isNSFW'=>true, 'fileSource'=>true, 'md5FileHash'=>true, 'enumTrackLicense'=>true, 'isApproved'=>true, 'intDuplicateID'=>true);
    protected $strDBTable = "tracks";
    protected $strDBKeyCol = "intTrackID";
    // Local Properties
    protected $intTrackID = 0;
    protected $intArtistID = 0;
    protected $objArtist = null;
    protected $strTrackName = "";
    protected $strTrackNameSounds = "";
    protected $strTrackUrl = "";
    protected $enumTrackLicense = "";
    protected $isNSFW = false;
    protected $fileSource = "";
    protected $timeLength = "00:00:00";
    protected $md5FileHash = "";
    protected $dtsAdded = "";
    protected $isApproved = false;
    protected $datDailyShow = "";
    protected $intChartPlace = 0;
    protected $intDuplicateID = 0;
    protected $arrChanges = array();
    // External variables - only called from elsewhere
    protected $intTrend = 0;

    /**
     * Read the contents of the pre-created class (from the object broker) and,
     * if it's a duplicate, replace the class values with the values from the
     * duplicate, then ensure that the various true and false values are set.
     *
     * @return void
     */
    function __construct()
    {
        if ($this->intDuplicateID != 0) {
            $pointer = TrackBroker::getTrackByID($this->intDuplicateID);
            $this->intTrackID = $pointer->get_intTrackID();
            $this->intArtistID = $pointer->get_intArtistID();
            $this->objArtist = $pointer->get_objArtist();
            $this->strTrackName = $pointer->get_strTrackName();
            $this->strTrackNameSounds = $pointer->get_strTrackNameSounds();
            $this->strTrackUrl = $pointer->get_strTrackUrl();
            $this->enumTrackLicense = $pointer->get_enumTrackLicense();
            $this->isNSFW = $pointer->get_isNSFW();
            $this->fileSource = $pointer->get_fileSource();
            $this->timeLength = $pointer->get_timeLength();
            $this->md5FileHash = $pointer->get_md5FileHash();
            $this->dtsAdded = $pointer->get_dtsAdded();
            $this->isApproved = $pointer->get_isApproved();
            $this->datDailyShow = $pointer->get_datDailyShow();
            $this->intChartPlace = $pointer->get_intChartPlace();
        }
        $this->verify_objArtist();
        $this->verify_isNSFW();
        $this->verify_isApproved();
    }

    /**
     * Add the collected generated data to the getSelf function
     *
     * @return The amassed data from this function
     */
    function getSelf()
    {
        $return = parent::getSelf();
        $return['localSource'] = $this->get_fileUrl();
        $return['strArtistName'] = $this->objArtist->get_strArtistName();
        $return['strArtistNameSounds'] = $this->objArtist->get_strArtistNameSounds();
        $return['strArtistUrl'] = $this->objArtist->get_strArtistUrl();
        $return['long_enumTrackLicense'] = UI::get_enumTrackLicenseFull($this->enumTrackLicense);
        $return['pronouncable_enumTrackLicense'] = UI::get_enumTrackLicensePronouncable($this->enumTrackLicense);
        if ($this->booleanFull == true) {
            $showtracks = ShowTrackBroker::getShowTracksByTrackID($this->intTrackID);
            if ($showtracks != false) {
                foreach ($showtracks as $showtrack) {
                    $show = ShowBroker::getShowByID($showtrack->get_intShowID());
                    $show->set_full(false);
                    $return['shows'][] = $show->getSelf();
                }
            }
        }
        if ($this->intTrend > 0) {
            $return['intTrend'] = $this->intTrend;
        }
        return $return;
    }

    /**
     * If we've only been given and intArtistID, pull in the true object value
     *
     * @return true
     */
    protected function verify_objArtist()
    {
        if ($this->objArtist == null and $this->intArtistID > 0) {
            $this->objArtist = ArtistBroker::getArtistByID($this->intArtistID);
        }
        return true;
    }

    /**
     * Depending on the value supplied, make sure the actual value in the class
     * is right for the isNSFW value
     *
     * @return true
     */
    protected function verify_isNSFW()
    {
        switch(strtolower($this->isNSFW)) {
        case "0":
        case "no":
        case "false":
            $this->isNSFW = false;
            break;
        default:
            $this->isNSFW = true;
        }
        return true;
    }

    /**
     * Depending on the value supplied, make sure the actual value in the class
     * is right for the isApproved value.
     *
     * @return true
     */
    protected function verify_isApproved()
    {
        switch(strtolower($this->isApproved))
        {
        case "0":
        case "no":
        case "false":
            $this->isApproved = false;
            break;
        default:
            $this->isApproved = true;
        }
        return true;
    }

    /**
     * Set the ArtistID
     *
     * @param int $intArtistID The ID for the artist who created this track
     *
     * @return void
     */
    function set_intArtistID($intArtistID = 0)
    {
        if ($this->intArtistID != $intArtistID) {
            $this->intArtistID = $intArtistID;
            $this->arrChanges[] = 'intArtistID';
        }
    }

    /**
     * Set the Track Name
     *
     * @param string $strTrackName The name of the track
     *
     * @return void
     */
    function set_strTrackName($strTrackName = "")
    {
        if ($this->strTrackName != $strTrackName) {
            $this->strTrackName = $strTrackName;
            $this->arrChanges[] = 'strTrackName';
        }
    }

    /**
     * Set the spoken version of the Track Name
     *
     * @param string $strTrackNameSounds The festival pronounciation of this track name
     *
     * @return void
     */
    function set_strTrackNameSounds($strTrackNameSounds = "")
    {
        if ($this->strTrackNameSounds != $strTrackNameSounds) {
            $this->strTrackNameSounds = $strTrackNameSounds;
            $this->arrChanges[] = 'strTrackNameSounds';
        }
    }

    /**
     * Set the URL to find more details about the track
     *
     * @param string $strTrackUrl The place to find out more about the track
     *
     * @return void
     */
    function set_strTrackUrl($strTrackUrl = "")
    {
        if ($this->strTrackUrl != $strTrackUrl) {
            $this->strTrackUrl = $strTrackUrl;
            $this->arrChanges[] = 'strTrackUrl';
        }
    }

    /**
     * Set the Work/Family Safe state of the track
     *
     * @param boolean $isNSFW Whether the track is considered work safe.
     *
     * @return void
     */
    function set_isNSFW($isNSFW = false)
    {
        if ($this->isNSFW != $isNSFW) {
            $this->isNSFW = $isNSFW;
            $this->arrChanges[] = 'isNSFW';
            $this->verify_isNSFW();
        }
    }

    /**
     * Set the name of the file within the system.
     *
     * @param string $fileSource The name of the file to use with this item
     *
     * @return void
     */
    function set_fileSource($fileSource = "")
    {
        if ($this->fileSource != $fileSource) {
            $this->fileSource = $fileSource;
            $this->arrChanges[] = 'fileSource';
        }
    }

    /**
     * Set the md5 Hash of the track
     *
     * @param string $md5FileHash The MD5 hash of this track
     *
     * @return void
     */
    function set_md5FileHash($md5FileHash = "")
    {
        if ($md5FileHash == '') {
            $md5FileHash = md5sum($this->get_localFileSource());
        }
        if ($this->md5FileHash != $md5FileHash) {
            $this->md5FileHash = $md5FileHash;
            $this->arrChanges[] = 'md5FileHash';
        }
    }

    /**
     * Set or change the track's approved status
     *
     * @param boolean $isApproved The approval status of this track
     *
     * @return void
     */
    function set_isApproved($isApproved = false)
    {
        if ($this->isApproved != $isApproved) {
            $this->isApproved = $isApproved;
            $this->arrChanges[] = 'isApproved';
            $this->verify_isApproved();
        }
    }

    /**
     * Set the number of the track which this is a duplicate of
     *
     * @param integer $intDuplicateID The Track number this track is a duplicate of
     *
     * @return void
     */
    function set_intDuplicateID($intDuplicateID = 0)
    {
        if ($this->intDuplicateID != $intDuplicateID) {
            $this->intDuplicateID = $intDuplicateID;
            $this->set_strTrackName("");
            $this->set_strTrackNameSounds("");
            $this->set_strTrackUrl("");
            $this->set_enumTrackLicense("WIPE");
            $this->set_isNSFW(false);
            $this->set_timeLength("00:00:00");
            $this->set_md5FileHash("");
            $this->set_datDailyShow("");
            $this->set_intChartPlace(0);
            $this->set_isApproved(false);
            $this->arrChanges[] = 'intDuplicateID';
            $this->write();
            $this->wipe_fileSource();
            VoteBroker::MergeVotes($this->intTrackID, $this->intDuplicateID);
            ShowTrackBroker::ChangeTrackID($this->intTrackID, $this->intDuplicateID);
        }
    }

    /**
     * This private function erases the file when it is a duplicate of another track.
     * It is only called by the set_intDuplicateID function
     *
     * @return void
     */
    protected function wipe_fileSource()
    {
        unlink($this->get_localFileSource());
        $this->set_fileSource("");
    }

    /**
     * Set the license terms we're using this track under
     *
     * @param string $strLicense The new license terms to operate under
     *
     * @return boolean Success or failure (due to unsupported terms)
     */
    function set_enumTrackLicense($strLicense = "")
    {
        switch($strLicense)
        {
        // Actual valid licenses in the database
        case 'cc-by':
        case 'by':
            $this->enumTrackLicense = 'cc-by';
            break;
        case 'cc-by-sa':
        case 'cc-sa-by':
        case 'by-sa':
        case 'sa-by':
            $this->enumTrackLicense = 'cc-by-sa';
            break;
        case 'cc-sa':
        case 'sa':
            $this->enumTrackLicense = 'cc-sa';
            break;
        case 'cc-by-nc':
        case 'cc-nc-by':
        case 'by-nc':
        case 'nc-by':
            $this->enumTrackLicense = 'cc-by-nc';
            break;
        case 'cc-nc':
        case 'nc':
            $this->enumTrackLicense = 'cc-nc';
            break;
        case 'cc-by-nd':
        case 'cc-nd-by':
        case 'by-nd':
        case 'nd-by':
            $this->enumTrackLicense = 'cc-by-nd';
            break;
        case 'cc-nd':
        case 'nd':
            $this->enumTrackLicense = 'cc-nd';
            break;
        case 'cc-by-sa-nc':
        case 'cc-by-nc-sa':
        case 'cc-nc-by-sa':
        case 'cc-nc-sa-by':
        case 'cc-sa-by-nc':
        case 'cc-sa-nc-by':
        case 'by-sa-nc':
        case 'by-nc-sa':
        case 'nc-by-sa':
        case 'nc-sa-by':
        case 'sa-by-nc':
        case 'sa-nc-by':
            $this->enumTrackLicense = 'cc-by-nc-sa';
            break;
        case 'cc-sa-nc':
        case 'cc-nc-sa':
        case 'sa-nc':
        case 'nc-sa':
            $this->enumTrackLicense = 'cc-nc-sa';
            break;
        case 'cc-by-nd-nc':
        case 'cc-by-nc-nd':
        case 'cc-nc-by-nd':
        case 'cc-nc-nd-by':
        case 'cc-nd-by-nc':
        case 'cc-nd-nc-by':
        case 'by-nd-nc':
        case 'by-nc-nd':
        case 'nc-by-nd':
        case 'nc-nd-by':
        case 'nd-by-nc':
        case 'nd-nc-by':
            $this->enumTrackLicense = 'cc-by-nc-nd';
            break;
        case 'cc-nd-nc':
        case 'cc-nc-nd':
        case 'nd-nc':
        case 'nc-nd':
            $this->enumTrackLicense = 'cc-nc-nd';
            break;
        case 'cc-sampling+':
        case 'cc-sampling-plus':
        case 'sampling+':
        case 'sampling-plus':
            $this->enumTrackLicense = 'cc-sampling+';
            break;
        case 'cc-nc-sampling+':
        case 'cc-sampling+-nc':
        case 'cc-nc-sampling-plus':
        case 'cc-sampling-plus-nc':
        case 'nc-sampling+':
        case 'sampling+-nc':
        case 'nc-sampling-plus':
        case 'sampling-plus-nc':
            $this->enumTrackLicense = 'cc-nc-sampling+';
            break;
        case 'cc-0':
        case 'cc0':
        case '0':
            $this->enumTrackLicense = 'cc-0';
            break;
        case 'WIPE':
            $this->enumTrackLicense = null;
            break;
        default:
            return false;
        }
        return true;
    }

    /**
     * Set the value of intTrend (used in TrendBroker only)
     *
     * @param integer $intTrend Value to set
     *
     * @return void
     */
    function set_intTrend($intTrend)
    {
        if ($this->intTrend != $intTrend) {
            $this->intTrend = $intTrend;
        }
    }

    /**
     * Return the intTrend
     *
     * @return integer The intTrend
     */
    function get_intTrend()
    {
        return $this->intTrend;
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
     * Return the intArtistID
     *
     * @return integer The intArtistID
     */
    function get_intArtistID()
    {
        return $this->intArtistID;
    }

    /**
     * Return the Artist Object
     *
     * @return object ArtistObject
     */
    function get_objArtist()
    {
        return $this->objArtist;
    }

    /**
     * Return the Track Name
     *
     * @return string Track Name
     */
    function get_strTrackName()
    {
        return $this->strTrackName;
    }

    /**
     * Pronounciation of the Track Name
     *
     * @return string Track Pronounciation
     */
    function get_strTrackNameSounds()
    {
        return $this->strTrackNameSounds;
    }

    /**
     * Return the URL for the Track
     *
     * @return string Track URL
     */
    function get_strTrackUrl()
    {
        return $this->strTrackUrl;
    }

    /**
     * Return the Track License
     *
     * @return string Track License (short version)
     */
    function get_enumTrackLicense()
    {
        return $this->enumTrackLicense;
    }

    /**
     * Work/Family Safe Status
     *
     * @return boolean Work/Family Safe Status
     */
    function get_isNSFW()
    {
        return $this->isNSFW;
    }
    /**
     * Filename
     *
     * @return string Filename to use for this track
     */
    function get_fileSource()
    {
        return $this->fileSource;
    }
    /**
     * FileURL
     *
     * @return string URL for the track
     */
    function get_fileUrl()
    {
        return ConfigBroker::getConfig("Base Media URL", "http://cchits.net/media") . ConfigBroker::getConfig("fileBaseTrack", "/tracks") . '/' . $this->fileSource;
    }
    /**
     * Local File Location
     *
     * @return string Full path to the file within the local system
     */
    function get_localFileSource()
    {
        return ConfigBroker::getConfig("fileBase", "/var/www/media") . ConfigBroker::getConfig("fileBaseTrack", "/tracks") . '/' . $this->fileSource;
    }

    /**
     * Length of the track
     *
     * @return time Length of this track
     */
    function get_timeLength()
    {
        return $this->timeLength;
    }

    /**
     * MD5 Hash of the track
     *
     * @return string md5sum of the track
     */
    function get_md5FileHash()
    {
        return $this->md5FileHash;
    }

    /**
     * Date Track added to the site
     *
     * @return datetime Date the track was submitted to the site
     */
    function get_dtsAdded()
    {
        return $this->dtsAdded;
    }

    /**
     * Approval status
     *
     * @return boolean If the track is approved to be played
     */
    function get_isApproved()
    {
        return $this->isApproved;
    }

    /**
     * Daily show this track appeared on
     *
     * @return integer The date the track appeared on the Daily Show in YYYYMMDD format
     */
    function get_datDailyShow()
    {
        return $this->datDailyShow;
    }

    /**
     * The Current chart place
     *
     * @return integer The current chart position.
     */
    function get_intChartPlace()
    {
        return $this->intChartPlace;
    }

    /**
     * Is this a duplicate? If so, what is the duplicate track?
     *
     * @return integer Duplicate intTrackID
     */
    function get_intDuplicateID()
    {
        return $this->intDuplicateID;
    }
}
