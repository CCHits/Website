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
 * This class scrapes appropriate data from www.riffworld.com
 * TODO: Incomplete Import from Macjams.com
 *
 * @category Default
 * @package  MusicSources
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
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
            throw new RemoteSource_INVALIDSRC();
        }
        $file_contents = file_get_contents($src);
        if ($file_contents == FALSE or $file_contents == '') {
            throw new RemoteSource_INVALIDSRC();
        }
        //var_dump($file_contents);
        $regex_strArtistName = '/<a href="\/artist\/[\"]+">([^<]*)<\/a>/';
        $regex_strTrackName = '/<h1 style="[^"]+">([^<]*)<\/h1>/';
        $regex_strArtistUrl = '/<a href="(\/artist\/[\"]+)">[^<]*<\/a>/';
        $regex_enumTrackLicense = '/licenses\/(.*)\/[0-9]/';
        $this->strTrackUrl = $src;
        preg_match($regex_strArtistName, $file_contents, $arrArtistName);
        preg_match($regex_strTrackName, $file_contents, $arrTrackName);
        preg_match($regex_strArtistUrl, $src, $arrArtistUrl);
        preg_match($regex_enumTrackLicense, $file_contents, $arrTrackLicense);
        var_dump(array('artistName'=>$arrArtistName, 'trackName'=>$arrTrackName, 'artistUrl'=>$arrArtistUrl, 'fileUrl'=>$arrFileUrl, 'trackLicense'=>$arrTrackLicense));
        /*
        $this->strArtistName = $arrArtistName[1];
        $this->strTrackName = $arrTrackName[1];
        $this->strArtistUrl = $arrArtistUrl[1];
        $this->fileUrl = $src . '/mp3file.mp3';
        $this->enumTrackLicense = $arrTrackLicense[1];
        return $this->is_valid_cchits_submission();
        */
    }
}
