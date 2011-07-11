<?php
/**
 * CCHits.net is a website designed to promote Creative Commons Music,
 * the artists who produce it and anyone or anywhere that plays it.
 * These files are used to generate the site.
 *
 * This file contains just enough to build an SQL connection, but imports the
 * data from the local_config file if it exists, in case the defaults aren't
 * right.
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

$TYPE = 'mysql';
$HOST = '127.0.0.1';
$PORT = '3306';
$BASE = 'cchits';
$USER = 'root';
$PASS = '';

if (file_exists(dirname(__FILE__) . "/LOCAL_CONFIG.php")) {
    include dirname(__FILE__) . "/LOCAL_CONFIG.php";
}

if (!isset($DSN)) {
    $DSN = array(
        'string' => "$TYPE:host=$HOST;port=$PORT;dbname=$BASE",
        'user' => $USER,
        'pass' => $PASS
    );
}

$APPCONFIG = array();
