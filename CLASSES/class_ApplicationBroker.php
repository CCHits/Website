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
 * This class knows how to do everything with Application Objects
 *
 * @category Default
 * @package  Brokers
 * @author   Yannick Mauray <yannick.mauray@gmail.com>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */
class ApplicationBroker
{
    /**
     * This function retreives all applications registered by a given developer
     * 
     * @param int $intDeveloperID developer id
     * 
     * @return Application[] array of the given user's application. Can be empty.
     */
    public static function getApplicationsForDeveloper($intDeveloperID)
    {
        $db = Database::getConnection();
        $sql = "SELECT * FROM applications WHERE intDeveloperID = ? ORDER BY strApplicationName ASC";
        $query = $db->prepare($sql);
        $values = [$intDeveloperID];
        $query->execute($values);
        // This section of code, thanks to code example here:
        // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
        if ($query->errorCode() != 0) {
            throw new Exception(
                "SQL Error: " . print_r(array('sql'=>$sql, 'values'=>$values, 'error'=>$query->errorInfo()), true), 1
            );
        }
        $applications = $query->fetchAll(PDO::FETCH_CLASS, "ApplicationObject");
        return $applications;
    }

    /**
     * This function creates a new application.
     * 
     * @param integer $intDeveloperID Developer ID
     * @param string  $strName        Application name
     * @param string  $strDescription Application description
     * @param string  $strUrl         Application home page
     * 
     * @return void
     */
    public static function createApplication($intDeveloperID, $strName, $strDescription, $strUrl)
    {
        $t = time();
        $clientID = dechex($t);
        $clientID = substr($clientID, strlen($clientID) - 4, 4);
        $clientID = strrev($clientID) . dechex(rand(0, 255)) . dechex(rand(0, 255));
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $shareSecret = substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr($charid, 12, 4) 
            . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12);
        $db = Database::getConnection();
        $sql = "INSERT INTO applications(intDeveloperID, strApplicationName, strApplicationDescription, " .
        "strApplicationURL, strApplicationClientID, strSharedSecret) VALUES(?, ?, ?, ?, ?, ?)";
        $query = $db->prepare($sql);
        $values = [$intDeveloperID, $strName, $strDescription, $strUrl, $clientID, $shareSecret];
        $query->execute($values);
        // This section of code, thanks to code example here:
        // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
        if ($query->errorCode() != 0) {
            throw new Exception(
                "SQL Error: " . print_r(array('sql'=>$sql, 'values'=>$values, 'error'=>$query->errorInfo()), true), 1
            );
        }

        return $shareSecret;
    }
}
