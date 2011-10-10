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

class ChartBroker
{
    /**
     * Return the highest point in the chart this track has reached
     *
     * @param integer $intTrackID The track to find
     *
     * @return integer The peak
     */
    function getTrackPeak($intTrackID = 0)
    {
        $db = Database::getConnection();
        try {
            $sql = "SELECT min(intPositionID) as intPeak FROM chart WHERE intTrackID = ?";
            $query = $db->prepare($sql);
            $query->execute(array($intTrackID));
            return $query->fetchColumn();
        } catch(Exception $e) {
            error_log("SQL error: " . $e);
            return false;
        }
    }

    /**
     * Get the last thirty days of chart data for the track number
     *
     * @param integer $intTrackID The track number
     *
     * @return Array|false Data from the cart or false because there is no data
     */
    function getLastSixtyDaysOfChartDataForOneTrack($intTrackID = 0)
    {
        $db = Database::getConnection();
        try {
            $sql = "SELECT intPositionID, datChart FROM chart WHERE intTrackID = ? ORDER BY datChart DESC LIMIT 0, 60";
            $query = $db->prepare($sql);
            $query->execute(array($intTrackID));
            $return = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($return != false and count($return) < 60) {
                $date = $return[count($return) - 1]['datChart'];
                for ($count = count($return); $count < 60; $count++) {
                    $date = date('-1 day', strtotime($date));
                    $return[$count] = array('intPositionID' => "null", 'datChart'=>$date);
                }
            }
            return $return;
        } catch(Exception $e) {
            error_log("SQL error: " . $e);
            return false;
        }
    }

    /**
     * A function to retrieve all the tracks associated to a day's chart where changes have occurred (position, votes).
     * TODO: Write this function - currently maps to the function not looking for changes
     *
     * @param date    $strChartDate The date of the chart in Y-m-d format
     * @param date    $strPriorDate The date of the chart to be compared with in Y-m-d format
     *
     * @return array|false An array of the Tracks, or false if the operation fails.
     */
    function getChartByDateWithChanges(
        $strChartDate = '',
        $strPriorDate = ''
    ) {
        $return = array();
        $db = Database::getConnection();
        try {
            if (! is_integer($strChartDate) OR UI::getShortDate($strChartDate) == false) {
                $strChartDate = '';
            }
            if ($strChartDate == '') {
                $sql = "SELECT max(datChart) as max_datChart FROM chart LIMIT 0, 1";
                $query = $db->prepare($sql);
                $query->execute();
                $strChartDate = $query->fetchColumn();
            }
            if ($strPriorDate == '') {
                $sql = "SELECT datChart FROM chart WHERE datChart < '$strChartDate' GROUP BY datChart ORDER BY datChart DESC LIMIT 0, 1";
                $query = $db->prepare($sql);
                $query->execute();
                $strPriorDate = $query->fetchColumn();
            }
            $return['intChartDate'] = UI::getShortDate($strChartDate);
            $return['strChartDate'] = $strChartDate;

            $sql = "SELECT intPositionID, intTrackID FROM chart WHERE datChart = ? ORDER BY intPositionID ASC";
            $query = $db->prepare($sql);
            $query->execute(array(UI::getShortDate($strChartDate)));
            $tracks = $query->fetchAll(PDO::FETCH_ASSOC);

            $query->execute(array(UI::getShortDate($strPriorDate)));
            $tracks2 = $query->fetchAll(PDO::FETCH_ASSOC);

            $sql = "SELECT intTrackID FROM votes WHERE datTimestamp < ? AND datTimestamp >= ? GROUP BY intTrackID";
            $query = $db->prepare($sql);
            $values = array(UI::getShortDate($strChartDate), UI::getShortDate($strPriorDate));
            $query->execute($values);
            $votes = $query->fetchAll(PDO::FETCH_ASSOC);

            $sql = "SELECT st.intTrackID FROM shows AS s, showtracks AS st WHERE s.datDateAdded < ? AND s.datDateAdded >= ? AND s.intShowID = st.intShowID GROUP BY st.intTrackID";
            $query = $db->prepare($sql);
            $query->execute($values);
            $shows = $query->fetchAll(PDO::FETCH_ASSOC);

            foreach ($tracks as $todaytrack) {
                $todayT[$todaytrack['intTrackID']] = $todaytrack['intPositionID'];
            }

            foreach ($tracks2 as $yesterdaytrack) {
                if ($todayT[$yesterdaytrack['intTrackID']] != $yesterdaytrack['intPositionID']) {
                    $diff[$yesterdaytrack['intTrackID']]['move'] = true;
                }
            }

            foreach ($votes as $vote) {
                $diff[$vote['intTrackID']]['vote'] = true;
            }

            foreach ($shows as $show) {
                $diff[$show['intTrackID']]['show'] = true;
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
            $return['position'] = $position;
            return $return;
        } catch(Exception $e) {
            error_log("SQL error: " . $e);
            return false;
        }
    }
    /**
     * A function to retrieve all the tracks associated to a day's chart.
     *
     * @param date    $strChartDate The date of the chart in Y-m-d format
     * @param integer $intPage      The start "page" number
     * @param integer $intSize      The size of each page
     *
     * @return array|false An array of the Tracks, or false if the operation fails.
     */
    function getChartByDate(
        $strChartDate = '',
        $intPage = null,
        $intSize = null
    ) {
        $arrUri = UI::getUri();
        if ($intPage == null and isset($arrUri['parameters']['page']) and $arrUri['parameters']['page'] > 0) {
            $intPage = $arrUri['parameters']['page'];
        } elseif ($intPage == null) {
            $intPage = 0;
        }
        if ($intSize == null and isset($arrUri['parameters']['size']) and $arrUri['parameters']['size'] > 0) {
            $intSize = $arrUri['parameters']['size'];
        } elseif ($intSize == null) {
            $intSize = 25;
        }

        $return = array();
        $db = Database::getConnection();
        try {
            if ($strChartDate == '') {
                $sql = "SELECT max(datChart) as max_datChart FROM chart LIMIT 0, 1";
                $query = $db->prepare($sql);
                $query->execute();
                $strChartDate = $query->fetchColumn();
            }
            if (! is_integer($strChartDate)) {
                $return['intChartDate'] = UI::getShortDate($strChartDate);
                $return['strChartDate'] = $strChartDate;
            } else {
                $return['intChartDate'] = $strChartDate;
                $return['strChartDate'] = UI::getLongDate($strChartDate);
            }
            $sql = "SELECT intPositionID, intTrackID FROM chart WHERE datChart = ?";
            $pagestart = ($intPage * $intSize);
            $query = $db->prepare($sql . " ORDER BY intPositionID ASC LIMIT " . $pagestart . ", $intSize");
            $query->execute(array(UI::getShortDate($strChartDate)));
            $tracks = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($tracks != false and count($tracks)>0) {
                foreach ($tracks as $track) {
                    $temp = TrackBroker::getTrackByID($track['intTrackID']);
                    if ($temp != false) {
                        $return['position'][$track['intPositionID']] = $temp->getSelf();
                        $return['position'][$track['intPositionID']]['intChartPosition'] = $track['intPositionID'];
                    }
                }
            }
            return $return;
        } catch(Exception $e) {
            error_log("SQL error: " . $e);
            return false;
        }
    }
}