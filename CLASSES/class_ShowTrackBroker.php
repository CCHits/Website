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
        Debug::Log(get_class() . "::getShowTrackByShowID($intShowID)", "DEBUG");
        $db = CF::getFactory()->getConnection();
        try {
            $sql = "SELECT * FROM showtracks WHERE intShowID = ? ORDER BY intPartID ASC";
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
            echo "SQL Died: " . $e->getMessage();;
            die();
        }
    }
}
