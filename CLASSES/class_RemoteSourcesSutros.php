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
 * This class scrapes appropriate data from Sutros.com
 *
 * @category Default
 * @package  MusicSources
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class RemoteSourcesSutros extends RemoteSources
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
        if (preg_match('/http[s]*:\/\/sutros.com\/songs\/[^\/]+', $src, $match) == 0) {
            throw new RemoteSource_InvalidSource();
        }
        $file_contents = file_get_contents($src);
        if ($file_contents == FALSE or $file_contents = '') {
            throw new RemoteSource_InvalidSource();
        }
        $regex_strArtistName = '/\s*<meta name="audio_artist" content="[^"]+" \/>/';
        $regex_strTrackName = '/\s*<meta name="audio_title" content="[^"]+" \/>/';
        $regex_strArtistUrl = '/\s*<div class="username"><b><a href="[^"]+">The Warloc</a></b></div>/';
        $regex_fileUrl = '/\s*<div id="details" class="song" about="([^"]+)">/';
        $regex_enumTrackLicense = '/licenses\/(.*)\/[0-9].*>\S/';
        $this->strTrackUrl = $src;
        if (preg_match($regex_strArtistName, $file_contents, $arrArtistName) > 0) {
            $this->strArtistName = $arrArtistName[1];
        }
        if (preg_match($regex_strTrackName, $file_contents, $arrTrackName) > 0) {
            $this->strTrackName = $arrTrackName[1];
        }
        if (preg_match($regex_strArtistUrl, $file_contents, $arrArtistUrl) > 0) {
            $this->strArtistUrl = $arrArtistUrl[1];
        }
        if (preg_match($regex_fileUrl, $file_contents, $arrFileUrl) > 0) {
            $this->fileUrl = $arrFileUrl[1];
        }
        if (preg_match($regex_enumTrackLicense, $file_contents, $arrTrackLicense) > 0) {
            $this->enumTrackLicense = $arrTrackLicense[1];
        }
        return $this->is_valid_cchits_submission();
    }
}
