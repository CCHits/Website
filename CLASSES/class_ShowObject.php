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
    protected $strShowName = null;
    protected $strShowNameSpoken = "";
    protected $strShowUrl = null;
    protected $strShowUrlSpoken = "";
    protected $intShowUrl = 0;
    protected $enumShowType = null;
    protected $intUserID = 0;
    protected $timeLength = "";
    protected $shaHash = null;
    protected $strCommentUrl = null;
    protected $jsonAudioLayout = null;
    protected $datDateAdded = "";
    protected $strShowFileMP3 = "";
    protected $strShowFileOGA = "";
    protected $strShowFileM4A = "";
    protected $arrTracks = null;
    // Functional switches extending GenericObject
    protected $booleanFull = true;

    /**
     * Delete the show if there are no tracks linked to it.
     *
     * @return boolean Action completed successfully
     */
    function cancel()
    {
        if (count($this->get_arrTracks) == 0) {
            $db = Database::getConnection();
            try {
                $sql = "DELETE FROM shows WHERE intShowID = ?";
                $query = $db->prepare($sql);
                $query->execute(array($this->intShowID));
                // This section of code, thanks to code example here:
                // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
                if ($query->errorCode() != 0) {
                    throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>$this->intProcessingID, 'error'=>$query->errorInfo()), true), 1);
                }
                return true;
            } catch(Exception $e) {
                error_log("SQL Died: " . $e->getMessage());
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Return an array of the associated tracks
     *
     * @param boolean $reset Force a reset of the arrTracks listing.
     *
     * @return array Associated tracks
     */
    function get_arrTracks($reset = false)
    {
        if (is_null($this->arrTracks) or (is_array($this->arrTracks) and count($this->arrTracks) == 0) or $reset = true) {
            $this->arrTracks = TracksBroker::getTracksByShowIDOrderedByPartID($this->intShowID);
        }
        if (!is_array($this->arrTracks)) {
            $this->arrTracks = array();
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
        if ($this->intShowUrl != 0) {
            if ($this->strShowUrl == "") {
                $this->strShowUrl = ConfigBroker::getConfig('baseURL', 'http://cchits.net') . '/' . $this->enumShowType . "/" . $this->intShowUrl;
                $this->strShowUrlSpoken = ConfigBroker::getConfig("Spoken Base URL", "Cee Cee Hits dot net") . ' slash ' . $this->enumShowType . " slash " . UI::getPronouncableDate($this->intShowUrl);
                if (file_exists(ConfigBroker::getConfig('fileBase', '/var/www/media') . '/' . $this->enumShowType . "/" . $this->intShowUrl . '.mp3')) {
                    $this->strShowFileMP3 = ConfigBroker::getConfig('baseURL', 'http://cchits.net') . '/media/' . $this->enumShowType . "/" . $this->intShowUrl . '.mp3';
                }
                if (file_exists(ConfigBroker::getConfig('fileBase', '/var/www/media') . '/' . $this->enumShowType . "/" . $this->intShowUrl . '.oga')) {
                    $this->strShowFileOGA = ConfigBroker::getConfig('baseURL', 'http://cchits.net') . '/media/' . $this->enumShowType . "/" . $this->intShowUrl . '.oga';
                }
                if (file_exists(ConfigBroker::getConfig('fileBase', '/var/www/media') . '/' . $this->enumShowType . "/" . $this->intShowUrl . '.m4a')) {
                    $this->strShowFileM4A = ConfigBroker::getConfig('baseURL', 'http://cchits.net') . '/media/' . $this->enumShowType . "/" . $this->intShowUrl . '.m4a';
                }
            }
            if ($this->strShowName == "") {
                switch($this->enumShowType) {
                case 'monthly':
                    $this->strShowName = 'The ' . ConfigBroker::getConfig('Site Name', 'CCHits.net');
                    $this->strShowName .= ' ' . ConfigBroker::getConfig('Monthly Show Name', 'Monthly Top Tracks Show');
                    $this->strShowName .= ' for ';
                    $this->strShowName .= UI::getLongDate($this->intShowUrl);
                    $this->strShowNameSpoken = ConfigBroker::getConfig('Spoken Monthly Show Name', 'Monthly Top Tracks Show');
                    $this->strShowNameSpoken .= ' for ';
                    $this->strShowNameSpoken .= date("F", strtotime(UI::getLongDate($this->intShowUrl) . '-01'));
                    $this->strShowNameSpoken .= ' ' . UI::getPronouncableDate(substr($this->intShowUrl, 0, 4));
                    break;
                case 'daily':
                    $this->strShowName = 'The ' . ConfigBroker::getConfig('Site Name', 'CCHits.net');
                    $this->strShowName .= ' ' . ConfigBroker::getConfig('Daily Show Name', 'Daily Exposure Show');
                    $this->strShowName .= ' for ';
                    $this->strShowName .= UI::getLongDate($this->intShowUrl);
                    $this->strShowNameSpoken = ConfigBroker::getConfig('Spoken Daily Show Name', 'Daily Exposure Show');
                    $this->strShowNameSpoken .= ' for ';
                    $this->strShowNameSpoken .= date("jS F", strtotime(UI::getLongDate($this->intShowUrl)));
                    $this->strShowNameSpoken .= ' ' . UI::getPronouncableDate(substr($this->intShowUrl, 0, 4));
                    break;
                case 'weekly':
                    $this->strShowName = 'The ' . ConfigBroker::getConfig('Site Name', 'CCHits.net');
                    $this->strShowName .= ' ' . ConfigBroker::getConfig('Weekly Show Name', 'Weekly Review Show');
                    $this->strShowName .= ' for ';
                    $this->strShowName .= UI::getLongDate($this->intShowUrl);
                    $this->strShowNameSpoken = ConfigBroker::getConfig('Spoken Weekly Show Name', 'Weekly Review Show');
                    $this->strShowNameSpoken .= ' for ';
                    $this->strShowNameSpoken .= date("jS F", strtotime(UI::getLongDate($this->intShowUrl)));
                    $this->strShowNameSpoken .= ' ' . UI::getPronouncableDate(substr($this->intShowUrl, 0, 4));
                    break;
                }
            }
        }

        $return = parent::getSelf();
        $counter = 0;
        $showname = $this->strShowName;
        $first = true;
        if ($this->booleanFull == true) {
            $this->get_arrTracks();
            $showname .= ' featuring ';
            $showname_tracks = '';
            $return['isNSFW'] = false;
            $this->get_arrTracks();
            foreach ($this->arrTracks as $objTrack) {
                $return['arrTracks'][++$counter] = $objTrack->getSelf();
                if ($this->asBoolean($objTrack->get_isNSFW())) {
                    $return['isNSFW'] = true;
                }
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
            $return['show_summary'] = $showname_tracks;
            $return['player_data'] = array(
            	'name' => $showname,
            	'free'=>'true',
            	'link' => $this->strShowUrl
            );
            if (file_exists(ConfigBroker::getConfig('fileBase', '/var/www/media') . '/' . $this->enumShowType . "/" . $this->intShowUrl . '.mp3')) {
                $return['player_data']['mp3'] = $this->strShowFileMP3;
                $return['player_data']['mp3_len'] = filesize(ConfigBroker::getConfig('fileBase', '/var/www/media') . '/' . $this->enumShowType . "/" . $this->intShowUrl . '.mp3');
            } else {
                $return['player_data']['mp3_len'] = 0;
            }
            if (file_exists(ConfigBroker::getConfig('fileBase', '/var/www/media') . '/' . $this->enumShowType . "/" . $this->intShowUrl . '.oga')) {
                $return['player_data']['oga'] = $this->strShowFileOGA;
                $return['player_data']['oga_len'] = filesize(ConfigBroker::getConfig('fileBase', '/var/www/media') . '/' . $this->enumShowType . "/" . $this->intShowUrl . '.oga');
            } else {
                $return['player_data']['oga_len'] = 0;
            }
            if (file_exists(ConfigBroker::getConfig('fileBase', '/var/www/media') . '/' . $this->enumShowType . "/" . $this->intShowUrl . '.m4a')) {
                $return['player_data']['m4a'] = $this->strShowFileM4A;
                $return['player_data']['m4a_len'] = filesize(ConfigBroker::getConfig('fileBase', '/var/www/media') . '/' . $this->enumShowType . "/" . $this->intShowUrl . '.m4a');
            } else {
                $return['player_data']['m4a_len'] = 0;
            }
            $arrShowLayout = (array) json_decode($this->jsonAudioLayout);
            if (count($arrShowLayout) > 0) {
                foreach ($arrShowLayout as $track=>$arrPositions) {
                    $arrPositions = (array) $arrPositions;
                    $showLayout[$track] = $this->arrTracks[$track]->getSelf();
                    $showLayout[$track]['start'] = $arrPositions['start'];
                    $showLayout[$track]['stop'] = $arrPositions['stop'];
                }
                $return['arrShowLayout'] = $showLayout;
            }
        }
        $return['datDateAdded'] = date('r', strtotime($this->datDateAdded));
        $return['strShowNameSpoken'] = $this->strShowNameSpoken;
        $return['strShowUrlSpoken'] = $this->strShowUrlSpoken;
        $return['strSiteNameSpoken'] = ConfigBroker::getConfig("Spoken Site Name", "Cee Cee Hits dot net");
        $return['qrcode'] = UI::InsertQRCode('/show/' . $this->intShowID);
        $return['shorturl'] = ConfigBroker::getConfig('baseURL', 'http://cchits.net') . '/s/' . UI::setLongNumber($this->intShowID);
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
            $this->arrChanges['strShowName'] = true;
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
            $this->arrChanges['strShowUrl'] = true;
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
            $this->arrChanges['intShowUrl'] = true;
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
            $this->arrChanges['enumShowType'] = true;
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
            $this->arrChanges['intUserID'] = true;
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
            $this->arrChanges['timeLength'] = true;
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
            $this->arrChanges['shaHash'] = true;
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
            $this->arrChanges['strCommentUrl'] = true;
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
            $this->arrChanges['jsonAudioLayout'] = true;
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
            $this->arrChanges['datDateAdded'] = true;
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
    
    /**
     * This function handles the uploaded files
     * 
     * @param array $array This is the $_FILES array of data.
     * 
     * @return void
     */
    function storeFiles($array)
    {
        foreach ($array as $file) {
            if ($file['error'] == 0) {
                switch($file['type']) {
                case 'audio/mpeg':
                case 'audio/mpeg3':
                case 'audio/mp3':
                    $filename = ConfigBroker::getConfig('fileBase', '/var/www/media') . '/' . $this->enumShowType . "/" . $this->intShowUrl . '.mp3';
                    move_uploaded_file($file['tmp_name'], $filename);
                    break;
                case 'audio/mp4':
                case 'audio/m4a':
                    $filename = ConfigBroker::getConfig('fileBase', '/var/www/media') . '/' . $this->enumShowType . "/" . $this->intShowUrl . '.m4a';
                    move_uploaded_file($file['tmp_name'], $filename);
                    break;
                case 'audio/ogg':
                case 'audio/oga':
                    $filename = ConfigBroker::getConfig('fileBase', '/var/www/media') . '/' . $this->enumShowType . "/" . $this->intShowUrl . '.oga';
                    move_uploaded_file($file['tmp_name'], $filename);
                    break;
                }
            }
        }
    }
}
