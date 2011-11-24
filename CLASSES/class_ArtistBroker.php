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
 * TODO: Implement caching as per trackbroker
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
        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM artists WHERE intArtistID = ? LIMIT 1";
            $query = $db->prepare($sql);
            $query->execute(array($intArtistID));
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r($query->errorInfo(), true), 1);
            }
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
     * @param integer $intPage       The start "page" number
     * @param integer $intSize       The size of each page
     *
     * @return array|false An array of ArtistObject or false if the item doesn't
     * exist
     */
    public function getArtistByExactName(
        $strArtistName = "",
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

        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM artists WHERE strArtistName REGEXP ?";
            $pagestart = ($intPage*$intSize);
            $query = $db->prepare($sql . " LIMIT " . $pagestart . ", $intSize");
            // This snippet from http://www.php.net/manual/en/function.str-split.php
            preg_match_all('`.`u', $strArtistName, $arr);
            $arr = array_chunk($arr[0], 1);
            $arr = array_map('implode', $arr);
            $strArtistName = "";
            foreach ($arr as $chrArtistName) {
                if (trim($chrArtistName) != '') {
                    $strArtistName .= "[[:space:]]*$chrArtistName";
                }
            }
            $query->execute(array("{$strArtistName}[[:space:]]*"));
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r($query->errorInfo(), true), 1);
            }
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
     * @param integer $intPage       The start "page" number
     * @param integer $intSize       The size of each page
     *
     * @return array|false An array of ArtistObject or false if not existing
     */
    public function getArtistByPartialName(
        $strArtistName = "",
        $intPage = null,
        $intSize = null
    ) {
        $arrUri = UI::getUri();
        if ($intPage == null and isset($arrUri['parameters']['page']) and $arrUri['parameters']['page'] > 0) {
            $intPage = $arrUri['parameters']['page'];
        } elseif ($intPage == null) {
            $objTrack = 0;
        }
        if ($intSize == null and isset($arrUri['parameters']['size']) and $arrUri['parameters']['size'] > 0) {
            $intSize = $arrUri['parameters']['size'];
        } elseif ($intSize == null) {
            $intSize = 25;
        }

        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM artists WHERE strArtistName REGEXP ?";
            $pagestart = ($intPage*$intSize);
            $query = $db->prepare($sql . " LIMIT " . $pagestart . ", $intSize");
            // This snippet from http://www.php.net/manual/en/function.str-split.php
            preg_match_all('`.`u', $strArtistName, $arr);
            $arr = array_chunk($arr[0], 1);
            $arr = array_map('implode', $arr);
            $strArtistName = "";
            foreach ($arr as $chrArtistName) {
                if (trim($chrArtistName) != '') {
                    $strArtistName .= "[[:space:]]*$chrArtistName";
                }
            }
            $query->execute(array(".*{$strArtistName}[[:space:]]*.*"));
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r($query->errorInfo(), true), 1);
            }
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
            echo "SQL Died: " . $e->getMessage();
            die();
        }
    }

    /**
     * This function finds a artist by its url.
     *
     * @param string  $strArtistUrl The part of the Track name to search for
     * @param integer $intPage      The start "page" number
     * @param integer $intSize      The size of each page
     *
     * @return array|false An array of ArtistObject or false if the item doesn't exist
     */
    public function getArtistByPartialUrl(
        $strArtistUrl = "",
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

        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM artists WHERE strArtistUrl LIKE ?";
            $pagestart = ($intPage*$intSize);
            $query = $db->prepare($sql . " LIMIT " . $pagestart . ", $intSize");
            $query->execute(array("$strArtistUrl%"));
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r($query->errorInfo(), true), 1);
            }
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
