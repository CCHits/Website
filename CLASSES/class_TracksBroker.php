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
 * This class knows all the ways to get collections of tracks
 *
 * @category Default
 * @package  Brokers
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */

class TracksBroker
{
    /**
     * A function to retrieve all the tracks associated to an artist.
     *
     * @param integer $intArtistID The Artist we're looking for
     *
     * @return array|false An array of the Tracks, or false if the operation fails.
     */
    function getTracksByArtistID($intArtistID = 0)
    {
        $return = array();
        $db = Database::getConnection();
        try {
            $sql = "SELECT intTrackID FROM tracks WHERE intArtistID = ?";
            $query = $db->prepare($sql);
            $query->execute(array($intShowID));
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>$intShowID, 'error'=>$query->errorInfo()), true), 1);
            }
            $tracks = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($tracks != false and count($tracks)>0) {
                foreach ($tracks as $track) {
                    $temp = TrackBroker::getTrackByID($track['intTrackID']);
                    if ($temp != false) {
                        $return[] = $temp;
                    }
                }
            }
            return $return;
        } catch(Exception $e) {
            return false;
        }
    }

    /**
     * A function to retrieve all the tracks associated to a show.
     *
     * @param integer $intShowID The ShowID we're looking for
     *
     * @return array|false An array of the Tracks, or false if the operation fails.
     */
    function getTracksByShowID($intShowID = 0)
    {
        $return = array();
        $db = Database::getConnection();
        try {
            $sql = "SELECT intTrackID FROM showtracks WHERE intShowID = ?";
            $query = $db->prepare($sql);
            $query->execute(array($intShowID));
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>$intShowID, 'error'=>$query->errorInfo()), true), 1);
            }
            $tracks = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($tracks != false and count($tracks)>0) {
                foreach ($tracks as $track) {
                    $temp = TrackBroker::getTrackByID($track['intTrackID']);
                    if ($temp != false) {
                        $return[$temp->get_intTrackID()] = $temp;
                    }
                }
            }
            return $return;
        } catch(Exception $e) {
            return false;
        }
    }

    /**
     * A function to retrieve all the tracks associated to a show.
     *
     * @param integer $intShowID The ShowID we're looking for
     *
     * @return array|false An array of the Tracks, or false if the operation fails.
     */
    function getTracksByShowIDOrderedByPartID($intShowID = 0)
    {
        $return = array();
        $db = Database::getConnection();
        try {
            $sql = "SELECT intTrackID, intPartID FROM showtracks WHERE intShowID = ? ORDER BY intPartID";
            $query = $db->prepare($sql);
            $query->execute(array($intShowID));
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>$intShowID, 'error'=>$query->errorInfo()), true), 1);
            }
            $tracks = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($tracks != false and count($tracks)>0) {
                $part = 0;
                foreach ($tracks as $track) {
                    $temp = TrackBroker::getTrackByID($track['intTrackID']);
                    if ($temp != false) {
                        $return[$part++] = $temp;
                    }
                }
            }
            return $return;
        } catch(Exception $e) {
            return false;
        }
    }
    
    /**
     * A function to retrieve all unplayed tracks
     *
     * @return array|false An array of the Tracks, or false if the operation fails.
     */
    function getUnplayedTracks()
    {
        $return = array();
        $db = Database::getConnection();
        try {
            $sql = "SELECT tracks.* FROM tracks LEFT JOIN (SELECT showtracks.intTrackID FROM showtracks, shows WHERE shows.enumShowType = 'daily' AND shows.intShowID = showtracks.intShowID) as showtrack ON showtrack.intTrackID = tracks.intTrackID WHERE tracks.isApproved = 1 AND showtrack.intTrackID IS NULL";
            $query = $db->prepare($sql);
            $query->execute();
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>$intShowID, 'error'=>$query->errorInfo()), true), 1);
            }
            $tracks = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($tracks != false and count($tracks)>0) {
                $part = 0;
                foreach ($tracks as $track) {
                    $temp = TrackBroker::getTrackByID($track['intTrackID']);
                    if ($temp != false) {
                        $return[$part++] = $temp;
                    }
                }
            }
            return $return;
        } catch(Exception $e) {
            return false;
        }
    }
}

