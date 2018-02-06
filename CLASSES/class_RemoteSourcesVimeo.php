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
 * This class scrapes appropriate data from vimeo.com/musicstore
 * Used http://vimeo.com/musicstore/track/11057 as scrapeable template
 *
 * @category Default
 * @package  MusicSources
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */
class RemoteSourcesVimeo extends RemoteSources
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
        if (preg_match('/http[s]*:\/\/.*vimeo.com\/musicstore\/track\/([\d]+)/', $src, $arrFileUrl) == 0) {
            return 406;
        }
        $file_contents = file_get_contents($src);
        if ($file_contents == false or $file_contents == '') {
            return 406;
        }
        $regex_strArtistName = '/<div class="info">\s+<h1>[^<]+<\/h1>\s+<h2>by ([^<]+)<\/h2>/m';
        $regex_strTrackName = '/<div class="info">\s+<h1>([^<]+)<\/h1>/m';
        $regex_strArtistUrl = '/\((http:\/\/[^\)]+)\)/';
        $regex_enumTrackLicense = '/licenses\/([^\/]*)\//';
        $this->set_strTrackUrl($src);
        if (preg_match($regex_strArtistName, $file_contents, $arrArtistName) > 0) {
            $this->set_strArtistName($arrArtistName[1]);
        }
        if (preg_match($regex_strTrackName, $file_contents, $arrTrackName) > 0) {
            $this->set_strTrackName($arrTrackName[1]);
        }
        if (preg_match($regex_strArtistUrl, $src, $arrArtistUrl) > 0) {
            $this->set_strArtistUrl($arrArtistUrl[1]);
        }
        $this->set_fileUrl('http://vimeo.com/musicstore/preview?id=' . $arrFileUrl[1]);
        if (preg_match($regex_enumTrackLicense, $file_contents, $arrTrackLicense) > 0) {
            $this->set_enumTrackLicense(LicenseSelector::validateLicense($arrTrackLicense[1]));
        }
        return $this->create_pull_entry();
    }
}
