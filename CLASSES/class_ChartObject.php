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
 * This class creates charts.
 *
 * @category Default
 * @package  Brokers
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
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
        $db = CF::getFactory()->getConnection();
        if ($date != '') {
            $date = date("Ymd");
        }
        $sql = "SELECT datChart FROM chart WHERE datChart = ? LIMIT 0, 1";
        $query = $db->prepare($sql);
        $query->execute(array($date));
        if ($query->fetch()) {
            return false;
        }
        if ($date != '' and 0 + $date > 20000000) {
            $sql_v = "LEFT JOIN (SELECT vt.intTrackID FROM votes as vt WHERE vt.datTimeStamp <= $date) as v on v.intTrackID = t.intTrackID ";
            $sql_st = "and ((s.intShowUrl <= '$date' and s.intShowUrl > '20000000') or (s.intShowUrl <= '" . substr($date, 0, 6) . "' and s.intShowUrl > '200000'))";
            $chartdate = substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2);
            $trenddate = date("Y-m-d", strtotime("-1 day", strtotime($chartdate)));
        } else {
            $sql_v = "JOIN votes AS v ON v.intTrackID = t.intTrackID ";
            $sql_st = '';
            $chartdate = date("Y-m-d");
            $trenddate = date("Y-m-d", strtotime("-1 day"));
        }
        $sql = "SELECT t.intTrackID, t.intChartPlace, count(t.intTrackID) * ((100-(IFNULL(MAX(intShowCount),0)*5))/100) AS decVotes
                FROM tracks AS t
    	        $sql_v
        	    LEFT JOIN (
            	    SELECT st.intTrackID, count(st.intTrackID) AS intShowCount
                    FROM showtracks as st, shows AS s
                    WHERE (s.enumShowType = 'weekly' OR s.enumShowType = 'monthly') $sql_st AND s.intShowID = st.intShowID
                    GROUP BY intTrackID
	            ) AS s ON s.intTrackID = t.intTrackID
    	        WHERE t.isApproved = 1
        	    GROUP BY t.intTrackID
            	ORDER BY decVotes DESC, intTrackID ASC";
        $chartsql = $db->prepare("INSERT INTO chart (datChart, intPositionID, intTrackID) VALUES (?, ?, ?)");
        $update = $db->prepare("UPDATE tracks SET intChartPlace = ? WHERE intTrackID = ?");
        foreach ($db->query($sql) as $data) {
            $counter++;
            $chartsql->execute(array($chartdate, $counter, $data['intTrackID']));
            if ($data['intChartPlace'] != $counter) {
                $update->execute(array($counter, $data['intTrackID']));
            }
        }
        $sql = "SELECT intTrackID, count(intVoteID) AS intVoteCount
                FROM votes
                WHERE datTimestamp>='$trenddate 00:00:00' AND datTimestamp<='$trenddate 25:59:59'
                GROUP BY intTrackID";
        $trend = $db->prepare("INSERT INTO trends (datTrendDay, intTrackID, intVotes) VALUES (?, ?, ?)");
        foreach ($db->query($sql) as $data) {
            if ($data['intTrackID'] != '' and $data['intVoteCount'] != '') {
                $trend->execute(array($trenddate, $data['intTrackID'], $data['intVoteCount']));
            }
        }
        return true;
    }
}