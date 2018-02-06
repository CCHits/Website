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
 * This class scrapes appropriate data from SectionZ.com
 * Use http://www.sectionz.com/detail.asp?rType=mp3&SZID=34527 for template scraping
 *
 * @category Default
 * @package  MusicSources
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */
class RemoteSourcesSectionz extends RemoteSources
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
        if (preg_match('/http:\/\/.*sectionz.com\/detail.asp.*/', $src) == 0) {
            return 406;
        }
        $file_contents = file_get_contents($src);
        if ($file_contents == false or $file_contents == '') {
            return 406;
        }
        $regex_strArtistName = '/<strong>[^<]*<\/strong>\s+by\s+:\s+<STRONG><A\s+HREF="[^"]*">([^<]*)/';
        $regex_strTrackName = '/<strong>([^<]*)<\/strong>\s+by\s+:\s+<STRONG><A\s+HREF="[^"]*">[^<]*/';
        $regex_strArtistUrl = '/<strong>[^<]*<\/strong>\s+by\s+:\s+<STRONG><A\s+HREF="([^"]*)">[^<]*/';
        $regex_fileUrl = "/MM_openBrWindow\('(download[^']*)/";
        $regex_enumTrackLicense = '/licenses\/([^\/]*)\//';
        $this->strTrackUrl = $src;
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
            $this->set_fileUrl($this->find_download('http://www.sectionz.com/' . $arrFileUrl[1]));
        }
        if (preg_match($regex_enumTrackLicense, $file_contents, $arrTrackLicense) > 0) {
            $this->set_enumTrackLicense(LicenseSelector::validateLicense($arrTrackLicense[1]));
        }
        return $this->create_pull_entry();
    }

    /**
     * Find the download location for the file
     *
     * @param string $track_url The download handle page.
     *
     * @return const|string Either a fault code or the URL to download
     */
    protected function find_download($track_url = "")
    {
        $status = curl_get($track_url, 0);
        if (false == $status or !is_array($status) or count($status) == 0 or $status[1]['http_code'] == 404) {
            throw new RemoteSource_NOFILEDL();
        } elseif (1 == preg_match('/<META HTTP-EQUIV="refresh" content="5; URL=(.+)">/', $status[0], $matches)) {
            return $matches[1];
        } else {
            throw new RemoteSource_NOFILEDL();
        }
    }
}
