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
 * This class knows all the ways to find a track.
 *
 * @category Default
 * @package  Brokers
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
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
    public static function getTotalTracks()
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
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'error'=>$query->errorInfo()), true), 1);
            }
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
     * @return TrackObject|false TrackObject or false if not existing
     */
    public static function getTrackByID($intTrackID = 0)
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
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>$intTrackID, 'error'=>$query->errorInfo()), true), 1);
            }
            $handler->arrTracks[$intTrackID] = $query->fetchObject('TrackObject');
            return $handler->arrTracks[$intTrackID];
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
    public static function getTrackByExactName(
        $strTrackName = "",
        $intPage = null,
        $intSize = null
    ) {
        $arrUri = UI::getUri();
        if ($intPage == null and isset($arrUri['parameters']['page']) and $arrUri['parameters']['page'] > 0) {
            $intPage = $arrUri['parameters']['page'];
        } elseif ($intPage == null) {
            $intPage = 0;
        }
        if ($intSize == null and isset($arrUri['parameters']['size']) and $arrUri['parameters']['size'] > 0) {
            $intSize = $arrUri['parameters']['size'];
        } elseif ($intSize == null) {
            $intSize = 25;
        }

        if ($strSort == null and isset($arrUri['parameters']['sort'])) {
            if ($arrUri['parameters']['sort'] == 'intTrackID' ||
                $arrUri['parameters']['sort'] == 'strTrackName') {
                $strSort = $arrUri['parameters']['sort'];
            } else {
                $strSort = 'intTrackID';
            }
        } else {
            $strSort = 'intTrackID';
        }

        if ($strDirection == null and isset($arrUri['parameters']['direction'])) {
            if ($arrUri['parameters']['direction'] == 'desc') {
                $strDirection = 'DESC';
            } else {
                $strDirection = 'ASC';
            }
        } else {
            $strDirection = 'ASC';
        }

        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM tracks WHERE strTrackName REGEXP ? OR strTrackName REGEXP ? ORDER BY " . $strSort . " " . $strDirection;
            $pagestart = ($intPage*$intSize);
            $query = $db->prepare($sql . " LIMIT " . $pagestart . ", $intSize");
            // This snippet from http://www.php.net/manual/en/function.str-split.php
            preg_match_all('`.`u', substr(json_encode(array($strTrackName)), 2, -2), $arr);
            $arr = array_chunk($arr[0], 1);
            $arr = array_map('implode', $arr);
            $strTrackName = "";
            foreach ($arr as $chrTrackName) {
                if (trim($chrTrackName) != '') {
                    switch($chrTrackName) {
                    case '.':
                    case '?':
                    case '*':
                    case '(':
                    case ')':
                    case '{':
                    case '}':
                    case '[':
                    case ']':
                    case '|':
                    case '/':
                    case '\\':
                        $chrTrackName = '\\' . $chrTrackName;
                        break;
                    }
                    $strTrackName .= "[[:space:]]*$chrTrackName";
                }
            }
            $query->execute(array("\"{$strTrackName}[[:space:]]*\"", "{$strTrackName}[[:space:]]*"));
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>array("\"{$strTrackName}[[:space:]]*\"", "{$strTrackName}[[:space:]]*"), 'error'=>$query->errorInfo()), true), 1);
            }
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
            $intPage = $arrUri['parameters']['page'];
        } elseif ($intPage == null) {
            $intPage = 0;
        }
        if ($intSize == null and isset($arrUri['parameters']['size']) and $arrUri['parameters']['size'] > 0) {
            $intSize = $arrUri['parameters']['size'];
        } elseif ($intSize == null) {
            $intSize = 25;
        }
        if ($strSort == null and isset($arrUri['parameters']['sort'])) {
            if ($arrUri['parameters']['sort'] == 'intTrackID' ||
                $arrUri['parameters']['sort'] == 'strTrackName') {
                $strSort = $arrUri['parameters']['sort'];
            } else {
                $strSort = 'intTrackID';
            }
        } else {
            $strSort = 'intTrackID';
        }

        if ($strDirection == null and isset($arrUri['parameters']['direction'])) {
            if ($arrUri['parameters']['direction'] == 'desc') {
                $strDirection = 'DESC';
            } else {
                $strDirection = 'ASC';
            }
        } else {
            $strDirection = 'ASC';
        }

        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM tracks WHERE strTrackName REGEXP ? ORDER BY " . $strSort . " " . $strDirection . " "; 
            $pagestart = ($intPage*$intSize);
            $query = $db->prepare($sql . " LIMIT " . $pagestart . ", $intSize");
            // This snippet from http://www.php.net/manual/en/function.str-split.php
            preg_match_all('`.`u', substr(json_encode(array($strTrackName)), 2, -2), $arr);
            $arr = array_chunk($arr[0], 1);
            $arr = array_map('implode', $arr);
            $strTrackName = "";
            foreach ($arr as $chrTrackName) {
                if (trim($chrTrackName) != '') {
                    switch($chrTrackName) {
                    case '.':
                    case '?':
                    case '*':
                    case '(':
                    case ')':
                    case '{':
                    case '}':
                    case '[':
                    case ']':
                    case '|':
                    case '/':
                    case '\\':
                        $chrTrackName = '\\' . $chrTrackName;
                        break;
                    }
                    $strTrackName .= "[[:space:]]*$chrTrackName";
                }
            }
            $query->execute(array(".*{$strTrackName}[[:space:]]*.*"));
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>".*{$strTrackName}[[:space:]]*.*", 'error'=>$query->errorInfo()), true), 1);
            }
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
     * This function finds a track by the first part of the url.
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
            $intPage = $arrUri['parameters']['page'];
        } elseif ($intPage == null) {
            $intPage = 0;
        }
        if ($intSize == null and isset($arrUri['parameters']['size']) and $arrUri['parameters']['size'] > 0) {
            $intSize = $arrUri['parameters']['size'];
        } elseif ($intSize == null) {
            $intSize = 25;
        }

        if ($strSort == null and isset($arrUri['parameters']['sort'])) {
            if ($arrUri['parameters']['sort'] == 'intTrackID' ||
                $arrUri['parameters']['sort'] == 'strTrackName') {
                $strSort = $arrUri['parameters']['sort'];
            } else {
                $strSort = 'intTrackID';
            }
        } else {
            $strSort = 'intTrackID';
        }

        if ($strDirection == null and isset($arrUri['parameters']['direction'])) {
            if ($arrUri['parameters']['direction'] == 'desc') {
                $strDirection = 'DESC';
            } else {
                $strDirection = 'ASC';
            }
        } else {
            $strDirection = 'ASC';
        }

        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM tracks WHERE strTrackUrl LIKE ? or strTrackUrl LIKE ? ORDER BY " . $strSort . " " . $strDirection;
            $pagestart = ($intPage*$intSize);
            $query = $db->prepare($sql . " LIMIT " . $pagestart . ", $intSize");
            // For tracks have this field as a serialized json array, in which "/" are escaped with "\", 
            // ie : {"0":"https:\/\/soundcloud.com\/the-easton-ellises\/05-endorphin",
            // "preferred":"http:\/\/archive.org\/details\/enrmp272_the_easton_ellises_-_ep_one"}
            // MySQL wants "\"s to be escaped, so that's "\\" for one "\". But PHP also wants "\"s to be escaped... 
            // Hence "\\\\" : this is sent as "\\" to MySQL which then interprets that as the literal "\" character.
            // BUT... for tracks with only one URL, it is stored as a non escaped string, ie : http://www.jamendo.com/en/track/806979
            // Hence the "or" operator in the $sql above.
            // Addendum : some tracks have this field stored as a JSON array, ie : ["http:\/\/www.jamendo.com\/en\/track\/1026956"].
            // The replacement bellow will also work for those.
            $strEscapedTrackUrl = str_replace("/", "\\\\/", $strTrackUrl);
            $query->execute(array("%$strTrackUrl%", "%$strEscapedTrackUrl%"));
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>array("\"$strTrackUrl%", $strTrackUrl . '%'), 'error'=>$query->errorInfo()), true), 1);
            }
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
     * This function finds a track by the url.
     *
     * @param string $strTrackUrl The part of the Track name to search for
     *
     * @return array|false An array of TrackObject or false if the item doesn't exist
     */
    public static function getTrackByExactUrl($strTrackUrl = "")
    {
        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM tracks WHERE strTrackUrl LIKE ? OR strTrackUrl = ?";
            $query = $db->prepare($sql);
            $query->execute(array("%\"$strTrackUrl\"%", $strTrackUrl));
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>array('%"' . $strTrackUrl . '"%', $strTrackUrl), 'error'=>$query->errorInfo()), true), 1);
            }
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
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>$md5FileHash, 'error'=>$query->errorInfo()), true), 1);
            }
            $handler = self::getHandler();
            $item = $query->fetchObject('TrackObject');
            if ($item == false) {
                return false;
            } else {
                $handler->arrTracks[$item->get_intTrackID()] = $item;
                return $item;
            }
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
    }
}
