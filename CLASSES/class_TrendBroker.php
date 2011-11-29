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

class TrendBroker
{
    /**
     * A function to retrieve all the tracks associated to the last 7 days of trending data.
     *
     * @param date    $strTrendDate The date of the chart in Y-m-d format
     * @param integer $intPage      The start "page" number
     * @param integer $intSize      The size of each page
     *
     * @return array|false An array of the Tracks, or false if the operation fails.
     */
    function getTrendByDate(
        $strTrendDate = '',
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
            if ($strTrendDate == '') {
                $sql = "SELECT max(datTrendDay) as max_datTrendDay FROM trends LIMIT 0, 1";
                $query = $db->prepare($sql);
                $query->execute();
                // This section of code, thanks to code example here:
                // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
                if ($query->errorCode() != 0) {
                    throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'error'=>$query->errorInfo()), true), 1);
                }
                $strTrendDate = $query->fetchColumn();
            } else {
                $strTrendDate = getLongDate($strTrendDate);
            }

            $start_date = date('Y-m-d', strtotime('-7 days', strtotime($strTrendDate)));
            $end_date = date('Y-m-d', strtotime($strTrendDate));

            $sql = "SELECT intTrackID, datTrendDay, intVotes FROM trends WHERE datTrendDay => ? AND datTrendDay <= ?";
            $pagestart = ($intPage * $intSize);
            $query = $db->prepare($sql . " LIMIT " . $pagestart . ", $intSize");
            $query->execute(array($start_date, $end_date));
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>array($start_date, $end_date), 'error'=>$query->errorInfo()), true), 1);
            }
            $tracks = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($tracks != false and count($tracks)>0) {
                $data = array();
                $return = array();
                foreach ($tracks as $track) {
                    $temp = TrackBroker::getTrackByID($track['intTrackID']);
                    if ($temp != false) {
                        $return[$track['intTrackID']] = $temp;
                        $data[$track['datTrendDay']][$track['intTrackID']] = $track['intVotes'];
                    }
                }
                if (is_array($data) and count($data) > 0) {
                    ksort($data);
                    $mod = 0;
                    for ($date = getShortDate($start_date);
                        $date <= getShortDate($end_date);
                        $date = date("Ymd", strtotime("+1 day", strtotime(getLongDate($date))))) {
                        $mod++;
                        $realdate = getLongDate($date);
                        if (isset($data[$realdate]) and is_array($data[$realdate]) and count($data[$realdate]) > 0) {
                            $track_data = $data[$realdate];
                            ksort($track_data);
                            foreach ($track_data as $track=>$votes) {
                                if (isset($return[$track])) {
                                    $return[$track]->set_intTrend($return[$track]->get_intTrend() + ($votes * $mod));
                                } else {
                                    $return[$track]->set_intTrend($votes * $mod);
                                }
                            }
                        }
                    }
                }
                arsort($return);
                return $return;
            }
            return $return;
        } catch(Exception $e) {
            error_log("SQL error: " . $e);
            return false;
        }
    }

    /**
     * A function to retrieve all the tracks associated to the last 7 days of trending data for one track.
     *
     * @param integer $intTrackID   The track to search for
     * @param date    $strTrendDate The date to end the trending information on
     * @param integer $intPage      The start "page" number
     * @param integer $intSize      The size of each page
     *
     * @return array|false An array of the Tracks, or false if the operation fails.
     */
    function getTrendByTrackID(
        $intTrackID = 0,
        $strTrendDate = '',
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
            if ($strTrendDate == '') {
                $sql = "SELECT max(datTrendDay) as max_datTrendDay FROM trends LIMIT 0, 1";
                $query = $db->prepare($sql);
                $query->execute();
                // This section of code, thanks to code example here:
                // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
                if ($query->errorCode() != 0) {
                    throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'error'=>$query->errorInfo()), true), 1);
                }
                $strTrendDate = $query->fetchColumn();
            } else {
                $strTrendDate = getLongDate($strTrendDate);
            }

            $start_date = date('Y-m-d', strtotime('-7 days', strtotime($strTrendDate)));
            $end_date = date('Y-m-d', strtotime($strTrendDate));

            $sql = "SELECT intTrackID, datTrendDay, intVotes FROM trends WHERE intTrackID = ? and datTrendDay => ? AND datTrendDay <= ?";
            $pagestart = ($intPage * $intSize);
            $query = $db->prepare($sql . " LIMIT " . $pagestart . ", $intSize");
            $query->execute(array($intTrackID, $start_date, $end_date));
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>array($intTrackID, $start_date, $end_date), 'error'=>$query->errorInfo()), true), 1);
            }
            $tracks = $query->fetchAll(PDO::FETCH_ASSOC);
            if ($tracks != false and count($tracks)>0) {
                $data = array();
                $return = array();
                foreach ($tracks as $track) {
                    $temp = TrackBroker::getTrackByID($track['intTrackID']);
                    if ($temp != false) {
                        $return[$track['intTrackID']] = $temp;
                        $data[$track['datTrendDay']][$track['intTrackID']] = $track['intVotes'];
                    }
                }
                if (is_array($data) and count($data) > 0) {
                    ksort($data);
                    $mod = 0;
                    for ($date = getShortDate($start_date);
                        $date <= getShortDate($end_date);
                        $date = date("Ymd", strtotime("+1 day", strtotime(getLongDate($date))))) {
                        $mod++;
                        $realdate = getLongDate($date);
                        if (isset($data[$realdate]) and is_array($data[$realdate]) and count($data[$realdate]) > 0) {
                            $track_data = $data[$realdate];
                            ksort($track_data);
                            foreach ($track_data as $track=>$votes) {
                                if (isset($return[$track])) {
                                    $return[$track]->set_intTrend($return[$track]->get_intTrend() + ($votes * $mod));
                                } else {
                                    $return[$track]->set_intTrend($votes * $mod);
                                }
                            }
                        }
                    }
                }
                arsort($return);
                return $return;
            }
            return $return;
        } catch(Exception $e) {
            error_log("SQL error: " . $e);
            return false;
        }
    }
}