<?php
/**
 * CCHits.net is a website designed to promote Creative Commons Music,
 * the artists who produce it and anyone or anywhere that plays it.
 * These files are used to generate the site.
 *
 * PHP version 5
 *
 * @category Default
 * @package  MusicSources
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
/**
 * This class scrapes appropriate data from alonetone.com
 * Used http://alonetone.com/pasha/tracks/cross-the-line as scrapeable template
 *
 * @category Default
 * @package  MusicSources
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class RemoteSourcesAlonetone extends RemoteSources
{
    /**
    * Get all the source data we can pull from the source.
    *
    * @param string $src Source URL for the retriever
    *
    * @return const A value explaining the outcome of the fetch request
    */
    function __construct($src)
    {
        if (preg_match('/http[s]*:\/\/.*alonetone.com\/[^\/]+/tracks\/[^\/]+/', $src) == 0) {
            return 406;
        }
        $file_contents = file_get_contents($src);
        if ($file_contents == FALSE or $file_contents == '') {
            return 406;
        }
        $regex_strArtistName = '/<h1 class="user">([^<]*)<\/h1>/';
        $regex_strTrackName = '/<a href="[^"]*" class="track_link" title="[^"]*">([^<]*)<\/a>/';
        $regex_strArtistUrl = '/<a href="[^"]*" class="artist"/';
        $regex_strFileUrl = '/<a href="([^"]*)" class="download button"/';
        $regex_enumTrackLicense = '/licenses\/([^\/]*)\//';
        $this->strTrackUrl = $src;
        if (preg_match($regex_strArtistName, $file_contents, $arrArtistName) > 0) {
            $this->set_strArtistName($arrArtistName[1]);
        }
        if (preg_match($regex_strTrackName, $file_contents, $arrTrackName) > 0) {
            $this->set_strTrackName($arrTrackName[1]);
        }
        if (preg_match($regex_strArtistUrl, $src, $arrArtistUrl) > 0) {
            $this->set_strArtistUrl('http://alonetone.com' . $arrArtistUrl[1]);
        }
        if (preg_match($regex_strFileUrl, $src, $arrFileUrl) > 0) {
            $this->set_fileUrl('http://alonetone.com' . $arrFileUrl[1]);
        }
        if (preg_match($regex_enumTrackLicense, $file_contents, $arrTrackLicense) > 0) {
            $this->set_enumTrackLicense(LicenseSelector::validateLicense($arrTrackLicense[1]));
        }
        return $this->create_pull_entry();
    }
}
