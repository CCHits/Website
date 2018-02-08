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
 * This class scrapes appropriate data from FreeMusicArchive.org
 *
 * @category Default
 * @package  MusicSources
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */
class RemoteSourcesFMA extends RemoteSources
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
        if (preg_match('/http:\/\/freemusicarchive.org\/music\/[^\/]+\/[^\/]+\/.+/', $src, $match) == 0) {
            return 406;
        }
        $file_contents = file_get_contents($src);
        if ($file_contents == false or $file_contents == '') {
            return 406;
        }
        $regex_strArtistName = '/\s+<span class="subh1">\(by <a href="\S+">(.*)<\/a>\)<\/span>/';
        $regex_strTrackName = '/&ldquo;(.*)&rdquo;/';
        $regex_strArtistUrl = '/\s+<span class="subh1">\(by <a href="(\S+)">.*<\/a>\)<\/span>/';
        $regex_fileUrl = '/\s+<a href="(\S+)" class="icn-arrow" title="Download"><\/a>/';
        // phpcs:ignore
        $regex_isNSFW = '/\s+<div class="sbar-stat-auto">\n*\s+<span class="lf120 stathd">Explicit<\/span>\n*\s+<div class="stat-item">(.*)<\/div>\n*\s+<div class="cf"><\/div>\n*\s+<\/div>/m';
        $regex_enumTrackLicense = '/licenses\/(.*)\/[0-9].*>\S/';
        $this->set_strTrackUrl($src);
        if (preg_match($regex_strArtistName, $file_contents, $arrArtistName) > 0) {
            $this->set_strArtistName($arrArtistName[1]);
        }
        if (preg_match($regex_strTrackName, $file_contents, $arrTrackName) > 0) {
            $this->set_strTrackName($arrTrackName[1]);
        }
        if (preg_match($regex_strArtistUrl, $file_contents, $arrArtistUrl) > 0) {
            $this->set_strArtistUrl($arrArtistUrl[1]);
        }
        if (preg_match($regex_fileUrl, $file_contents, $arrFileUrl) > 0) {
            $this->set_fileUrl($arrFileUrl[1]);
        }
        if (preg_match($regex_isNSFW, $file_contents, $arrNSFW) > 0) {
            $this->set_isNSFW($arrNSFW[1]);
        }
        if (preg_match($regex_enumTrackLicense, $file_contents, $arrTrackLicense) > 0) {
            $this->set_enumTrackLicense(LicenseSelector::validateLicense($arrTrackLicense[1]));
        }
        return $this->create_pull_entry();
    }
}
