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
        $db = CF::getFactory()->getConnection();
        try {
            $sql = "SELECT * FROM shows WHERE intShowID = ? LIMIT 1";
            $query = $db->prepare($sql);
            $show = $query->execute(array($intShowID));
            return $show->fetchObject('ShowObject');
        } catch(Exception $e) {
            error_log($e);
            return false;
        }
    }

    /**
     * This function finds a collection of shows by an array of intShowIDs.
     *
     * @param arr $arrShowIDs Show ID to search for
     *
     * @return false|array of ShowObjects
     */
    public function getShowsByIDs($arrShowIDs = array())
    {
        if (is_array($arrShowIDs) and count($arrShowIDs) > 0) {
            $db = CF::getFactory()->getConnection();
            try {
                $sql = "SELECT * FROM shows WHERE intShowID = ? LIMIT 1";
                $query = $db->prepare($sql);
                foreach ($arrShowIDs as $intShowID) {
                    if (is_object($intShowID)) {
                        $intShowID = $intShowID->get_intShowID();
                    }
                    $show = $query->execute(array($intShowID));
                    $return[$intShowID] = $show->fetchObject('ShowObject');
                }
                return $return;
            } catch(Exception $e) {
                error_log($e);
                return false;
            }
        } else {
            return false;
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
        $db = CF::getFactory()->getConnection();
        try {
            $sql = "SELECT * FROM shows WHERE strShowUrl LIKE ? LIMIT 1";
            $query = $db->prepare($sql);
            $query->execute(array($strShowUrl));
            return $query->fetchObject('ShowObject');
        } catch(Exception $e) {
            error_log($e);
            return false;
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
                while ($item != false) {
                    $return[] = $item;
                    $item = $query->fetchObject('ShowObject');
                }
                return $return;
            }
        } catch(Exception $e) {
            error_log($e);
            return false;
        }
    }

    /**
     * Get the most recent show of a specific type
     *
     * @param string  $enumShowType Type of show to look for
     * @param integer $intQuantity  Number of shows to get
     *
     * @return object ShowObject
     */
    function getInternalShowByType($enumShowType = '', $intQuantity = 25)
    {
        switch ($enumShowType) {
        case 'daily':
        case 'weekly':
        case 'monthly':
            break;
        default:
            return false;
        }
        if ( ! is_integer($intQuantity) or $intQuantity < 1 or $intQuantity > 100) {
            $intQuantity = 25;
        }
        $db = CF::getFactory()->getConnection();
        try {
            $sql = "SELECT * FROM shows WHERE enumShowType = ? ORDER BY intShowUrl DESC LIMIT $intQuantity";
            $query = $db->prepare($sql);
            $query->execute(array($enumShowType));
            $item = $query->fetchObject('ShowObject');
            if ($item == false) {
                return false;
            } else {
                while ($item != false) {
                    $return[] = $item;
                    $item = $query->fetchObject('ShowObject');
                }
                return $return;
            }
        } catch(Exception $e) {
            error_log($e);
            return false;
        }
    }

    /**
     * Get the specifically dated show of a specific type
     *
     * @param string  $enumShowType Type of show to look for
     * @param integer $intShowUrl   Show date to retrieve
     *
     * @return object ShowObject
     */
    function getInternalShowByDate($enumShowType = '', $intShowUrl = '')
    {
        switch ($enumShowType) {
        case 'daily':
        case 'weekly':
        case 'monthly':
            break;
        default:
            return false;
        }
        $db = CF::getFactory()->getConnection();
        try {
            $sql = "SELECT * FROM shows WHERE enumShowType = ? and intShowUrl = ?";
            $query = $db->prepare($sql);
            $query->execute(array($enumShowType, $intShowUrl));
            return $query->fetchObject('ShowObject');
        } catch(Exception $e) {
            error_log($e);
            return false;
        }
    }

}
