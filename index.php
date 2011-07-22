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

$generator = microtime(true);

$arrUri = UI::getUri();
$content = null;

try {
    if (is_array($arrUri)
        and isset($arrUri['path_items'])
        and is_array($arrUri['path_items'])
        and count($arrUri['path_items']) > 0
    ) {
        switch($arrUri['path_items'][0]) {
        case 'media':
            switch($arrUri['path_items'][1]) {
            case 'track':
                $file = ConfigBroker::getConfig('fileBase', '/var/www') . ConfigBroker::getConfig('fileBaseTrack', '/tracks') . "/";
                break;
            case 'daily':
                $file = ConfigBroker::getConfig('fileBase', '/var/www') . ConfigBroker::getConfig('fileBaseDaily', '/daily') . "/";
                break;
            case 'weekly':
                $file = ConfigBroker::getConfig('fileBase', '/var/www') . ConfigBroker::getConfig('fileBaseWeekly', '/weekly') . "/";
                break;
            case 'monthly':
                $file = ConfigBroker::getConfig('fileBase', '/var/www') . ConfigBroker::getConfig('fileBaseMonthly', '/monthly') . "/";
                break;
            default:
                UI::sendHttpResponse(404);
            }
            $file .= $arrUri['path_items'][2] . '.' . $arrUri['format'];
            if (!file_exists($file)) {
                error_log("Could not find $file");
                UI::sendHttpResponse(404);
            } else {
                if ($arrUri['path_items'][1] == 'track' and TrackBroker::getTrackByID($arrUri['path_items'][2])->get_isApproved() == false and UserBroker::getUser()->isAdmin()) {
                    UI::sendHttpResponse(401);
                } else {
                    UI::dl_file_resumable($file, TRUE);
                }
            }
        case 'api':
            $content = new API();
            break;
        default:
            $content = new HTML();
        }
    } else {
        $content = new HTML();
    }
} catch(Exception $e) {
    error_log($e);
    die("An error occurred - we are looking into it.");
}

/**
 * A basic autoloader
 *
 * @param string $className The name of the class we're trying to load
 *
 * @return true|false Whether we were able to load the class.
 */
function __autoload($className)
{
    if (is_file(dirname(__FILE__) . '/CLASSES/class_' . $className . '.php')) {
        include_once dirname(__FILE__) . '/CLASSES/class_' . $className . '.php';
        return true;
    }
    return false;
}
