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
 * This class knows all the ways to get collections of tracks
 *
 * @category Default
 * @package  Brokers
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
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
    public static function getTrackPeak($intTrackID = 0)
    {
        $db = Database::getConnection();
        try {
            $sql = "SELECT min(intPositionID) as intPeak FROM chart WHERE intTrackID = ?";
            $query = $db->prepare($sql);
            $query->execute(array($intTrackID));
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception(
                    "SQL Error: " . print_r(
                        array('sql'=>$sql, 'values'=>$intTrackID, 'error'=>$query->errorInfo()), true
                    ), 1
                );
            }
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
    public static function getLastSixtyDaysOfChartDataForOneTrack($intTrackID = 0)
    {
        $db = Database::getConnection();
        try {
            $sql = "SELECT intPositionID, datChart FROM chart WHERE intTrackID = ? ORDER BY datChart DESC LIMIT 0, 60";
            $query = $db->prepare($sql);
            $query->execute(array($intTrackID));
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception(
                    "SQL Error: " . print_r(
                        array('sql'=>$sql, 'values'=>$intTrackID, 'error'=>$query->errorInfo()), true
                    ), 1
                );
            }
            $return = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($return != false and count($return) < 60) {
                $date = $return[count($return) - 1]['datChart'];
                for ($count = count($return); $count < 60; $count++) {
                    $date = date('-1 day', strtotime($date));
                    $return[$count] = array('intPositionID' => null, 'datChart'=>$date);
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
    public static function getChartByDate(
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
                // This section of code, thanks to code example here:
                // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
                if ($query->errorCode() != 0) {
                    throw new Exception(
                        "SQL Error: " . print_r(array('sql'=>$sql, 'error'=>$query->errorInfo()), true), 1
                    );
                }
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
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception(
                    "SQL Error: " . print_r(
                        array(
                            'sql'=>$sql, 
                            'values'=>UI::getShortDate($strChartDate), 
                            'error'=>$query->errorInfo()
                        ), true
                    ), 1
                );
            }
            $tracks = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($tracks != false and count($tracks)>0) {
                foreach ($tracks as $track) {
                    $temp = TrackBroker::getTrackByID($track['intTrackID']);
                    if ($temp != false) {
                        $return['position'][$track['intPositionID']] = $temp->getSelf();
                        $return['position'][$track['intPositionID']]['intChartPosition'] = $track['intPositionID'];
                        $return['position'][$track['intPositionID']]['arrVotes'] 
                            = VoteBroker::getVotesForTrackByShow($temp->get_intTrackID());
                    }
                }
            }
            return $return;
        } catch(Exception $e) {
            error_log("SQL error: " . $e);
            return false;
        }
    }
    
    /**
     * Returns a light version of the chart, by date.
     * 
     * @param string $strChartDate date
     * @param int    $intPage      page
     * @param int    $intSize      size
     * 
     * @return array|false
     */
    public static function getLightChartByDate(
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
        $return[ 'intPage' ] = $intPage;
        $return[ 'intSize' ] = $intSize;
        $db = Database::getConnection();
        try {
            if ($strChartDate == '') {
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
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception(
                    "SQL Error: " . print_r(
                        array(
                            'sql'=>$sql,
                            'values'=>UI::getShortDate($strChartDate), 
                            'error'=>$query->errorInfo()
                        ), true
                    ), 1
                );
            }
            $tracks = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($tracks != false and count($tracks)>0) {
                foreach ($tracks as $track) {
                    $temp = TrackBroker::getTrackByID($track['intTrackID']);
                    if ($temp != false) {
                        $t = $temp->getSelf();
                        $return['position'][$track['intPositionID']] = [];
                        $return['position'][$track['intPositionID']]['strPositionLastWeek'] = $t['strPositionLastWeek'];
                        $return['position'][$track['intPositionID']]['averageLastWeek'] = $t['averageLastWeek'];
                        $return['position'][$track['intPositionID']]['intChartPeak'] = $t['intChartPeak'];
                        $return['position'][$track['intPositionID']]['strTrackName'] = $t['strTrackName'];
                        $return['position'][$track['intPositionID']]['strArtistName'] = $t['strArtistName'];
                        $return['position'][$track['intPositionID']]['intChartPosition'] = $track['intPositionID'];
                        $return['position'][$track['intPositionID']]['arrVotes'] = [];
                    }
                }
            }
            return $return;
        } catch (Exception $e) {
            error_log("SQL error: " . $e);
            return false;
        }
    }
}
