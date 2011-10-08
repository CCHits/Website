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
    protected static $handler = null;
    protected $arrShows = array();
    protected $arrDates = array();

    /**
     * An internal function to make this a singleton
     *
     * @return object This class by itself.
     */
    private static function getHandler()
    {
        if (self::$handler == null) {
            self::$handler = new self();
        }
        return self::$handler;
    }

    /**
     * This function finds a show by it's intShowID.
     *
     * @param int $intShowID Show ID to search for
     *
     * @return object|false ShowObject or false if not existing
     */
    public function getShowByID($intShowID = 0)
    {
        $handler = self::getHandler();
        if (isset($handler->arrShows[$intShowID]) and $handler->arrShows[$intShowID] != false) {
            return $handler->arrShows[$intShowID];
        } else {
            $db = Database::getConnection();
            try {
                $sql = "SELECT * FROM shows WHERE intShowID = ? LIMIT 1";
                $query = $db->prepare($sql);
                $query->execute(array($intShowID));
                $item = $query->fetchObject('ShowObject');
                if ($item != false) {
                    $handler->arrShows[$intShowID] = $item;
                    if ((integer) $item->get_intShowUrl() > 0) {
                        $handler->arrDates[$item->get_intShowUrl()][$item->get_enumShowType()] = $item;
                    }
                }
                return $item;
            } catch(Exception $e) {
                error_log($e);
                return false;
            }
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
        $return = array();
        if (is_array($arrShowIDs) and count($arrShowIDs) > 0) {
            $handler = self::getHandler();
            $gotall = true;
            foreach ($arrShowIDs as $intShowID) {
                if (is_object($intShowID)) {
                    $intShowID = $intShowID->get_intShowID();
                }
                if (!isset($handler->arrShows[$intShowID]) or $handler->arrShows[$intShowID] == false) {
                    $gotall = false;
                } else {
                    $return[$intShowID] = $handler->arrShows[$intShowID];
                }
            }
            if ($gotall == true) {
                return $return;
            }
            $db = Database::getConnection();
            try {
                $sql = "SELECT * FROM shows WHERE intShowID = ? LIMIT 1";
                $query = $db->prepare($sql);
                foreach ($arrShowIDs as $intShowID) {
                    if (is_object($intShowID)) {
                        $intShowID = $intShowID->get_intShowID();
                    }
                    if (!isset($handler->arrShows[$intShowID])) {
                        $query->execute(array($intShowID));
                        $item = $query->fetchObject('ShowObject');
                        if ($item != false) {
                            $handler->arrShows[$intShowID] = $item;
                            if ((integer) $item->get_intShowUrl() > 0) {
                                $handler->arrDates[$item->get_intShowUrl()][$item->get_enumShowType()] = $item;
                            }
                        }
                        $return[$intShowID] = $item;
                    }
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
     * This function finds a show by it's UserID.
     *
     * @param integer $intUserID The start of the URL
     * @param integer $intPage   The start "page" number
     * @param integer $intSize   The size of each page
     *
     * @return array|false An array of ShowObject or false if not existing
     */
    public function getShowByUserID(
        $intUserID = 0,
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
            $sql = "SELECT * FROM shows WHERE intUserID = ?";
            $pagestart = ($intPage*$intSize);
            $query = $db->prepare($sql . " ORDER BY datDateAdded DESC LIMIT " . $pagestart . ", $intSize");
            $query->execute(array($intUserID));
            $item = $query->fetchObject('ShowObject');
            if ($item == false) {
                return false;
            } else {
                $handler = self::getHandler();
                while ($item != false) {
                    $return[] = $item;
                    $item = $query->fetchObject('ShowObject');
                    if ($item != false) {
                        $handler->arrShows[$item->get_intShowID()] = $item;
                        if ((integer) $item->get_intShowUrl() > 0) {
                            $handler->arrDates[$item->get_intShowUrl()][$item->get_enumShowType()] = $item;
                        }
                    }
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
     * This function finds a show by it's exact (but case insensitive) URL.
     *
     * @param string $strShowUrl The case insensitive URL
     *
     * @return object|false ArtistObject or false if the item doesn't exist
     */
    public function getShowByExactUrl($strShowUrl = "")
    {
        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM shows WHERE strShowUrl LIKE ? LIMIT 1";
            $query = $db->prepare($sql);
            $query->execute(array($strShowUrl));
            $item = $query->fetchObject('ShowObject');
            if ($item != false) {
                $handler = self::getHandler();
                $handler->arrShows[$item->get_intShowID()] = $item;
                if ((integer) $item->get_intShowUrl() > 0) {
                    $handler->arrDates[$item->get_intShowUrl()][$item->get_enumShowType()] = $item;
                }
            }
            return $item;
        } catch(Exception $e) {
            error_log($e);
            return false;
        }
    }
    /**
     * This function finds a show by it's partial URL.
     *
     * @param string  $strShowUrl The start of the URL
     * @param integer $intPage    The start "page" number
     * @param integer $intSize    The size of each page
     *
     * @return array|false An array of ShowObject or false if not existing
     */
    public function getShowByPartialUrl(
        $strShowUrl = "",
        $intPage = null,
        $intSize = null
    ) {
        $arrUri = UI::getUri();
        if ($intPage == null and isset($arrUri['parameters']['page']) and $arrUri['parameters']['page'] > 0) {
            $page = $arrUri['parameters']['page'];
        } elseif ($intPage == null) {
            $page = 0;
        }
        if ($intSize == null and isset($arrUri['parameters']['size']) and $arrUri['parameters']['size'] > 0) {
            $size = $arrUri['parameters']['size'];
        } elseif ($intSize == null) {
            $size = 25;
        }

        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM shows WHERE strShowUrl LIKE ?";
            $pagestart = ($intPage*$intSize);
            $query = $db->prepare($sql . " LIMIT " . $pagestart . ", $intSize");
            $query->execute(array("{$strShowUrl}%"));
            $handler = self::getHandler();
            $item = $query->fetchObject('ShowObject');
            if ($item == false) {
                return false;
            } else {
                $handler = self::getHandler();
                while ($item != false) {
                    $return[] = $item;
                    if ($item != false) {
                        $handler->arrShows[$item->get_intShowID()] = $item;
                        if ((integer) $item->get_intShowUrl() > 0) {
                            $handler->arrDates[$item->get_intShowUrl()][$item->get_enumShowType()] = $item;
                        }
                    }
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
     * This function finds a show by it's partial Name.
     *
     * @param string  $strShowName The start of the Show Name
     * @param integer $intPage     The start "page" number
     * @param integer $intSize     The size of each page
     *
     * @return array|false An array of ShowObject or false if not existing
     */
    public function getShowByPartialName(
        $strShowName = "",
        $intPage = null,
        $intSize = null
    ) {
        $arrUri = UI::getUri();
        if ($intPage == null and isset($arrUri['parameters']['page']) and $arrUri['parameters']['page'] > 0) {
            $page = $arrUri['parameters']['page'];
        } elseif ($intPage == null) {
            $page = 0;
        }
        if ($intSize == null and isset($arrUri['parameters']['size']) and $arrUri['parameters']['size'] > 0) {
            $size = $arrUri['parameters']['size'];
        } elseif ($intSize == null) {
            $size = 25;
        }
        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM shows WHERE strShowName LIKE ?";
            $pagestart = ($intPage*$intSize);
            $query = $db->prepare($sql . " LIMIT " . $pagestart . ", $intSize");
            // This snippet from http://www.php.net/manual/en/function.str-split.php
            preg_match_all('`.`u', $strShowName, $arr);
            $arr = array_chunk($arr[0], 1);
            $arr = array_map('implode', $arr);
            $strShowName = "";
            foreach ($arr as $chrShowName) {
                if (trim($chrShowName) != '') {
                    $strShowName .= "[:space:]*$chrShowName";
                }
            }
            $query->execute(array(".*{$strShowName}[:space:]*.*"));
            $handler = self::getHandler();
            $item = $query->fetchObject('ShowObject');
            if ($item == false) {
                return false;
            } else {
                $handler = self::getHandler();
                while ($item != false) {
                    $return[] = $item;
                    if ($item != false) {
                        $handler->arrShows[$item->get_intShowID()] = $item;
                        if ((integer) $item->get_intShowUrl() > 0) {
                            $handler->arrDates[$item->get_intShowUrl()][$item->get_enumShowType()] = $item;
                        }
                    }
                    $item = $query->fetchObject('ShowObject');
                }
                return $return;
            }
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
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
        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM shows WHERE enumShowType = ? ORDER BY intShowUrl DESC LIMIT $intQuantity";
            $query = $db->prepare($sql);
            $query->execute(array($enumShowType));
            $handler = self::getHandler();
            $item = $query->fetchObject('ShowObject');
            if ($item == false) {
                return false;
            } else {
                $handler = self::getHandler();
                while ($item != false) {
                    $return[] = $item;
                    if ($item != false) {
                        $handler->arrShows[$item->get_intShowID()] = $item;
                        if ((integer) $item->get_intShowUrl() > 0) {
                            $handler->arrDates[$item->get_intShowUrl()][$item->get_enumShowType()] = $item;
                        }
                    }
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
        $handler = self::getHandler();
        if (isset($handler->arrDates[$intShowUrl][$enumShowType])) {
            return $handler->arrDates[$intShowUrl][$enumShowType];
        }
        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM shows WHERE enumShowType = ? and intShowUrl = ?";
            $query = $db->prepare($sql);
            $query->execute(array($enumShowType, $intShowUrl));
            $item = $query->fetchObject('ShowObject');
            if ($item != false) {
                $handler->arrShows[$item->get_intShowID()] = $item;
                if ((integer) $item->get_intShowUrl() > 0) {
                    $handler->arrDates[$item->get_intShowUrl()][$item->get_enumShowType()] = $item;
                }
            }
            return $item;
        } catch(Exception $e) {
            error_log($e);
            return false;
        }
    }

}
