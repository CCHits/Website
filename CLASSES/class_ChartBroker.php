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
     * Get the last thirty days of chart data for the track number
     *
     * @param integer $intTrackID The track number
     *
     * @return Array|false Data from the cart or false because there is no data
     */
    function getLastThirtyDaysOfChartDataForOneTrack($intTrackID = 0)
    {
        $db = Database::getConnection();
        try {
            $sql = "SELECT intPositionID, datChart FROM chart WHERE intTrackID = ? ORDER BY datChart DESC LIMIT 0, 30";
            $query = $db->prepare($sql);
            $query->execute(array($intTrackID));
            $return = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($return != false and count($return) < 30) {
                $date = $return[count($return) - 1]['datChart'];
                for ($count = count($return); $count < 30; $count++) {
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
            $page = $arrUri['parameters']['page'];
        } elseif ($intPage == null) {
            $page = 0;
        }
        if ($intSize == null and isset($arrUri['parameters']['size']) and $arrUri['parameters']['size'] > 0) {
            $size = $arrUri['parameters']['size'];
        } elseif ($intSize == null) {
            $size = 25;
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
            $sql = "SELECT intPositionID, intTrackID FROM chart WHERE datChart = ?";
            $pagestart = ($intPage * $intSize);
            $query = $db->prepare($sql . " ORDER BY intPositionID ASC LIMIT " . $pagestart . ", $intSize");
            $query->execute(array($strChartDate));
            $tracks = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($tracks != false and count($tracks)>0) {
                foreach ($tracks as $track) {
                    $temp = TrackBroker::getTrackByID($track['intTrackID']);
                    if ($temp != false) {
                        $return[$track['intPositionID']] = $temp;
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