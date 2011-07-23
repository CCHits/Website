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
 * This class knows every way to get an Artist
 *
 * @category Default
 * @package  Brokers
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */

class ArtistBroker
{
    /**
     * This function finds an artist by their intArtistID.
     *
     * @param integer $intArtistID Artist ID to search for
     *
     * @return object|false ArtistObject or false if not existing
     */
    public function getArtistByID($intArtistID = 0)
    {
        Debug::Log(get_class() . "::getArtistByID($intArtistID)", "DEBUG");
        $db = CF::getFactory()->getConnection();
        try {
            $sql = "SELECT * FROM artists WHERE intArtistID = ? LIMIT 1";
            $query = $db->prepare($sql);
            $query->execute(array($intArtistID));
            return $query->fetchObject('ArtistObject');
        } catch(Exception $e) {
            echo "SQL Died: " . $e->getMessage();;
            die();
        }
    }

    /**
     * This function finds an artist by their name.
     * It tries to eliminate duplicates by searching for the name searching
     * for any instance of the name with or without spaces between characters.
     * This is in response to an issue with multiple instances of "TenPenny Joke"
     *
     * @param string  $strArtistName The exact artist name to search for
     * @param integer $intStart      The start "page" number
     * @param integer $intSize       The size of each page
     *
     * @return array|false An array of ArtistObject or false if the item doesn't
     * exist
     */
    public function getArtistByExactName(
        $strArtistName = "",
        $intStart = 0,
        $intSize = 25
    ) {
        Debug::Log(get_class() . "::getArtistByExactName($strArtistName)", "DEBUG");
        $db = CF::getFactory()->getConnection();
        try {
            $sql = "SELECT * FROM artists WHERE strArtistName REGEXP ?";
            $pagestart = ($intStart*$intSize);
            $query = $db->prepare($sql . " LIMIT " . $pagestart . ", $intSize");
            // This snippet from http://www.php.net/manual/en/function.str-split.php
            preg_match_all('`.`u', $strArtistName, $arr);
            $arr = array_chunk($arr[0], 1);
            $arr = array_map('implode', $arr);
            $strArtistName = "";
            foreach ($arr as $chrArtistName) {
                if (trim($chrArtistName) != '') {
                    $strArtistName .= "[:space:]*$chrArtistName";
                }
            }
            $query->execute(array("{$strArtistName}[:space:]*"));
            $item = $query->fetchObject('ArtistObject');
            if ($item == false) {
                return false;
            } else {
                $return[] = $item;
                while ($item = $query->fetchObject('ArtistObject')) {
                    $return[] = $item;
                }
                return $return;
            }
        } catch(Exception $e) {
            echo "SQL Died: " . $e->getMessage();;
            die();
        }
    }

    /**
     * This function finds an artist by their name.
     * It tries to eliminate duplicates by searching for the name searching
     * for any instance of the name with or without spaces between characters.
     * This is in response to an issue with multiple instances of "TenPenny Joke"
     *
     * @param string  $strArtistName The artist name to search for
     * @param integer $intStart      The start "page" number
     * @param integer $intSize       The size of each page
     *
     * @return array|false An array of ArtistObject or false if not existing
     */
    public function getArtistByPartialName(
        $strArtistName = "",
        $intStart = 0,
        $intSize = 25
    ) {
        $db = CF::getFactory()->getConnection();
        try {
            $sql = "SELECT * FROM artists WHERE strArtistName REGEXP ?";
            $pagestart = ($intStart*$intSize);
            $query = $db->prepare($sql . " LIMIT " . $pagestart . ", $intSize");
            // This snippet from http://www.php.net/manual/en/function.str-split.php
            preg_match_all('`.`u', $strArtistName, $arr);
            $arr = array_chunk($arr[0], 1);
            $arr = array_map('implode', $arr);
            $strArtistName = "";
            foreach ($arr as $chrArtistName) {
                if (trim($chrArtistName) != '') {
                    $strArtistName .= "[:space:]*$chrArtistName";
                }
            }
            $query->execute(array(".*{$strArtistName}[:space:]*.*"));
            $item = $query->fetchObject('ArtistObject');
            if ($item == false) {
                error_log("SQL error: " . print_r($query->errorInfo(), TRUE));
                return false;
            } else {
                $return[] = $item;
                while ($item = $query->fetchObject('ArtistObject')) {
                    $return[] = $item;
                }
                return $return;
            }
        } catch(Exception $e) {
            echo "SQL Died: " . $e->getMessage();;
            die();
        }
    }

    /**
     * This function finds a artist by its url.
     *
     * @param string $strArtistUrl The part of the Track name to search for
     * @param int    $intStart     The start "page" number
     * @param int    $intSize      The size of each page
     *
     * @return array|false An array of ArtistObject or false if the item doesn't exist
     */
    public function getArtistByPartialUrl(
        $strArtistUrl = "",
        $intStart = 0,
        $intSize = 25
    ) {
        $db = CF::getFactory()->getConnection();
        try {
            $sql = "SELECT * FROM artists WHERE strArtistUrl LIKE ?";
            $pagestart = ($intStart*$intSize);
            $query = $db->prepare($sql . " LIMIT " . $pagestart . ", $intSize");
            $query->execute(array("$strArtistUrl%"));
            $item = $query->fetchObject('ArtistObject');
            if ($item == false) {
                return false;
            } else {
                $return[] = $item;
                while ($item = $query->fetchObject('ArtistObject')) {
                    $return[] = $item;
                }
                return $return;
            }
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
    }

}
