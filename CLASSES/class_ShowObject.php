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
 * This class extends upon Tracks to create a Show class. A show is a
 * collection of tracks, with a title, an ID and a URL. It is constructed
 * around the database table of the same name.
 *
 * @category Default
 * @package  Objects
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class ShowObject extends GenericObject
{
    // Inherited Properties
    protected $arrDBItems = array('strShowName'=>true, 'strShowUrl'=>true, 'intShowUrl'=>true, 'enumShowType'=>true, 'intUserID'=>true, 'timeLength'=>true, 'shaHash'=>true, 'strCommentUrl'=>true, 'jsonAudioLayout'=>true, 'datDateAdded'=>true);
    protected $strDBTable = "shows";
    protected $strDBKeyCol = "intShowID";
    // Local Properties
    protected $intShowID = 0;
    protected $strShowName = "";
    protected $strShowNameSpoken = "";
    protected $strShowUrl = "";
    protected $strShowUrlSpoken = "";
    protected $intShowUrl = 0;
    protected $enumShowType = "";
    protected $intUserID = 0;
    protected $timeLength = "";
    protected $shaHash = "";
    protected $strCommentUrl = "";
    protected $jsonAudioLayout = "";
    protected $datDateAdded = "";
    protected $strShowFileMP3 = "";
    protected $strShowFileOGG = "";
    protected $arrTracks = null;
    // Functional switches extending GenericObject
    protected $booleanFull = true;

    /**
     * Construct the object.
     *
     * Check whether it's being instantiated from PDO and thus already
     * has some details populated. In which case, set class objects
     * accordingly.
     *
     * @return void
     */
    function __construct()
    {
        if ($this->intShowID > 0) {
            $intShowID = $this->intShowID;
        }

        if ($this->intShowUrl != 0) {
            if ($this->strShowUrl == "") {
                $this->strShowUrl = ConfigBroker::getConfig("Base URL", "http://cchits.net") . '/' . $this->enumShowType . "/" . $this->intShowUrl;
                $this->strShowUrlSpoken = ConfigBroker::getConfig("Spoken Base URL", "Cee Cee Hits Dot Net") . ' slash ' . $this->enumShowType . " slash " . UI::getPronouncableDate($this->intShowUrl);
                if (file_exists(ConfigBroker::getConfig('fileBase', '/var/www/media') . '/' . $this->enumShowType . "/" . $this->intShowUrl . '.mp3')) {
                    $this->strShowFileMP3 = ConfigBroker::getConfig("Base Media URL", "http://cchits.net/media") . '/' . $this->enumShowType . "/" . $this->intShowUrl . '.mp3';
                }
                if (file_exists(ConfigBroker::getConfig('fileBase', '/var/www/media') . '/' . $this->enumShowType . "/" . $this->intShowUrl . '.ogg')) {
                    $this->strShowFileOGG = ConfigBroker::getConfig("Base Media URL", "http://cchits.net/media") . '/' . $this->enumShowType . "/" . $this->intShowUrl . '.ogg';
                }
            }
            if ($this->strShowName == "") {
                switch($this->enumShowType) {
                case 'monthly':
                    $this->strShowName = 'The ' . ConfigBroker::getConfig('Site Name', 'CCHits.net');
                    $this->strShowName .= ' ' . ConfigBroker::getConfig('Monthly Show Name', 'Monthly Top Tracks Show');
                    $this->strShowName .= ' for ';
                    $this->strShowName .= UI::getLongDate($this->intShowUrl);
                    $this->strShowNameSpoken = 'The ' . ConfigBroker::getConfig('Spoken Site Name', 'Cee Cee Hits dot Net');
                    $this->strShowNameSpoken .= ' ' . ConfigBroker::getConfig('Spoken Monthly Show Name', 'Monthly Top Tracks Show');
                    $this->strShowNameSpoken .= ' for ';
                    $this->strShowNameSpoken .= date("F", strtotime(UI::getLongDate($this->intShowUrl) . '-01'));
                    $this->strShowNameSpoken .= ' ' . UI::getPronouncableDate(substr($this->intShowUrl, 0, 4));
                    break;
                case 'daily':
                    $this->strShowName = 'The ' . ConfigBroker::getConfig('Site Name', 'CCHits.net');
                    $this->strShowName .= ' ' . ConfigBroker::getConfig('Daily Show Name', 'Daily Exposure Show');
                    $this->strShowName .= ' for ';
                    $this->strShowName .= UI::getLongDate($this->intShowUrl);
                    $this->strShowNameSpoken = 'The ' . ConfigBroker::getConfig('Spoken Site Name', 'Cee Cee Hits dot Net');
                    $this->strShowNameSpoken .= ' ' . ConfigBroker::getConfig('Spoken Daily Show Name', 'Daily Exposure Show');
                    $this->strShowNameSpoken .= ' for ';
                    $this->strShowNameSpoken .= date("jS F", strtotime(UI::getLongDate($this->intShowUrl)));
                    $this->strShowNameSpoken .= ' ' . UI::getPronouncableDate(substr($this->intShowUrl, 0, 4));
                    break;
                case 'weekly':
                    $this->strShowName = 'The ' . ConfigBroker::getConfig('Site Name', 'CCHits.net');
                    $this->strShowName .= ConfigBroker::getConfig('Weekly Show Name', 'Weekly Review Show');
                    $this->strShowName .= ' for ';
                    $this->strShowName .= UI::getLongDate($this->intShowUrl);
                    $this->strShowNameSpoken = 'The ' . ConfigBroker::getConfig('Spoken Site Name', 'Cee Cee Hits dot Net');
                    $this->strShowNameSpoken .= ' ' . ConfigBroker::getConfig('Spoken Weekly Show Name', 'Weekly Review Show');
                    $this->strShowNameSpoken .= ' for ';
                    $this->strShowNameSpoken .= date("jS F", strtotime(UI::getLongDate($this->intShowUrl)));
                    $this->strShowNameSpoken .= ' ' . UI::getPronouncableDate(substr($this->intShowUrl, 0, 4));
                    break;
                }
            }
        }
    }

    /**
     * Return an array of the associated tracks
     *
     * @return false|array Associated tracks
     */
    function get_arrTracks()
    {
        if (is_null($this->arrTracks)) {
            switch($this->enumShowType) {
            case 'daily':
                $this->arrTracks = TracksBroker::getTracksByShowID($this->intShowID);
                // HACK: Transition to having the dailyshow entry in the showtracks table
                if ($this->arrTracks == false) {
                    $this->arrTracks = array(TrackBroker::getTrackByDailyShowDate($this->intShowUrl));
                }
            default:
                $this->arrTracks = TracksBroker::getTracksByShowID($this->intShowID);
                break;
            }
        }
        return $this->arrTracks;
    }

    /**
     * Add the collected tracks to the getSelf function
     *
     * @return The amassed data from this function
     */
    function getSelf()
    {
        $return = parent::getSelf();
        $counter = 0;
        $showname = $this->strShowName;
        $first = true;
        if ($this->booleanFull == true) {
            $showname .= ' featuring ';
            $showname_tracks = '';
            foreach ($this->get_arrTracks() as $objTrack) {
                $return['arrTracks'][++$counter] = $objTrack->getSelf();
                if ($counter <= 5) {
                    if ($showname_tracks != '') {
                        $showname_tracks .= ', ';
                    }
                    $showname_tracks .= $objTrack->get_strTrackName() . ' by ' . $objTrack->get_objArtist()->get_strArtistName();
                } elseif ($first) {
                    $first = false;
                    $showname_tracks .= ' and more...';
                }
            }
            $showname .= $showname_tracks;
            $return['player_data'] = array('name' => $showname, 'free'=>'true', 'mp3' => $this->strShowFileMP3, 'oga' => $this->strShowFileOGG, 'link' => $this->strShowUrl);
        }
        $return['strShowNameSpoken'] = $this->strShowNameSpoken;
        $return['strShowUrlSpoken'] = $this->strShowUrlSpoken;
        return $return;
    }

    /**
     * Set the Show Name for external shows.
     *
     * @param string $strShowName Show Name
     *
     * @return void
     */
    function set_strShowName($strShowName = "")
    {
        if ($this->strShowName != $strShowName) {
            $this->strShowName = $strShowName;
            $arrChanges[] = 'strShowName';
        }
    }

    /**
     * Set the Show URL for external shows.
     *
     * @param string $strShowUrl Show URL for Shownotes
     *
     * @return void
     */
    function set_strShowUrl($strShowUrl = "")
    {
        if ($this->strShowUrl != $strShowUrl) {
            $this->strShowUrl = $strShowUrl;
            $arrChanges[] = 'strShowUrl';
        }
    }

    /**
     * Set the date the show is going out.
     *
     * Used to generate the show URLs on internal shows.
     *
     * @param integer $intShowUrl Show Date
     *
     * @return void
     */
    function set_intShowUrl($intShowUrl = 0)
    {
        if ($this->intShowUrl != $intShowUrl) {
            $this->intShowUrl = $intShowUrl;
            $arrChanges[] = 'intShowUrl';
        }
    }

    /**
     * Set the Show type.
     *
     * Technically it'll have to match the enum on the database.
     *
     * @param string $enumShowType Show Type
     *
     * @return void
     */
    function set_enumShowType($enumShowType = "")
    {
        if ($this->enumShowType != $enumShowType) {
            $this->enumShowType = $enumShowType;
            $arrChanges[] = 'enumShowType';
        }
    }

    /**
     * Set the UserID of the person who uploaded the show
     *
     * @param integer $intUserID The UserID
     *
     * @return void
     */
    function set_intUserID($intUserID = 0)
    {
        if ($this->intUserID != $intUserID) {
            $this->intUserID = $intUserID;
            $arrChanges[] = 'intUserID';
        }
    }

    /**
     * Set the length of the show
     *
     * @param time $timeLength The show length
     *
     * @return void
     */
    function set_timeLength($timeLength = "")
    {
        if ($this->timeLength != $timeLength) {
            $this->timeLength = $timeLength;
            $arrChanges[] = 'timeLength';
        }
    }

    /**
     * Set the SHA1 hash of the show
     *
     * @param string $shaHash SHA1 hash of the audio file
     *
     * @return void
     */
    function set_shaHash($shaHash = "")
    {
        if ($this->shaHash != $shaHash) {
            $this->shaHash = $shaHash;
            $arrChanges[] = 'shaHash';
        }
    }

    /**
     * Set the URL for where to get involved in a conversation about the show.
     *
     * @param string $strCommentUrl The URL for the Conversation
     *
     * @return void
     */
    function set_strCommentUrl($strCommentUrl = "")
    {
        if ($this->strCommentUrl != $strCommentUrl) {
            $this->strCommentUrl = $strCommentUrl;
            $arrChanges[] = 'strCommentUrl';
        }
    }

    /**
     * Set the jsonAudioLayout.
     *
     * Strictly speaking, it's just a string here, but as the only shows
     * where it makes a difference are the internal ones, and we're the
     * ones generating the show, it shouldn't matter.
     *
     * @param json $jsonAudioLayout The JSON layout of the show
     *
     * @return void
     */
    function set_jsonAudioLayout($jsonAudioLayout = "")
    {
        if ($this->jsonAudioLayout != $jsonAudioLayout) {
            $this->jsonAudioLayout = $jsonAudioLayout;
            $arrChanges[] = 'jsonAudioLayout';
        }
    }

    /**
     * Set the date the show was added
     *
     * @param datetime $datDateAdded Date the show was added
     *
     * @return void
     */
    function set_datDateAdded($datDateAdded = "")
    {
        if ($this->datDateAdded != $datDateAdded) {
            $this->datDateAdded = $datDateAdded;
            $arrChanges[] = 'datDateAdded';
        }
    }

    /**
     * Return the ShowID
     *
     * @return integer
     */
    function get_intShowID()
    {
        return $this->intShowID;
    }

    /**
     * Return the Show Name - external shows only
     *
     * @return string
     */
    function get_strShowName()
    {
        return $this->strShowName;
    }

    /**
     * Return the URL for external tracks
     *
     * @return string
     */
    function get_strShowUrl()
    {
        return $this->strShowUrl;
    }

    /**
     * Return the date of the show to generate the URLs - internal shows only
     *
     * @return integer
     */
    function get_intShowUrl()
    {
        return $this->intShowUrl;
    }

    /**
     * Return the show type
     *
     * @return string
     */
    function get_enumShowType()
    {
        return $this->enumShowType;
    }

    /**
     * Return the userID of the person creating the show
     *
     * @return integer
     */
    function get_intUserID()
    {
        return $this->intUserID;
    }

    /**
     * Return the length of the show
     *
     * @return time
     */
    function get_timeLength()
    {
        return $this->timeLength;
    }

    /**
     * Return the hash of the show MP3
     *
     * @return string
     */
    function get_shaHash()
    {
        return $this->shaHash;
    }

    /**
     * Return the URL to the comment stream
     *
     * @return string
     */
    function get_strCommentUrl()
    {
        return $this->strCommentUrl;
    }

    /**
     * Return the jsonAudioLayout
     *
     * @return string
     */
    function get_jsonAudioLayout()
    {
        return $this->jsonAudioLayout;
    }

    /**
     * Return the date the show was added.
     *
     * @return datetime
     */
    function get_datDateAdded()
    {
        return $this->datDateAdded;
    }
}
