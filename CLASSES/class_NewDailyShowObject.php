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
        $sql = "SELECT tracks.intArtistID, tracks.timeLength FROM tracks, shows, showtracks WHERE showtracks.intShowID=shows.intShowID AND showtracks.intTrackID=tracks.intTrackID AND shows.enumShowType = ? ORDER BY shows.intShowUrl DESC LIMIT 0,14";
        $query = $db->prepare($sql);
        $query->execute(array('daily'));
        // This section of code, thanks to code example here:
        // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
        if ($query->errorCode() != 0) {
            throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>'daily', 'error'=>$query->errorInfo()), true), 1);
        }
        $history = $query->fetch(PDO::FETCH_ASSOC);
        $strQry = '';
        $arrArtists = array();
        $arrArtistIDs = array();
        $boolLongTrack = false;
        foreach ($history as $history_item) {
            if (! isset($arrArtistIDs[$history_item['intArtistID']])) {
                $strQry .= ' AND intArtistID != ?';
                $arrArtists[] = $history_item['intArtistID'];
                $arrArtistIDs[$history_item['intArtistID']] = true;
            }
            $minute = intval(date('i', strtotime($history_item['timeLength']))) + (intval(date('G', strtotime($history_item['timeLength']))) * 60);
            if ($minute > 8) {
                $boolLongTrack = true;
            }
        }

        $sql = "SELECT tracks.intTrackID FROM tracks LEFT JOIN (SELECT showtracks.intTrackID FROM showtracks, shows WHERE shows.enumShowType = 'daily' AND shows.intShowID = showtracks.intShowID) as showtrack ON showtrack.intTrackID = tracks.intTrackID WHERE showtrack.intTrackID IS NULL ";
        if ($boolLongTrack) {
            $sql .= " AND timeLength < '00:08:00'";
        }
        $sql .= $strQry . " ORDER BY RAND() LIMIT 0,1 ";
        $query = $db->prepare($sql);
        $query->execute();
        // This section of code, thanks to code example here:
        // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
        if ($query->errorCode() != 0) {
            throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'error'=>$query->errorInfo()), true), 1);
        }
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