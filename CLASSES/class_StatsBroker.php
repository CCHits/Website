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
 * @author   Yannick Mauray <yannick.mauray@gmail.com>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */
/**
 * This class deals with all things Stats related.
 *
 * @category Default
 * @package  Brokers
 * @author   Yannick Mauray <yannick.mauray@gmail.com>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */

class StatsBroker
{
    protected static $handler = null;

    /**
     * An internal function to make this a singleton
     *
     * @return object This class by itself.
     */
    private static function _getHandler()
    {
        if (self::$handler == null) {
            self::$handler = new self();
        }
        return self::$handler;
    }

    /**
     * This function returns the site's stats.
     *
     * @return StatsObject|false
     */    
    public static function getStats()
    {
        $db = Database::getConnection();
        try
        {
            $statsObject = new StatsObject();

            $statsObject->setNumberOfTracks(static::getNumberOfTracks());
            $statsObject->setNumberOfArtists(static::getNumberOfArtists());
            $statsObject->setAverageNumberOfTracksPerArtist(static::getAverageTracksPerArtists());
            $result = static::getNumberOfTracksPerLicense();
            $numberOfTracksPerLicense = [
                "cc-by" => 0,
                "cc-by-sa" => 0,
                "cc-sa" => 0,
                "cc-by-nc" => 0,
                "cc-nc" => 0,
                "cc-by-nd" => 0,
                "cc-nd" => 0,
                "cc-by-nc-sa" => 0,
                "cc-nc-sa" => 0,
                "cc-by-nc-nd" => 0,
                "cc-nc-nd" => 0,
                "cc-sampling+" => 0,
                "cc-nc-sampling+" => 0,
                "cc-0" => 0
            ];
            foreach ($result as $value) {
                $numberOfTracksPerLicense[$value['enumTrackLicense']] = $value['intNumberOfTracksPerLicense'];
            }
            $statsObject->setNumberOfTracksPerLicense($numberOfTracksPerLicense);

            $by = 0;
            $nc = 0;
            $sa = 0;
            $nd = 0;
            $sampling = 0;
            $cc0 = 0;
            foreach ($numberOfTracksPerLicense as $license => $numberOfTracks) {
                switch ($license)
                {
                case 'cc-by':
                    $by += $numberOfTracks;
                    break;
                case 'cc-by-sa':
                    $by += $numberOfTracks;
                    $sa += $numberOfTracks;
                    break;
                case 'cc-sa':
                    $sa += $numberOfTracks;
                    break;
                case 'cc-by-nc':
                    $by += $numberOfTracks;
                    $nc += $numberOfTracks;
                    break;
                case 'cc-nc':
                    $nc += $numberOfTracks;
                    break;
                case 'cc-by-nd':
                    $by += $numberOfTracks;
                    $nd += $numberOfTracks;
                    break;
                case 'cc-nd':
                    $nd += $numberOfTracks;
                    break;
                case 'cc-by-nc-sa':
                    $by += $numberOfTracks;
                    $nc += $numberOfTracks;
                    $sa += $numberOfTracks;
                    break;
                case 'cc-nc-sa':
                    $nc += $numberOfTracks;
                    $sa += $numberOfTracks;
                    break;
                case 'cc-by-nc-nd':
                    $by += $numberOfTracks;
                    $nc += $numberOfTracks;
                    $nd += $numberOfTracks;
                    break;
                case 'cc-nc-nd':
                    $nc += $numberOfTracks;
                    $nd += $numberOfTracks;
                    break;
                case 'cc-sampling+':
                    $sampling += $numberOfTracks;
                    break;
                case 'cc-nc-sampling+':
                    $nc += $numberOfTracks;
                    $sampling += $numberOfTracks;
                    break;
                case 'cc-0':
                    $cc0 += $numberOfTracks;
                    break;
                case 'none specified':
                    break;
                default:
                    throw new Exception("Unknown license " . $licenseStats['enumTrackLicense']);
                }
            }
            $statsObject->setNumberOfTracksPerLicenseCriteria(
                ["by" => $by, "nc" => $nc, "nd" => $nd, "sa" => $sa, "sampling+" => $sampling, "0" => $cc0]
            );

            $result = static::getTop10LongestRunningTracksAtNumberOnePosition();
            $result = array_map(
                function ($item) {
                    $item['track'] = TrackBroker::getTrackByID($item['intTrackID'])->getSelf();
                    unset($item['intTrackID']);
                    return $item;
                }, $result
            );

            $statsObject->setTop10LongestRunningTracksAtNumberOnePosition($result);

            return $statsObject;
        }
        catch(Exception $e) 
        {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Query rows.
     * 
     * @param string   $getQuery      the query.
     * @param array    $getParameters the parameters.
     * @param callable $getResults    a function to parse the result.
     * 
     * @return array|bool
     */
    private static function queryRows($getQuery, $getParameters = null, $getResults = null)
    {
        $db = Database::getConnection();
        try
        {
            $sql = $getQuery();
            $query = $db->prepare($sql);
            $parameters = null;
            if ($getParameters != null) {
                $parameters = $getParameters();
                $query->execute($parameters);
            } else {
                $query->execute();
            }
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                $a = array('sql'=>$sql, 'error'=>$query->errorInfo());
                if ($parameters != null) {
                    $a['values'] = $parameters;
                }
                throw new Exception(
                    "SQL Error: " . print_r($a, true), 1
                );
            }
            $results = [];
            while ($assoc = $query->fetch(PDO::FETCH_ASSOC)) {
                if ($getResults != null) {
                    $results[] = $getResults($assoc);
                } else {
                    $results[] = $assoc;
                }
            }
            return $results;
        }
        catch(Exception $e) 
        {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get the number of tracks.
     * 
     * @return int
     */
    private static function getNumberOfTracks()
    {
        return static::queryRows(
            function () {
                return "SELECT COUNT(1) AS intNumberOfTracks FROM tracks";
            }
        )[0]['intNumberOfTracks'];
    }

    /**
     * Get the number of artits.
     * 
     * @return int
     */
    private static function getNumberOfArtists()
    {
        return static::queryRows(
            function () {
                return "SELECT COUNT(1) AS intNumberOfArtists FROM artists";
            }
        )[0]['intNumberOfArtists'];
    }

    /**
     * Get the average tracks per artits.
     * 
     * @return double
     */
    private static function getAverageTracksPerArtists()
    {
        return static::queryRows(
            function () {
                $innerSQL = "SELECT COUNT(1) AS intTracksPerArtist, intArtistID FROM tracks GROUP BY intArtistID";
                return "SELECT AVG(intTracksPerArtist) dblAvgTracksPerArtist FROM (" . $innerSQL . ") tracksPerArtist";
            }
        )[0]['dblAvgTracksPerArtist'];
    }

    /**
     * Get the number of tracks per license.
     * 
     * @return int
     */
    private static function getNumberOfTracksPerLicense()
    {
        return static::queryRows(
            function () {
                return "SELECT COUNT(1) AS intNumberOfTracksPerLicense, enumTrackLicense FROM tracks GROUP BY " .
                    "enumTrackLicense ORDER BY intNumberOfTracksPerLicense DESC";
            }
        );
    }

    /**
     * Get the top 10 longuest running tracks at number one position.
     * 
     * @return int
     */
    private static function getTop10LongestRunningTracksAtNumberOnePosition()
    {
        return static::queryRows(
            function () {
                return "SELECT COUNT(1) numberOfDaysAtPosition1, intTrackID FROM chart WHERE intPositionID = 1 GROUP " .
                    "BY intTrackID ORDER BY numberOfDaysAtPosition1 DESC LIMIT 10";
            }
        );
    }
}
