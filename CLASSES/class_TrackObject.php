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
    protected $arrChartData = array();
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
        $arrChartData = ChartBroker::getLastSixtyDaysOfChartDataForOneTrack($this->intTrackID);
        if ($arrChartData != false) {
            $this->arrChartData = $arrChartData;
        }
    }

    /**
     * This function amends data supplied by the API or HTML forms to provide additional data to update a track
     *
     * @return void
     */
    public function amendRecord()
    {
        $arrUri = UI::getUri();
        if (isset($arrUri['parameters']['strTrackName_preferred']) and $arrUri['parameters']['strTrackName_preferred'] != '') {
            $this->setpreferred_strTrackName($arrUri['parameters']['strTrackName_preferred']);
        }
        if (isset($arrUri['parameters']['strTrackName']) and $arrUri['parameters']['strTrackName'] != '') {
            $this->set_strTrackName($arrUri['parameters']['strTrackName']);
        }
        if (isset($arrUri['parameters']['del_strTrackName']) and $arrUri['parameters']['del_strTrackName'] != '') {
            $this->del_strTrackName($arrUri['parameters']['del_strTrackName']);
        }
        if (isset($arrUri['parameters']['strTrackNameSounds']) and $arrUri['parameters']['strTrackNameSounds'] != '') {
            $this->set_strTrackNameSounds($arrUri['parameters']['strTrackNameSounds']);
        }
        if (isset($arrUri['parameters']['strTrackUrl_preferred']) and $arrUri['parameters']['strTrackUrl_preferred'] != '') {
            $this->setpreferred_strTrackUrl($arrUri['parameters']['strTrackUrl_preferred']);
        }
        if (isset($arrUri['parameters']['strTrackUrl']) and $arrUri['parameters']['strTrackUrl'] != '') {
            $this->set_strTrackUrl($arrUri['parameters']['strTrackUrl']);
        }
        if (isset($arrUri['parameters']['del_strTrackUrl']) and $arrUri['parameters']['del_strTrackUrl'] != '') {
            $this->del_strTrackUrl($arrUri['parameters']['del_strTrackUrl']);
        }
        if (isset($arrUri['parameters']['strArtistName_preferred']) and $arrUri['parameters']['strArtistName_preferred'] != '') {
            $this->get_objArtist()->setpreferred_strArtistName($arrUri['parameters']['strArtistName_preferred']);
        }
        if (isset($arrUri['parameters']['strArtistName']) and $arrUri['parameters']['strArtistName'] != '') {
            $this->get_objArtist()->set_strArtistName($arrUri['parameters']['strArtistName']);
        }
        if (isset($arrUri['parameters']['del_strArtistName']) and $arrUri['parameters']['del_strArtistName'] != '') {
            $this->get_objArtist()->del_strArtistName($arrUri['parameters']['del_strArtistName']);
        }
        if (isset($arrUri['parameters']['strArtistNameSounds']) and $arrUri['parameters']['strArtistNameSounds'] != '') {
            $this->get_objArtist()->set_strArtistNameSounds($arrUri['parameters']['strArtistNameSounds']);
        }
        if (isset($arrUri['parameters']['strArtistUrl_preferred']) and $arrUri['parameters']['strArtistUrl_preferred'] != '') {
            $this->get_objArtist()->setpreferred_strArtistUrl($arrUri['parameters']['strArtistUrl_preferred']);
        }
        if (isset($arrUri['parameters']['strArtistUrl']) and $arrUri['parameters']['strArtistUrl'] != '') {
            $this->get_objArtist()->set_strArtistUrl($arrUri['parameters']['strArtistUrl']);
        }
        if (isset($arrUri['parameters']['del_strArtistUrl']) and $arrUri['parameters']['del_strArtistUrl'] != '') {
            $this->get_objArtist()->del_strArtistUrl($arrUri['parameters']['del_strArtistUrl']);
        }
        if (isset($arrUri['parameters']['approved'])) {
            $this->set_isApproved($this->asBoolean($arrUri['parameters']['approved']));
        }
        if (isset($arrUri['parameters']['nsfw'])) {
            $this->set_isNSFW($arrUri['parameters']['nsfw']);
        }
        if (isset($arrUri['parameters']['duplicate'])) {
            $this->set_intDuplicateID($arrUri['parameters']['duplicate']);
        }
        $this->get_objArtist()->write();
        $this->write();
    }

    /**
     * Add the collected generated data to the getSelf function
     *
     * @return The amassed data from this function
     */
    function getSelf()
    {
        $return = parent::getSelf();
        $return['arrTrackName'] = $this->getJson($this->strTrackName);
        $return['strTrackName'] = $this->preferredJson($this->strTrackName);
        $return['strLicenseUrl'] = UI::resolveLicenseUrl($this->get_enumTrackLicense());
        $return['strLicenseName'] = UI::get_enumTrackLicenseFull($this->get_enumTrackLicense());
        $return['strTrackUrl'] = $this->preferredJson($this->get_strTrackUrl());
        $return['arrTrackUrl'] = $this->getJson($this->get_strTrackUrl());
        $return['localSource'] = $this->get_fileUrl();
        $return['strArtistName'] = $this->preferredJson($this->objArtist->get_strArtistName());
        $return['arrArtistName'] = $this->getJson($this->objArtist->get_strArtistName());
        $return['strArtistNameSounds'] = $this->objArtist->get_strArtistNameSounds();
        $return['strArtistUrl'] = $this->preferredJson($this->objArtist->get_strArtistUrl());
        $return['arrArtistUrl'] = $this->getJson($this->objArtist->get_strArtistUrl());
        $return['long_enumTrackLicense'] = UI::get_enumTrackLicenseFull($this->enumTrackLicense);
        $return['pronouncable_enumTrackLicense'] = UI::get_enumTrackLicensePronouncable($this->enumTrackLicense);
        $return['dtsAdded'] = date("Y-m-d", strtotime($this->dtsAdded));
        $return['qrcode'] = UI::InsertQRCode(ConfigBroker::getConfig('fileBaseTrack', '/tracks') . '/' . $this->intTrackID);
        if (isset($this->arrChartData[1])) {
            if ($this->arrChartData[0]['intPositionID'] > $this->arrChartData[1]['intPositionID']) {
                $return['strPositionYesterday'] = 'down';
            } elseif ($this->arrChartData[0]['intPositionID'] < $this->arrChartData[1]['intPositionID']) {
                $return['strPositionYesterday'] = 'up';
            } else {
                $return['strPositionYesterday'] = 'equal';
            }
            $return['60dayhighest'] = TrackBroker::getTotalTracks();
            $return['60daylowest'] = 0;
            foreach ($this->arrChartData as $arrChartObject) {
                if ($arrChartObject['intPositionID'] < $return['60dayhighest']) {
                    $return['60dayhighest'] = $arrChartObject['intPositionID'];
                }
                if ($arrChartObject['intPositionID'] > $return['60daylowest']) {
                    $return['60daylowest'] = $arrChartObject['intPositionID'];
                }
            }
            if ($return['60daylowest'] == 'null') {
                $return['60daylowest'] = TrackBroker::getTotalTracks();
            }
            if (isset($this->arrChartData[13])) {
                $averageThisWeek = (
                    $this->arrChartData[0]['intPositionID'] +
                    $this->arrChartData[1]['intPositionID'] +
                    $this->arrChartData[2]['intPositionID'] +
                    $this->arrChartData[3]['intPositionID'] +
                    $this->arrChartData[4]['intPositionID'] +
                    $this->arrChartData[5]['intPositionID'] +
                    $this->arrChartData[6]['intPositionID']) / 7;
                $averageLastWeek = (
                    $this->arrChartData[7]['intPositionID'] +
                    $this->arrChartData[8]['intPositionID'] +
                    $this->arrChartData[9]['intPositionID'] +
                    $this->arrChartData[10]['intPositionID'] +
                    $this->arrChartData[11]['intPositionID'] +
                    $this->arrChartData[12]['intPositionID'] +
                    $this->arrChartData[13]['intPositionID']) / 7;
                $return['averageLastWeek'] = $averageLastWeek;
                $return['averageThisWeek'] = $averageThisWeek;
                if ($averageThisWeek > $averageLastWeek) {
                    $return['strPositionLastWeek'] = 'down';
                } elseif ($averageThisWeek < $averageLastWeek) {
                    $return['strPositionLastWeek'] = 'up';
                } else {
                    $return['strPositionLastWeek'] = 'equal';
                }
            }
        }
        $arrChartData = $this->arrChartData;
        krsort($arrChartData);
        $return['intChartPeak'] = ChartBroker::getTrackPeak($this->intTrackID);
        $return['arrChartData'] = $arrChartData;
        if ($this->booleanFull == true) {
            if ($this->intChartPlace <= 40) {
                $return['generalPosition'] = 'top40';
            } elseif ($this->intChartPlace < (TrackBroker::getTotalTracks() /2)) {
                $return['generalPosition'] = 'top';
            } else {
                $return['generalPosition'] = 'bottom';
            }
            $showtracks = ShowTrackBroker::getShowTracksByTrackID($this->intTrackID);
            if ($showtracks != false) {
                foreach ($showtracks as $showtrack) {
                    $show = ShowBroker::getShowByID($showtrack->get_intShowID());
                    $show->set_full(false);
                    $return['shows'][] = $show->getSelf();
                }
            }
            $votes = VoteBroker::getVotesForTrackByShow($this->intTrackID);
            $return['intVote'] = $votes['total'];
            $return['decVoteAdj'] = $votes['total'] * $votes['adjust'];
            $return['decAdj'] = $votes['adjust'];
            foreach ($votes['shows'] as $show) {
                if ($show == false) {
                    $intShowID = 0;
                    $return['arrShows'][0] = array(
                    	'strShowName' => "Non-show votes",
                        'strShowUrl' => ConfigBroker::getConfig('Base URL', 'http://cchits.net') . ConfigBroker::getConfig('fileBaseTrack', '/track') . '/' . $this->intTrackID,
                        'enumShowType' => 'none'
                    );
                } else {
                    $intShowID = $show->get_intShowID();
                    $show->set_full(false);
                    $return['arrShows'][$intShowID] = $show->getSelf();
                }
                $return['arrShows'][$intShowID]['intVote'] = $votes['breakdown'][$intShowID]->get_intCount();
                $return['arrShows'][$intShowID]['decVoteAdj'] = $votes['breakdown'][$intShowID]->get_intCount() * $votes['adjust'];
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
     * @return void
     */
    protected function verify_objArtist()
    {
        if ($this->objArtist == null and $this->intArtistID > 0) {
            $this->objArtist = ArtistBroker::getArtistByID($this->intArtistID);
        }
    }

    /**
     * Depending on the value supplied, make sure the actual value in the class
     * is right for the isNSFW value
     *
     * @return void
     */
    protected function verify_isNSFW()
    {
        $this->isNSFW = $this->asBoolean($this->isNSFW);
    }

    /**
     * Depending on the value supplied, make sure the actual value in the class
     * is right for the isApproved value.
     *
     * @return void
     */
    protected function verify_isApproved()
    {
        $this->isApproved = $this->asBoolean($this->isApproved);
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
        if ( ! $this->inJson($this->strTrackName, $strTrackName)) {
            $this->strTrackName = $this->addJson($this->strTrackName, $strTrackName);
            $this->arrChanges[] = 'strTrackName';
        }
    }

    /**
     * Set the Preferred Track Name
     *
     * @param string $strTrackName The name of the track
     *
     * @return void
     */
    function setpreferred_strTrackName($strTrackName = "")
    {
        if ($this->preferredJson($this->strTrackName) != $strTrackName) {
            $this->strTrackName = $this->addJson($this->strTrackName, $strTrackName, true);
            $this->arrChanges[] = 'strTrackName';
        }
    }

    /**
     * Remove a value from the JSON array of Track Names and update the change queue
     *
     * @param string $strTrackName The string to remove
     *
     * @return void
     */
    function del_strTrackName($strTrackName = "")
    {
        $this->strTrackName = $this->delJson($this->strTrackName, $strTrackName);
        $this->arrChanges[] = 'strTrackName';
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
        if ( ! $this->inJson($this->strTrackUrl, $strTrackUrl)) {
            $this->strTrackUrl = $this->addJson($this->strTrackUrl, $strTrackUrl);
            $this->arrChanges[] = 'strTrackUrl';
        }
    }

    /**
     * Set the preferred URL to find more details about the track
     *
     * @param string $strTrackUrl The preferred place to find out more about the track
     *
     * @return void
     */
    function setpreferred_strTrackUrl($strTrackUrl = '')
    {
        if ($this->preferredJson($this->strTrackUrl) != $strTrackUrl) {
            $this->strTrackUrl = $this->addJson($this->strTrackUrl, $strTrackUrl, true);
            $this->arrChanges[] = 'strTrackUrl';
        }
    }

    /**
     * Remove a value from the JSON array of URLs and update the change queue
     *
     * @param string $strTrackUrl The string to remove
     *
     * @return void
     */
    function del_strTrackUrl($strTrackUrl = "")
    {
        $this->strTrackUrl = $this->delJson($this->strTrackUrl, $strTrackUrl);
        $this->arrChanges[] = 'strTrackUrl';
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
            $md5FileHash = md5($this->get_localFileSource());
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
     * Set the license terms we're using this track under
     *
     * @param string $strLicense The new license terms to operate under
     *
     * @return boolean Success or failure (due to unsupported terms)
     */
    function set_enumTrackLicense($strLicense = "")
    {
        $strLicense = LicenseSelector::validateLicense($strLicense);
        if ($this->enumTrackLicense != $strLicense) {
            $this->enumTrackLicense = $strLicense;
            $arrChanges['enumTrackLicense'] = true;
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
