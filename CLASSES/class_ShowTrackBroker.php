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
 * This class deals with all things ShowTrack related.
 *
 * @category Default
 * @package  Brokers
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */

class ShowTrackBroker
{
    protected static $handler = null;

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
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>$intShowID, 'error'=>$query->errorInfo()), true), 1);
            }
            $item = $query->fetchObject('ShowTrackObject');
            if ($item == false) {
                return false;
            } else {
                $return[$item->get_intPartID()] = $item;
                while ($item = $query->fetchObject('ShowTrackObject')) {
                    $return[$item->get_intPartID()] = $item;
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
    public static function getShowTracksByShowTrackID($intShowID = 0, $intTrackID = 0)
    {
        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM showtracks WHERE intShowID = ? AND intTrackID = ? ORDER BY intPartID ASC";
            $query = $db->prepare($sql);
            $query->execute(array($intShowID, $intTrackID));
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>array($intShowID, $intTrackID), 'error'=>$query->errorInfo()), true), 1);
            }
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
    public static function getShowTracksByTrackID($intTrackID = 0)
    {
        $stb = self::getHandler();
        if (isset($stb->arrShowTracksByTrack[$intTrackID])) {
            return $stb->arrShowTracksByTrack[$intTrackID];
        }
        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM showtracks WHERE intTrackID = ? ORDER BY intShowID ASC";
            $query = $db->prepare($sql);
            $query->execute(array($intTrackID));
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>$intTrackID, 'error'=>$query->errorInfo()), true), 1);
            }
            $item = $query->fetchObject('ShowTrackObject');
            if ($item == false) {
                return false;
            } else {
                $return[$item->get_intShowID()] = $item;
                while ($item = $query->fetchObject('ShowTrackObject')) {
                    $return[$item->get_intShowID()] = $item;
                }
                $stb->arrShowTracksByTrack[$intTrackID] = $return;
                return $stb->arrShowTracksByTrack[$intTrackID];
            }
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
    }

    /**
     * This function sets the value of the track position which needs to be
     * moved to 9999, then moves the previous track to the position of the
     * track which needs to be moved, and then will move the track just moved
     * to position 9999 back to the position of the track which was moved.
     *
     * This is because of the indexes on this table, and that we can't have two
     * tracks in the same position in a show.
     *
     * @param object  $objShow    The object containing the show running order
     * and intShowID
     * @param integer $intTrackID The track to move one space up in the running
     * order
     *
     * @return void
     */
    public function MoveShowTrackUp($objShow = null, $intTrackID = null)
    {
        $arrShowTracks = ShowTrackBroker::getShowTracksByShowID($objShow->get_intShowID());
        $this_objShowTrack = false;
        $this_intPartID = false;
        foreach ($arrShowTracks as $intPartID => $objShowTrack) {
            $last_objShowTrack = $this_objShowTrack;
            $last_intPartID = $this_intPartID;
            $this_objShowTrack = $objShowTrack;
            $this_intPartID = $intPartID;
            if ($this_objShowTrack->get_intTrackID() == $intTrackID && $last_objShowTrack != false) {
                $last_objShowTrack->set_intPartID(9999);
                $last_objShowTrack->write();
                $this_objShowTrack->set_intPartID($last_intPartID);
                $this_objShowTrack->write();
                $last_objShowTrack->set_intPartID($this_intPartID);
                $last_objShowTrack->write();
            }
        }
    }

    /**
     * This function manipulates the running order of a show, by re-indexing 
     * all the show tracks, two tracks apart, until the track to be moved 
     * forward arrives, when it is shunted into a space 3 tracks forward, and 
     * the pointer is then reset back by two spaces.
     *
     * The tracks are then sorted and committed incremented by 900 track
     * spaces. They are then re-run, committing them back in order 900 track
     * spaces back. This is because the index on the ShowTracks table won't
     * permit duplication of the "intPartID" value.
     *
     * @param object  $objShow    The object containing the show running order and intShowID
     * @param integer $intTrackID The track to move down space up in the running order
     *
     * @return void
     */
    public function MoveShowTrackDown($objShow = null, $intTrackID = null)
    {
        $pos = 1;
        $reset = false;
        foreach ($objShow->get_arrTracks() as $intPartID => $objTrack) {
            if ($objTrack->get_intTrackID() == $intTrackID) {
                $pos = $pos + 3;
                $temp[$pos] = $objTrack;
                $pos = $pos - 3;
                $reset = true;
            } else {
                $pos = $pos + 2;
                $temp[$pos] = $objTrack;
            }
        }
        if ($reset == true) {
            ksort($temp);
            $pos = 0;
            foreach ($temp as $objTrack) {
                $arrTracks[$objTrack->get_intTrackID()] = $pos++;
                $arrShowTrackObjects[$objTrack->get_intTrackID()] = ShowTrackBroker::getShowTracksByShowTrackID($objShow->get_intShowID(), $objTrack->get_intTrackID());
            }
            foreach ($arrShowTrackObjects as $intTrackID=>$objShowTrack) {
                $objShowTrack->set_intPartID(900+$arrTracks[$intTrackID]);
                $objShowTrack->write();
            }
            foreach ($arrShowTrackObjects as $intTrackID=>$objShowTrack) {
                $objShowTrack->set_intPartID($arrTracks[$intTrackID]);
                $objShowTrack->write();
            }
        }
    }

    /**
     * This function again is very similar to it's previous two counterparts.
     * It indexes the running order, looking for a track number to remove. Once
     * it finds it, it ignores that particular track entry, but sets the flag
     * to delete it from the running order.
     *
     * Once the indexing is complete, it checks whether the flag exists to
     * delete the ShowTrack, and if it does, deletes it and then re-numbers the
     * track parts, up by 900, and then back down by 900 due to the table
     * indexes.
     *
     * @param object  $objShow    The object containing the show running order and intShowID
     * @param integer $intTrackID The track to move down space up in the running order
     *
     * @return void
     */
    public function RemoveShowTrack($objShow = null, $intTrackID = null)
    {
        $pos = 0;
        $remove = false;
        foreach ($objShow->get_arrTracks() as $intPartID => $objTrack) {
            if ($objTrack->get_intTrackID() != $intTrackID) {
                $temp[$pos++] = $objTrack;
            } else {
                $remove = true;
            }
        }
        if ($remove == true) {
            $db = Database::getConnection();
            try {
                $sql = "DELETE FROM showtracks WHERE intTrackID = ? AND intShowID = ?";
                $query = $db->prepare($sql);
                $values = array($intTrackID, $objShow->get_intShowID());
                $query->execute($values);
                // This section of code, thanks to code example here:
                // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
                if ($query->errorCode() != 0) {
                    throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>$values, 'error'=>$query->errorInfo()), true), 1);
                }
            } catch(Exception $e) {
                error_log("SQL Died: " . $e->getMessage());
                return false;
            }

            $pos = 0;
            foreach ($temp as $objTrack) {
                $arrTracks[$objTrack->get_intTrackID()] = $pos++;
                $arrShowTrackObjects[$objTrack->get_intTrackID()] = ShowTrackBroker::getShowTracksByShowTrackID($objShow->get_intShowID(), $objTrack->get_intTrackID());
            }
            foreach ($arrShowTrackObjects as $intTrackID=>$objShowTrack) {
                $objShowTrack->set_intPartID(900+$arrTracks[$intTrackID]);
                $objShowTrack->write();
            }
            foreach ($arrShowTrackObjects as $intTrackID=>$objShowTrack) {
                $objShowTrack->set_intPartID($arrTracks[$intTrackID]);
                $objShowTrack->write();
            }
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
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>array($intNewTrackID, $intOldTrackID), 'error'=>$query->errorInfo()), true), 1);
            }
            return true;
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
    }
}
