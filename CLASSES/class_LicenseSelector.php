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
 * This class provides all the functions for a track
 *
 * @category Default
 * @package  Objects
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */

class LicenseSelector
{
    /**
     * Convert license strings not matching the recognised format into a recognised format.
     *
     * @param string $strLicense License string to parse
     *
     * @return string Recognised strings.
     */
    function validateLicense($strLicense = "")
    {
        switch($strLicense)
        {
        // Actual valid licenses in the database
        case 'cc-by':
        case 'by':
            return 'cc-by';
        case 'cc-by-sa':
        case 'cc-sa-by':
        case 'by-sa':
        case 'sa-by':
            return 'cc-by-sa';
        case 'cc-sa':
        case 'sa':
            return 'cc-sa';
        case 'cc-by-nc':
        case 'cc-nc-by':
        case 'by-nc':
        case 'nc-by':
            return 'cc-by-nc';
        case 'cc-nc':
        case 'nc':
            return 'cc-nc';
        case 'cc-by-nd':
        case 'cc-nd-by':
        case 'by-nd':
        case 'nd-by':
            return 'cc-by-nd';
        case 'cc-nd':
        case 'nd':
            return 'cc-nd';
        case 'cc-by-sa-nc':
        case 'cc-by-nc-sa':
        case 'cc-nc-by-sa':
        case 'cc-nc-sa-by':
        case 'cc-sa-by-nc':
        case 'cc-sa-nc-by':
        case 'by-sa-nc':
        case 'by-nc-sa':
        case 'nc-by-sa':
        case 'nc-sa-by':
        case 'sa-by-nc':
        case 'sa-nc-by':
            return 'cc-by-nc-sa';
        case 'cc-sa-nc':
        case 'cc-nc-sa':
        case 'sa-nc':
        case 'nc-sa':
            return 'cc-nc-sa';
        case 'cc-by-nd-nc':
        case 'cc-by-nc-nd':
        case 'cc-nc-by-nd':
        case 'cc-nc-nd-by':
        case 'cc-nd-by-nc':
        case 'cc-nd-nc-by':
        case 'by-nd-nc':
        case 'by-nc-nd':
        case 'nc-by-nd':
        case 'nc-nd-by':
        case 'nd-by-nc':
        case 'nd-nc-by':
            return 'cc-by-nc-nd';
        case 'cc-nd-nc':
        case 'cc-nc-nd':
        case 'nd-nc':
        case 'nc-nd':
            return 'cc-nc-nd';
        case 'cc-sampling+':
        case 'cc-sampling-plus':
        case 'sampling+':
        case 'sampling-plus':
            return 'cc-sampling+';
        case 'cc-nc-sampling+':
        case 'cc-sampling+-nc':
        case 'cc-nc-sampling-plus':
        case 'cc-sampling-plus-nc':
        case 'nc-sampling+':
        case 'sampling+-nc':
        case 'nc-sampling-plus':
        case 'sampling-plus-nc':
            return 'cc-nc-sampling+';
        case 'cc-0':
        case 'cc0':
        case '0':
            return 'cc-0';
        case 'WIPE':
        default:
            return 'none specified';
            break;
        }
    }
}
