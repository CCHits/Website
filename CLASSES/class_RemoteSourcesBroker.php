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
 * This class knows every way to get an incomplete Remote Source entry
 *
 * @category Default
 * @package  Brokers
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */

class RemoteSourcesBroker
{
    /**
     * This function finds a Remote Source by it's ID
     *
     * @param integer $intSourceID SourceID to search for
     *
     * @return object|false RemoteSources or false if not existing
     */
    public function getRemoteSourceByID($intSourceID = 0)
    {
        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM processing WHERE intProcessingID = ? LIMIT 1";
            $query = $db->prepare($sql);
            $query->execute(array($intSourceID));
            return $query->fetchObject('RemoteSources');
        } catch(Exception $e) {
            echo "SQL Died: " . $e->getMessage();;
            die();
        }
    }
}
