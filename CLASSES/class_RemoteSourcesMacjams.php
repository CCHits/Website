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
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */
/**
 * This class scrapes appropriate data from Macjams.com
 * Used http://www.macjams.com/song/69976 as scrapable template
 *
 * @category Default
 * @package  MusicSources
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */
class RemoteSourcesMacjams extends RemoteSources
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
        if (preg_match('/http[s]*:\/\/.*macjams.com\/song\/(\d+)/', $src) == 0) {
            return 406;
        }
        $file_contents = file_get_contents($src);
        if ($file_contents == false or $file_contents == '') {
            return 406;
        }
        $regex_strArtistName = '/<h2 style="[^"]*"><a href="[^"]*">([^<]*)<\/a><\/h2>/';
        $regex_strTrackName = '/<h1 style="[^"]*">([^<]*)<\/h1>/';
        $regex_strArtistUrl = '/<h2 style="[^"]*"><a href="([^"]*)">[^<]*<\/a><\/h2>/';
        $regex_strFileUrl = '/so.addVariable\("file","([^"]*)\);/';
        $regex_enumTrackLicense = '/licenses\/([^\/]*)\//';
        $this->set_strTrackUrl($src);
        if (preg_match($regex_strArtistName, $file_contents, $arrArtistName) > 0) {
            $this->set_strArtistName($arrArtistName[1]);
        }
        if (preg_match($regex_strTrackName, $file_contents, $arrTrackName) > 0) {
            $this->set_strTrackName($arrTrackName[1]);
        }
        if (preg_match($regex_strArtistUrl, $src, $arrArtistUrl) > 0) {
            $this->set_strArtistUrl('http://macjams.com' . $arrArtistUrl[1]);
        }
        if (preg_match($regex_strFileUrl, $src, $arrFileUrl) > 0) {
            $this->set_fileUrl($arrFileUrl[1]);
        }
        if (preg_match($regex_enumTrackLicense, $file_contents, $arrTrackLicense) > 0) {
            $this->set_enumTrackLicense(LicenseSelector::validateLicense($arrTrackLicense[1]));
        }
        return $this->create_pull_entry();
    }
}
