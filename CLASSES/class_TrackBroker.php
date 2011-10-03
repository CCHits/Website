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
 * This class knows all the ways to find a track.
 *
 * @category Default
 * @package  Brokers
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */

class TrackBroker
{
    protected static $handler = null;
    protected $arrTracks = array();
    protected $intTotalTracks = 0;

    /**
     * An internal function to make this a singleton
     *
     * @return object This class by itself.
     */
    private static function getHandler()
    {
        if (self::$handler == null) {
            self::$handler = new self();
        }
        return self::$handler;
    }

    /**
     * Return the total number of tracks in the system
     *
     * @return integer Total number of tracks
     */
    function getTotalTracks()
    {
        $th = self::getHandler();
        if (isset($th->intTotalTracks) and $th->intTotalTracks != 0) {
            return $th->intTotalTracks;
        }
        $db = Database::getConnection();
        try {
            $sql = "SELECT COUNT(intTrackID) as totalTracks FROM tracks";
            $query = $db->prepare($sql);
            $query->execute();
            $th->intTotalTracks = $query->fetchColumn();
            return $th->intTotalTracks;
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
    }

    /**
     * This function finds a track by it's intTrackID.
     *
     * @param integer $intTrackID Track ID to search for
     *
     * @return object|false TrackObject or false if not existing
     */
    public function getTrackByID($intTrackID = 0)
    {
        $handler = self::getHandler();
        if (isset($handler->arrTracks[$intTrackID]) and $handler->arrTracks[$intTrackID] != false) {
            return $handler->arrTracks[$intTrackID];
        }
        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM tracks WHERE intTrackID = ? LIMIT 1";
            $query = $db->prepare($sql);
            $query->execute(array($intTrackID));
            $th->arrTracks[$intTrackID] = $query->fetchObject('TrackObject');
            return $th->arrTracks[$intTrackID];
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
    }

    /**
     * This function finds a track by it's name.
     * This search removes all spaces and then checks for the name
     * including any spaces
     *
     * @param string  $strTrackName The exact Track name to search for
     * @param integer $intPage      The start "page" number
     * @param integer $intSize      The size of each page
     *
     * @return array|false An array of TrackObject or false if the item doesn't exist
     */
    public function getTrackByExactName(
        $strTrackName = "",
        $intPage = null,
        $intSize = null
    ) {
        $arrUri = UI::getUri();
        if ($intPage == null and isset($arrUri['parameters']['page']) and $arrUri['parameters']['page'] > 0) {
            $page = $arrUri['parameters']['page'];
        } elseif ($intPage == null) {
            $page = 0;
        }
        if ($intSize == null and isset($arrUri['parameters']['size']) and $arrUri['parameters']['size'] > 0) {
            $size = $arrUri['parameters']['size'];
        } elseif ($intSize == null) {
            $size = 25;
        }

        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM tracks WHERE strTrackName REGEXP ?";
            $pagestart = ($intPage*$intSize);
            $query = $db->prepare($sql . " LIMIT " . $pagestart . ", $intSize");
            // This snippet from http://www.php.net/manual/en/function.str-split.php
            preg_match_all('`.`u', $strTrackName, $arr);
            $arr = array_chunk($arr[0], 1);
            $arr = array_map('implode', $arr);
            $strTrackName = "";
            foreach ($arr as $chrTrackName) {
                if (trim($chrTrackName) != '') {
                    $strTrackName .= "[:space:]*$chrTrackName";
                }
            }
            $query->execute(array("{$strTrackName}[:space:]*"));
            $handler = self::getHandler();
            $item = $query->fetchObject('TrackObject');
            if ($item == false) {
                return false;
            } else {
                while ($item != false) {
                    $return[] = $item;
                    $handler->arrTracks[$item->get_intTrackID()] = $item;
                    $item = $query->fetchObject('TrackObject');
                }
                return $return;
            }
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
    }
    /**
     * This function finds a track by its name.
     * This search removes all spaces and then checks for the name
     * including any spaces
     *
     * @param string  $strTrackName The part of the Track name to search for
     * @param integer $intPage      The start "page" number
     * @param integer $intSize      The size of each page
     *
     * @return array|false An array of TrackObject or false if the item doesn't exist
     */
    public function getTrackByPartialName(
        $strTrackName = "",
        $intPage = null,
        $intSize = null
    ) {
        $arrUri = UI::getUri();
        if ($intPage == null and isset($arrUri['parameters']['page']) and $arrUri['parameters']['page'] > 0) {
            $page = $arrUri['parameters']['page'];
        } elseif ($intPage == null) {
            $page = 0;
        }
        if ($intSize == null and isset($arrUri['parameters']['size']) and $arrUri['parameters']['size'] > 0) {
            $size = $arrUri['parameters']['size'];
        } elseif ($intSize == null) {
            $size = 25;
        }

        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM tracks WHERE strTrackName REGEXP ?";
            $pagestart = ($intPage*$intSize);
            $query = $db->prepare($sql . " LIMIT " . $pagestart . ", $intSize");
            // This snippet from http://www.php.net/manual/en/function.str-split.php
            preg_match_all('`.`u', $strTrackName, $arr);
            $arr = array_chunk($arr[0], 1);
            $arr = array_map('implode', $arr);
            $strTrackName = "";
            foreach ($arr as $chrTrackName) {
                if (trim($chrTrackName) != '') {
                    $strTrackName .= "[:space:]*$chrTrackName";
                }
            }
            $query->execute(array(".*{$strTrackName}[:space:]*.*"));
            $handler = self::getHandler();
            $item = $query->fetchObject('TrackObject');
            if ($item == false) {
                return false;
            } else {
                while ($item != false) {
                    $return[] = $item;
                    $handler->arrTracks[$item->get_intTrackID()] = $item;
                    $item = $query->fetchObject('TrackObject');
                }
                return $return;
            }
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
    }
    /**
     * This function finds a track by its url.
     * This search removes all spaces and then checks for the name
     * including any spaces
     *
     * @param string  $strTrackUrl The part of the Track name to search for
     * @param integer $intPage     The start "page" number
     * @param integer $intSize     The size of each page
     *
     * @return array|false An array of TrackObject or false if the item doesn't exist
     */
    public function getTrackByPartialUrl(
        $strTrackUrl = "",
        $intPage = null,
        $intSize = null
    ) {
        $arrUri = UI::getUri();
        if ($intPage == null and isset($arrUri['parameters']['page']) and $arrUri['parameters']['page'] > 0) {
            $page = $arrUri['parameters']['page'];
        } elseif ($intPage == null) {
            $page = 0;
        }
        if ($intSize == null and isset($arrUri['parameters']['size']) and $arrUri['parameters']['size'] > 0) {
            $size = $arrUri['parameters']['size'];
        } elseif ($intSize == null) {
            $size = 25;
        }

        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM tracks WHERE strTrackUrl LIKE ?";
            $pagestart = ($intPage*$intSize);
            $query = $db->prepare($sql . " LIMIT " . $pagestart . ", $intSize");
            $query->execute(array("$strTrackUrl%"));
            $handler = self::getHandler();
            $item = $query->fetchObject('TrackObject');
            if ($item == false) {
                return false;
            } else {
                while ($item != false) {
                    $return[] = $item;
                    $handler->arrTracks[$item->get_intTrackID()] = $item;
                    $item = $query->fetchObject('TrackObject');
                }
                return $return;
            }
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
    }

    /**
     * This function finds a track by it's md5 sum.
     *
     * @param string $md5FileHash The pre-generated MD5 hash of a file
     *
     * @return object|false TrackObject or false if not existing
     */
    public function getTrackByMD5($md5FileHash = "")
    {
        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM tracks WHERE md5FileHash = ? LIMIT 1";
            $query = $db->prepare($sql);
            $query->execute(array($md5FileHash));
            $handler = self::getHandler();
            $item = $query->fetchObject('TrackObject');
            $handler->arrShows[$item->get_intTrackID()] = $item;
            return $item;
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
    }
}
