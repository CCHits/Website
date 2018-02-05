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
 * This class knows all the ways to get full listings of changes
 *
 * @category Default
 * @package  Brokers
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */

class ChangeBroker
{
    /**
     * A function to retrieve all the tracks associated to a day's chart where changes have occurred (position, votes).
     *
     * @param integer $intTrackID    The optional trackID to search for
     * @param date    $strChangeDate The date of the chart in Y-m-d format
     * @param date    $strPriorDate  The date of the chart to be compared with in Y-m-d format
     *
     * @return array|false An array of the Tracks, or false if the operation fails.
     */
    function getChangeByDate(
        $intTrackID = 0,
        $strChangeDate = '',
        $strPriorDate = ''
    ) {
        $return = array();
        $db = Database::getConnection();
        try {
            if (! is_integer($strChangeDate) OR UI::getShortDate($strChangeDate) == false) {
                $strChangeDate = '';
            }
            if ($strChangeDate == '') {
                $sql = "SELECT max(datChart) as max_datChart FROM chart LIMIT 0, 1";
                $query = $db->prepare($sql);
                $query->execute();
                // This section of code, thanks to code example here:
                // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
                if ($query->errorCode() != 0) {
                    throw new Exception(
                        "SQL Error: " . print_r(array('sql'=>$sql, 'error'=>$query->errorInfo()), true), 1
                    );
                }
                $strChangeDate = $query->fetchColumn();
            }
            if ($strPriorDate == '') {
                $sql = "SELECT datChart FROM chart WHERE datChart < '$strChangeDate' GROUP BY datChart ORDER BY " .
                "datChart DESC LIMIT 0, 1";
                $query = $db->prepare($sql);
                $query->execute();
                // This section of code, thanks to code example here:
                // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
                if ($query->errorCode() != 0) {
                    throw new Exception(
                        "SQL Error: " . print_r(array('sql'=>$sql, 'error'=>$query->errorInfo()), true), 1
                    );
                }
                $strPriorDate = $query->fetchColumn();
            }
            $return['intChangeDate'] = UI::getShortDate($strChangeDate);
            $return['strChangeDate'] = $strChangeDate;

            if ($intTrackID != 0) {
                $positionsql = "SELECT intPositionID, intTrackID FROM chart WHERE datChart = ? AND intTrackID = ? " .
                "ORDER BY intPositionID ASC";
                $votessql = "SELECT count(intTrackID) AS count_intTrackID, intTrackID FROM votes WHERE datTimestamp " .
                "< ? AND datTimestamp >= ? AND intTrackID = ? GROUP BY intTrackID";
                $showsql = "SELECT count(st.intTrackID) AS count_intTrackID, st.intTrackID FROM shows AS s, " .
                "showtracks AS st WHERE s.datDateAdded < ? AND s.datDateAdded >= ? AND s.intShowID = st.intShowID " .
                "AND st.intTrackID = ? GROUP BY st.intTrackID";
                $values1 = array(UI::getShortDate($strChangeDate), $intTrackID);
                $values2 = array(UI::getShortDate($strPriorDate), $intTrackID);
                $values3 = array(UI::getShortDate($strChangeDate), UI::getShortDate($strPriorDate), $intTrackID);
            } else {
                $positionsql = "SELECT intPositionID, intTrackID FROM chart WHERE datChart = ? ORDER BY " .
                "intPositionID ASC";
                $votessql = "SELECT count(intTrackID) AS count_intTrackID, intTrackID FROM votes WHERE datTimestamp " .
                "< ? AND datTimestamp >= ? GROUP BY intTrackID";
                $showsql = "SELECT count(st.intTrackID) AS count_intTrackID, st.intTrackID FROM shows AS s, " .
                "showtracks AS st WHERE s.datDateAdded < ? AND s.datDateAdded >= ? AND s.intShowID = st.intShowID " .
                "GROUP BY st.intTrackID";
                $values1 = array(UI::getShortDate($strChangeDate));
                $values2 = array(UI::getShortDate($strPriorDate));
                $values3 = array(UI::getShortDate($strChangeDate), UI::getShortDate($strPriorDate));
            }
            $query = $db->prepare($positionsql);
            $query->execute($values1);
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception(
                    "SQL Error: " . print_r(
                        array('sql'=>$positionsql, 'values'=>$values1, 'error'=>$query->errorInfo()), true
                    ), 1
                );
            }
            $tracks = $query->fetchAll(PDO::FETCH_ASSOC);
            $query->execute($values2);
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception(
                    "SQL Error: " . print_r(
                        array('sql'=>$positionsql, 'values'=>$values2, 'error'=>$query->errorInfo()), true
                    ), 1
                );
            }
            $tracks2 = $query->fetchAll(PDO::FETCH_ASSOC);
            $query = $db->prepare($votessql);
            $query->execute($values3);
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception(
                    "SQL Error: " . print_r(
                        array('sql'=>$votessql, 'values'=>$values3, 'error'=>$query->errorInfo()), true
                    ), 1
                );
            }
            $votes = $query->fetchAll(PDO::FETCH_ASSOC);
            $query = $db->prepare($showsql);
            $query->execute($values3);
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception(
                    "SQL Error: " . print_r(
                        array('sql'=>$showsql, 'values'=>$values3, 'error'=>$query->errorInfo()), true
                    ), 1
                );
            }
            $shows = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($tracks as $todaytrack) {
                $todayT[$todaytrack['intTrackID']] = $todaytrack['intPositionID'];
            }

            foreach ($tracks2 as $yesterdaytrack) {
                if ($todayT[$yesterdaytrack['intTrackID']] != $yesterdaytrack['intPositionID']) {
                    $diff[$yesterdaytrack['intTrackID']]['move'] = array(
                        'from' => $yesterdaytrack['intPositionID'], 
                        'to' => $todayT[$yesterdaytrack['intTrackID']]
                    );
                }
            }

            foreach ($votes as $vote) {
                $diff[$vote['intTrackID']]['vote'] = $vote['count_intTrackID'];
            }

            foreach ($shows as $show) {
                $diff[$show['intTrackID']]['show'] = $show['count_intTrackID'];
            }

            $position = array();
            if (isset($diff) and is_array($diff) and count($diff) > 0) {
                foreach ($diff as $trackid=>$reasons) {
                    $temp = TrackBroker::getTrackByID($trackid);
                    if ($temp != false) {
                        $position[$todayT[$trackid]] = $temp->getSelf();
                        $position[$todayT[$trackid]]['reasons'] = $reasons;
                        $position[$todayT[$trackid]]['intChartPosition'] = $todayT[$trackid];
                    }
                }
            }
            ksort($position);
            $return['tracks'] = $position;
            return $return;
        } catch(Exception $e) {
            error_log("SQL error: " . $e);
            return false;
        }
    }
}
