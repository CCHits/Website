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
 * This class provides the debugging framework. It's very basic right now, but
 * should be relatively easy to enhance from here.
 *
 * @category Default
 * @package  Helpers
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class Debug
{
    protected static $debug = null;
    protected static $loglevel = 0;

    /**
     * Set the logging level
     *
     * @param integer|string $level Logging level as numbers or words
     *
     * @return void
     */
    public static function Level($level = 0)
    {
        if (self::$debug == null) {
            self::$debug = new Debug();
        }

        switch(strtolower($level)) {
        case "noisy":
            $level = 6;
            break;
        case "verbose":
            $level = 5;
            break;
        case "debug":
            $level = 4;
            break;
        case "warning":
            $level = 3;
            break;
        case "error":
            $level = 2;
            break;
        case "critical":
            $level = 1;
            break;
        }
        self::$loglevel = $level;
    }

    /**
     * Perform a log action
     *
     * @param string         $message The text of the log message
     * @param integer|string $level   Logging level for this message as a string or integer
     *
     * @return void
     */
    public static function Log($message, $level = 5)
    {
        if (self::$debug == null) {
            self::$debug = new Debug();
        }

        switch(strtolower($level)) {
        case "verbose":
            $level = 5;
            break;
        case "debug":
            $level = 4;
            break;
        case "warning":
            $level = 3;
            break;
        case "error":
            $level = 2;
            break;
        case "critical":
            $level = 1;
            break;
        }

        if ($level <= self::$loglevel) {
            switch($level) {
            case 5:
                $level = "VERBOSE";
                break;
            case 4:
                $level = "DEBUG";
                break;
            case 3:
                $level = "WARNING";
                break;
            case 2:
                $level = "ERROR";
                break;
            case 1:
                $level = "CRITICAL";
                break;
            }
            echo date("H:i:s") . " - $level: $message\r\n";
        }
    }
}
