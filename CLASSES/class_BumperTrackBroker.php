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
 * This class knows all the ways to find a Track Bumper.
 *
 * @category Default
 * @package  Brokers
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */

class BumperTrackBroker
{
    /**
     * Return all track bumpers
     *
     * @return array|false An array of 
     */
    public function getTrackBumpers()
    {
        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM bumpers_tracks";
            $query = $db->prepare($sql);
            $query->execute();
            $item = $query->fetchObject('BumperTrackObject');
            if ($item == false) {
                return false;
            } else {
                $return[] = $item;
                while ($item = $query->fetchObject('BumperTrackObject')) {
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
     * Return a collection of the bumpers
     *
     * @param int     $intTrackID The track the bumper is for
     * @param string  $position   The position of the bumper - optional
     * @param boolean $boolRandom Whether to pick one item at random from the selection
     *
     * @return array|false An array of BumperTrackObject or false if the item doesn't exist
     */
    public function getTrackBumperByTrack($intTrackID = 0, $position = '', $boolRandom = false)
    {
        $db = Database::getConnection();
        try {
            if ($position != '') {
                if ($boolRandom == true) {
                    $sql = "SELECT count(intTrackBumper) AS count_intTrackBumper FROM bumpers_track WHERE intTrackID = ? AND enumPosition = ?";
                    $query->execute(array($intTrackID, $position));
                    $scale = $query->fetchColumn();
                    if ($scale == 0) {
                        return false;
                    }
                    $sql = "SELECT * FROM bumpers_track WHERE intTrackID = ? AND enumPosition = ? LIMIT " . rand(0, $scale -1) . ", 1";
                    $query->execute(array($intTrackID, $position));                    
                } else {
                    $sql = "SELECT * FROM bumpers_track WHERE intTrackID = ? AND enumPosition = ?";
                    $query->execute(array($intTrackID, $position));
                }
            } else {
                $sql = "SELECT * FROM bumpers_track WHERE intTrackID = ?";
                $query->execute(array($intTrackID));
            }
            $item = $query->fetchObject('BumperTrackObject');
            if ($item == false) {
                return false;
            } else {
                $return[] = $item;
                while ($item = $query->fetchObject('BumperTrackObject')) {
                    $return[] = $item;
                }
                return $return;
            }
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
    }

}