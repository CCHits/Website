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
     * A function to retrieve all the tracks associated to a show.
     *
     * @param integer $intShowID The ShowID we're looking for
     *
     * @return array|false An array of the Tracks, or false if the operation fails.
     */
    function getTracksByShowID($intShowID = 0)
    {
        Debug::Log(get_class() . "::getTracksByShowID($intShowID)", "DEBUG");
        $return = array();
        $db = CF::getFactory()->getConnection();
        try {
            $sql = "SELECT intTrackID FROM showtracks WHERE intShowID = ?";
            $query = $db->prepare($sql);
            $query->execute(array($intShowID));
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
}

