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

$RW_TYPE = 'mysql';
$RW_HOST = '127.0.0.1';
$RW_PORT = '3306';
$RW_BASE = 'cchits';
$RW_USER = 'root';
$RW_PASS = '';

$SPLIT_RO_RW = false;

$RO_TYPE = '';
$RO_HOST = '';
$RO_PORT = '';
$RO_BASE = '';
$RO_USER = '';
$RO_PASS = '';

if (file_exists(dirname(__FILE__) . "/LOCAL_CONFIG.php")) {
    include dirname(__FILE__) . "/LOCAL_CONFIG.php";
}

if (!isset($RW_DSN)) {
    $RW_DSN = array(
        'string' => "$RW_TYPE:host=$RW_HOST;port=$RW_PORT;dbname=$RW_BASE",
        'user' => $RW_USER,
        'pass' => $RW_PASS
    );
}

if (!isset($RO_DSN) and $SPLIT_RO_RW == true) {
    $RO_DSN = array(
        'string' => "$RO_TYPE:host=$RO_HOST;port=$RO_PORT;dbname=$RO_BASE",
        'user' => $RO_USER,
        'pass' => $RO_PASS
    );
}

$APPCONFIG = array();
