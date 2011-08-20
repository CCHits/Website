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
 * This class deals with all things ShowTrack related.
 *
 * @category Default
 * @package  Brokers
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */

class ShowTrackBroker
{
    /**
     * getShowTracksByShowID returns a collection of show tracks.
     *
     * @param integer $intShowID The ShowID to get tracks linked to
     *
     * @return array Collection of ShowTrackObjects
     */
    public function getShowTracksByShowID($intShowID = 0)
    {
        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM showtracks WHERE intShowID = ? ORDER BY intPartID ASC";
            $query = $db->prepare($sql);
            $query->execute(array($intShowID));
            $item = $query->fetchObject('ShowTrackObject');
            if ($item == false) {
                return false;
            } else {
                $return[] = $item;
                while ($item = $query->fetchObject('ShowTrackObject')) {
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
     * getShowTracksByShowID returns confirmation about whether a track is in a show.
     *
     * @param integer $intShowID  The ShowID to get tracks linked to
     * @param integer $intTrackID The TrackID to look for
     *
     * @return Object|boolean The ShowTrack object OR false
     */
    public function getShowTracksByShowTrackID($intShowID = 0, $intTrackID = 0)
    {
        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM showtracks WHERE intShowID = ? AND intTrackID = ? ORDER BY intPartID ASC";
            $query = $db->prepare($sql);
            $query->execute(array($intShowID, $intTrackID));
            return $query->fetchObject('ShowTrackObject');
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get shows by Track ID
     *
     * @param integer $intTrackID The TrackID to look for
     *
     * @return Array|false An array of showtrack items, or false
     */
    public function getShowTracksByTrackID($intTrackID = 0)
    {
        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM showtracks WHERE intTrackID = ? ORDER BY intShowID ASC";
            $query = $db->prepare($sql);
            $query->execute(array($intTrackID));
            $item = $query->fetchObject('ShowTrackObject');
            if ($item == false) {
                return false;
            } else {
                $return[] = $item;
                while ($item = $query->fetchObject('ShowTrackObject')) {
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
     * This function changes any show which contains a duplicate ID and replaces it with the new track number.
     *
     * @param integer $intOldTrackID The Duplicated Track
     * @param integer $intNewTrackID The Original Track
     *
     * @return boolean Worked or it didn't
     */
    public function ChangeTrackID($intOldTrackID = 0, $intNewTrackID = 0)
    {
        $db = Database::getConnection();
        try {
            $sql = "UPDATE showtracks SET intTrackID = ? WHERE intTrackID = ?";
            $query = $db->prepare($sql);
            $query->execute(array($intNewTrackID, $intOldTrackID));
            return true;
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
    }
}