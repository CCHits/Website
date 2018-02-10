<?php
/**
 * CCHits.net is a website designed to promote Creative Commons Music,
 * the artists who produce it and anyone or anywhere that plays it.
 * These files are used to generate the site.
 *
 * This file contains the local modifications to the config settings
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
$RW_HOST = 'localhost';
$RW_PORT = '3306';
$RW_BASE = 'cchits_ci';
$RW_USER = 'cchits_ci';
$RW_PASS = 'cchits_ci';

$SPLIT_RO_RW = false;

date_default_timezone_set("Europe/London");
