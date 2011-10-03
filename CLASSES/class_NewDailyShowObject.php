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
 * This class extends internal show object class to create daily shows.
 *
 * @category Default
 * @package  Objects
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class NewDailyShowObject extends NewInternalShowObject
{
    /**
     * Establish the creation of the new item by setting the values and then calling the create function.
     *
     * @param integer $intShowUrl The date of the show in YYYYMMDD format
     *
     * @return object This show object
     */
    public function __construct($intShowUrl = 0)
    {
        $db = Database::getConnection();
        $sql = "SELECT tracks.intTrackID FROM tracks LEFT JOIN (SELECT showtracks.intTrackID FROM showtracks, shows WHERE shows.enumShowType = 'daily' AND shows.intShowID = showtracks.intShowID) as showtrack ON showtrack.intTrackID = tracks.intTrackID WHERE showtrack.intTrackID IS NULL ORDER BY RAND() LIMIT 0,1 ";
        $query = $db->prepare($sql);
        $query->execute(array());
        $track = $query->fetch(PDO::FETCH_ASSOC);
        if ($track != false) {
            $status = parent::__construct($intShowUrl, 'daily');
            if ($status) {
                $this->arrTracks[] = new NewShowTrackObject($track['intTrackID'], $this->intShowID);
            }
            return $this;
        } else {
            return false;
        }
    }
}