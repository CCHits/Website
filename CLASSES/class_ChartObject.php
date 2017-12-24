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
 * This class creates charts.
 *
 * @category Default
 * @package  Brokers
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */

class ChartObject
{
    /**
     * Create the chart and trends for the specified date.
     *
     * @param date $date The date of the chart in Ymd format
     *
     * @return boolean Whether the chart needed to be created or not.
     */
    function __construct($date = '')
    {
        $db = Database::getConnection(true);
        if ($date != '' || strtotime(UI::getLongDate($date)) === false) {
            $date = date("Ymd");
        }
        $sql = "SELECT datChart FROM chart WHERE datChart = ? LIMIT 0, 1";
        $query = $db->prepare($sql);
        $query->execute(array($date));
        // This section of code, thanks to code example here:
        // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
        if ($query->errorCode() != 0) {
            throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>$date, 'error'=>$query->errorInfo()), true), 1);
        }
        if ($query->fetch() || 0 + $date < 20000000) {
            return false;
        }
        $chartdate = UI::getLongDate($date);
        $trenddate = date("Y-m-d", strtotime("-1 day", strtotime($chartdate)));
        $sql = "SELECT t.intTrackID, t.intChartPlace, count(t.intTrackID) * ((100-(IFNULL(MAX(intShowCount),0)*5))/100) AS decVotes
                FROM tracks AS t
    	        LEFT JOIN (SELECT vt.intTrackID FROM votes as vt WHERE vt.datTimeStamp <= $date) as v on v.intTrackID = t.intTrackID
        	    LEFT JOIN (
            	    SELECT st.intTrackID, count(st.intTrackID) AS intShowCount
                    FROM showtracks as st, shows AS s
                    WHERE (
        					(s.enumShowType = 'weekly' AND s.intShowUrl <= '$date' AND s.intShowUrl > '20000000') OR
        					(s.enumShowType = 'monthly' AND s.intShowUrl <= '" . substr($date, 0, 6) . "' AND s.intShowUrl > '200000')
    					  ) AND s.intShowID = st.intShowID
                    GROUP BY intTrackID
	            ) AS s ON s.intTrackID = t.intTrackID
    	        WHERE t.isApproved = 1
        	    GROUP BY t.intTrackID
            	ORDER BY decVotes DESC, intTrackID ASC";
        $chart_sql = "INSERT INTO chart (datChart, intPositionID, intTrackID) VALUES (?, ?, ?)";
        $chartsql = $db->prepare($chart_sql);
        $update_sql = "UPDATE tracks SET intChartPlace = ? WHERE intTrackID = ?";
        $update = $db->prepare($update_sql);
        $counter = 0;
        foreach ($db->query($sql) as $data) {
            $counter++;
            $chartsql->execute(array($chartdate, $counter, $data['intTrackID']));
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($chartsql->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r(array('sql'=>$chart_sql, 'values'=>array($chartdate, $counter, $data['intTrackID']), 'error'=>$chartsql->errorInfo()), true), 1);
            }
            if ($data['intChartPlace'] != $counter) {
                $update->execute(array($counter, $data['intTrackID']));
                // This section of code, thanks to code example here:
                // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
                if ($update->errorCode() != 0) {
                    throw new Exception("SQL Error: " . print_r(array('sql'=>$update_sql, 'values'=>array($counter, $data['intTrackID']), 'error'=>$update->errorInfo()), true), 1);
                }

            }
        }
        $sql = "SELECT intTrackID, count(intVoteID) AS intVoteCount
                FROM votes
                WHERE datTimestamp>='$trenddate 00:00:00' AND datTimestamp<='$trenddate 23:59:59'
                GROUP BY intTrackID";
        $trend_sql = "INSERT INTO trends (datTrendDay, intTrackID, intVotes) VALUES (?, ?, ?)";
        $trend = $db->prepare($trend_sql);
        foreach ($db->query($sql) as $data) {
            if ($data['intTrackID'] != '' and $data['intVoteCount'] != '') {
                $trend->execute(array($trenddate, $data['intTrackID'], $data['intVoteCount']));
                // This section of code, thanks to code example here:
                // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
                if ($trend->errorCode() != 0) {
                    throw new Exception("SQL Error: " . print_r(array('sql'=>$trend_sql, 'values'=>array($trenddate, $data['intTrackID'], $data['intVoteCount']), 'error'=>$query->errorInfo()), true), 1);
                }
            }
        }
        return true;
    }
}