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
    /**
     * This function finds a track by it's intTrackID.
     *
     * @param int $intTrackID Track ID to search for
     *
     * @return object|false TrackObject or false if not existing
     */
    public function getTrackByID($intTrackID = 0)
    {
        $db = CF::getFactory()->getConnection();
        try {
            $sql = "SELECT * FROM tracks WHERE intTrackID = ? LIMIT 1";
            $query = $db->prepare($sql);
            $query->execute(array($intTrackID));
            return $query->fetchObject('TrackObject');
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
    }

    // TODO: Use intPage, intSize and make these null by default, using parameters to set them

    /**
     * This function finds a track by it's name.
     * This search removes all spaces and then checks for the name
     * including any spaces
     *
     * @param string $strTrackName The exact Track name to search for
     * @param int    $intStart     The start "page" number
     * @param int    $intSize      The size of each page
     *
     * @return array|false An array of TrackObject or false if the item doesn't exist
     */
    public function getTrackByExactName(
        $strTrackName = "",
        $intStart = 0,
        $intSize = 25
    ) {
        $db = CF::getFactory()->getConnection();
        try {
            $sql = "SELECT * FROM tracks WHERE strTrackName REGEXP ?";
            $pagestart = ($intStart*$intSize);
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
            $item = $query->fetchObject('TrackObject');
            if ($item == false) {
                return false;
            } else {
                $return[] = $item;
                while ($item = $query->fetchObject('TrackObject')) {
                    $return[] = $item;
                }
                return $return;
            }
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
    }

    // TODO: Use intPage, intSize and make these null by default, using parameters to set them

    /**
     * This function finds a track by its name.
     * This search removes all spaces and then checks for the name
     * including any spaces
     *
     * @param string $strTrackName The part of the Track name to search for
     * @param int    $intStart     The start "page" number
     * @param int    $intSize      The size of each page
     *
     * @return array|false An array of TrackObject or false if the item doesn't exist
     */
    public function getTrackByPartialName(
        $strTrackName = "",
        $intStart = 0,
        $intSize = 25
    ) {
        $db = CF::getFactory()->getConnection();
        try {
            $sql = "SELECT * FROM tracks WHERE strTrackName REGEXP ?";
            $pagestart = ($intStart*$intSize);
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
            $item = $query->fetchObject('TrackObject');
            if ($item == false) {
                return false;
            } else {
                $return[] = $item;
                while ($item = $query->fetchObject('TrackObject')) {
                    $return[] = $item;
                }
                return $return;
            }
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
    }

    // TODO: Use intPage, intSize and make these null by default, using parameters to set them

    /**
     * This function finds a track by its url.
     * This search removes all spaces and then checks for the name
     * including any spaces
     *
     * @param string $strTrackUrl The part of the Track name to search for
     * @param int    $intStart    The start "page" number
     * @param int    $intSize     The size of each page
     *
     * @return array|false An array of TrackObject or false if the item doesn't exist
     */
    public function getTrackByPartialUrl(
        $strTrackUrl = "",
        $intStart = 0,
        $intSize = 25
    ) {
        $db = CF::getFactory()->getConnection();
        try {
            $sql = "SELECT * FROM tracks WHERE strTrackUrl LIKE ?";
            $pagestart = ($intStart*$intSize);
            $query = $db->prepare($sql . " LIMIT " . $pagestart . ", $intSize");
            $query->execute(array("$strTrackUrl%"));
            $item = $query->fetchObject('TrackObject');
            if ($item == false) {
                return false;
            } else {
                $return[] = $item;
                while ($item = $query->fetchObject('TrackObject')) {
                    $return[] = $item;
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
        $db = CF::getFactory()->getConnection();
        try {
            $sql = "SELECT * FROM tracks WHERE md5FileHash = ? LIMIT 1";
            $query = $db->prepare($sql);
            $query->execute(array($md5FileHash));
            return $query->fetchObject('TrackObject');
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Ideally, this will be removed from the code ASAP, however, for the meantime
     * this function looks for the datDailyShow column and finds the track with
     * this date.
     *
     * @param integer $intDate The date to look for
     *
     * @return object|false TrackObject or false if not existing
     */
    function getTrackByDailyShowDate($intDate = '')
    {
        $db = CF::getFactory()->getConnection();
        try {
            $sql = "SELECT * FROM tracks WHERE datDailyShow = ? LIMIT 1";
            $query = $db->prepare($sql);
            $query->execute(array($intDate));
            return $query->fetchObject('TrackObject');
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
    }
}
