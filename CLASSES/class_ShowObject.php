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
    protected $strShowUrl = "";
    protected $intShowUrl = 0;
    protected $enumShowType = "";
    protected $intUserID = 0;
    protected $timeLength = "";
    protected $shaHash = "";
    protected $strCommentUrl = "";
    protected $jsonAudioLayout = "";
    protected $datDateAdded = "";

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
        Debug::Log(get_class() . "::__construct()", "DEBUG");

        if ($this->intShowID > 0) {
            $intShowID = $this->intShowID;
        }

        if ($this->intShowUrl != 0) {
            if ($this->strShowUrl == "") {
                $this->strShowUrl = "http://cchits.net/" . $this->enumShowType . "/" . $this->intShowUrl;
            }
            if ($this->strShowName == "") {
                switch($this->enumShowType) {
                case 'daily':
                    $this->strShowName = "The CCHits Daily Exposure Show for ";
                    break;
                case 'weekly':
                    $this->strShowName = "The CCHits Weekly Review Show for ";
                    break;
                case 'monthly':
                    $this->strShowName = "The CCHits Monthly Chart Show for ";
                    break;
                }
                $this->strShowName .= substr($this->intShowUrl, 0, 4) . "-";
                $this->strShowName .= substr($this->intShowUrl, 4, 2) . "-";
                $this->strShowName .= substr($this->intShowUrl, 6, 2);
            }
        }

        $this->arrTracks = TracksBroker::getTracksByShowID($this->intShowID);
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
