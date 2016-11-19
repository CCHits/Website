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
 * @author   Yannick Mauray <yannick@frenchguy.ch>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
/**
 * This class handles all APIv2 calls
 *
 * @category Default
 * @package  UI
 * @author   Yannick Mauray <yannick@frenchguy.ch>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class APIv2
{
	public static function getDates() {
		$db = Database::getConnection();
		try {
			$sql = "select DATE_FORMAT(NOW(), '%Y-%c-%d') Today, YEARWEEK(NOW(), 3) YearWeek, STR_TO_DATE(CONCAT(YEARWEEK(NOW(), 3),' Monday'), '%x%v %W') Monday, STR_TO_DATE(CONCAT(YEARWEEK(NOW(), 3),' Sunday'), '%x%v %W') Sunday";
			$query = $db->prepare($sql);
			$query->execute();
			if ($query->errorCode() != 0) {
				throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'error'=>$query->errorInfo()), true), 1);
			}
			$data = $query->fetchAll(PDO::FETCH_ASSOC)[0];
			return $data;
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
	}

	public static function getYearWeek($date) {
		$db = Database::getConnection();
		try {
			$sql = "SELECT YEARWEEK(STR_TO_DATE('" . $date . "', '%Y-%c-%d'), 3) YearWeek";
			$query = $db->prepare($sql);
			$query->execute();
			if ($query->errorCode() != 0) {
				throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'error'=>$query->errorInfo()), true), 1);	
			}
			$data = $query->fetchAll(PDO::FETCH_ASSOC);
			return $data[0]['YearWeek'];
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
		}
	}

	public static function getNewChart($yearweek, $weeks) {
		$db = Database::getConnection();
		try {
			$sql = "SELECT SUM(S.SCORE) intRank, T.* FROM ( SELECT SUM( " . $weeks . " +( YEARWEEK(v.datTimestamp) - " . $yearweek . " ) ) SCORE, v.intTrackID TRACK, YEARWEEK(v.datTimestamp) YEARWEEK FROM votes v WHERE YEARWEEK(v.datTimestamp) <= " . $yearweek . " AND YEARWEEK(v.datTimestamp) > " . $yearweek . " - " . $weeks . " GROUP BY v.intTrackID, YEARWEEK(v.datTimestamp) ORDER BY YEARWEEK DESC, v.intTrackID ASC ) S LEFT JOIN tracks T ON T.intTrackID = S.TRACK GROUP BY S.TRACK ORDER BY intRank DESC";
			$query = $db->prepare($sql);
			$query->execute();
			if ($query->errorCode() != 0) {
				throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'error'=>$query->errorInfo()), true), 1);	
			}
			$data = $query->fetchAll(PDO::FETCH_ASSOC);
			error_log(json_encode($data));
			return $data;
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
		}
	}
}
