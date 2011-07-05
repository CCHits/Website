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
 * This class scrapes appropriate data from SectionZ.com
 *
 * @category Default
 * @package  MusicSources
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
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
            throw new RemoteSource_INVALIDSRC();
        }
        $file_contents = file_get_contents($src);
        if ($file_contents == FALSE or $file_contents == '') {
            throw new RemoteSource_INVALIDSRC();
        }
        //var_dump($file_contents);
        $regex_strArtistName = '/<strong>[^<]*<\/strong>\s+by\s+:\s+<STRONG><A\s+HREF=".*">([^<]*)/';
        $regex_strTrackName = '/<strong>([^<]*)<\/strong>\s+by\s+:\s+<STRONG><A\s+HREF=".*">[^<]*/';
        $regex_strArtistUrl = '/<strong>[^<]*<\/strong>\s+by\s+:\s+<STRONG><A\s+HREF="(.*)">[^<]*/';
        $regex_fileUrl = "/MM_openBrWindow\('(download[^']*)/";
        $regex_enumTrackLicense = '/licenses\/(.*)\/[0-9]/';
        $this->strTrackUrl = $src;
        preg_match($regex_strArtistName, $file_contents, $arrArtistName);
        preg_match($regex_strTrackName, $file_contents, $arrTrackName);
        preg_match($regex_strArtistUrl, $file_contents, $arrArtistUrl);
        preg_match($regex_fileUrl, $file_contents, $arrFileUrl);
        preg_match($regex_enumTrackLicense, $file_contents, $arrTrackLicense);
        var_dump(array('artistName'=>$arrArtistName, 'trackName'=>$arrTrackName, 'artistUrl'=>$arrArtistUrl, 'fileUrl'=>$arrFileUrl, 'trackLicense'=>$arrTrackLicense));
        /*
        $this->strArtistName = $arrArtistName[1];
        $this->strTrackName = $arrTrackName[1];
        $this->strArtistUrl = 'http://www.sectionz.com/' . $arrArtistUrl[1];
        $this->fileUrl = $this->find_download('http://www.sectionz.com/' . $arrFileUrl[1]);
        $this->enumTrackLicense = $arrTrackLicense[1];
        return $this->is_valid_cchits_submission();
        */
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
