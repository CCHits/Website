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
 * This class can identify whether to redirect a media lookup to another site.
 *
 * @category Default
 * @package  Redirects
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */

class MediaRedirect
{
    public static function getNewUrl($path, $item, $format)
    {
        $db = Database::getConnection();
        try {
            $sql = "SELECT remotevalue FROM redirectmedia WHERE localvalue = ? LIMIT 1";
            $query = $db->prepare($sql);
            $query->execute(array($path.'/'.$item.'.'.$format));
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>$path.'/'.$item.'.'.$format, 'error'=>$query->errorInfo()), true), 1);
            }
            $item = $query->fetch(PDO::FETCH_ASSOC);
            if (is_array($item) && isset($item['remotevalue'])) {
                $sql = "UPDATE redirectmedia SET hitcount = hitcount + 1 WHERE localvalue = ?";
                $query = $db->prepare($sql);
                $query->execute(array($path.'/'.$item.'.'.$format));
                // This section of code, thanks to code example here:
                // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
                if ($query->errorCode() != 0) {
                    throw new Exception("SQL Error: " . print_r(array('sql'=>$sql, 'values'=>$path.'/'.$item.'.'.$format, 'error'=>$query->errorInfo()), true), 1);
                }
                return $item['remotevalue'];
            }
            return false;
        } catch(Exception $e) {
            error_log($e);
            UI::sendHttpResponse(500);
        }
    }
}