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
 * This class knows all the ways to find a show.
 *
 * @category Default
 * @package  Brokers
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */

class ShowBroker
{
    /**
     * This function finds a show by it's intShowID.
     *
     * @param int $intShowID Show ID to search for
     *
     * @return object|false ShowObject or false if not existing
     */
    public function getShowByID($intShowID = 0)
    {
        Debug::Log(get_class() . "::getShowByID($intShowID)", "DEBUG");
        $db = CF::getFactory()->getConnection();
        try {
            $sql = "SELECT * FROM shows WHERE intShowID = ? LIMIT 1";
            $query = $db->prepare($sql);
            $query->execute(array($intShowID));
            return $query->fetchObject('ShowObject');
        } catch(Exception $e) {
            echo "SQL Died: " . $e->getMessage();;
            die();
        }
    }
    /**
     * This function finds a show by it's exact (but case insensitive) URL.
     *
     * @param string $strShowUrl The case insensitive URL
     *
     * @return object|false ArtistObject or false if the item doesn't exist
     */
    public function getShowByExactUrl($strShowUrl = "")
    {
        Debug::Log(get_class() . "::getShowByExactUrl($strShowUrl)", "DEBUG");
        $db = CF::getFactory()->getConnection();
        try {
            $sql = "SELECT * FROM shows WHERE strShowUrl LIKE ? LIMIT 1";
            $query = $db->prepare($sql);
            $query->execute(array($strShowUrl));
            return $query->fetchObject('ShowObject');
        } catch(Exception $e) {
            echo "SQL Died: " . $e->getMessage();;
            die();
        }
    }

    /**
     * This function finds a show by it's partial URL.
     *
     * @param string $strShowUrl The start of the URL
     * @param int    $intStart   The start "page" number
     * @param int    $intSize    The size of each page
     *
     * @return array|false An array of ShowObject or false if not existing
     */
    public function getShowByPartialUrl
    (
        $strShowUrl = "", 
        $intStart = 0,
        $intSize = 25
    ) {
        Debug::Log(get_class() . "::getShowByPartialUrl($strShowUrl)", "DEBUG");
        $db = CF::getFactory()->getConnection();
        try {
            $sql = "SELECT * FROM shows WHERE strShowUrl LIKE ?";
            $pagestart = ($intStart*$intSize);
            $query = $db->prepare($sql . " LIMIT " . $pagestart . ", $intSize");
            $query->execute(array("{$strShowUrl}%"));
            $item = $query->fetchObject('ShowObject');
            if ($item == false) {
                return false;
            } else {
                $return[] = $item;
                while ($item = $query->fetchObject('ShowObject')) {
                    $return[] = $item;
                }
                return $return;
            }
        } catch(Exception $e) {
            echo "SQL Died: " . $e->getMessage();;
            die();
        }
    }
}
