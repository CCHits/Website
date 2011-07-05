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
 * This class pulls appropriate data from Soundcloud.com
 *
 * @category Default
 * @package  MusicSources
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class RemoteSourcesSoundcloud extends RemoteSources
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
        if (ConfigBroker::getConfig('Soundcloud API') == '') {
            return $this->INVALIDAPICODE;
        }
        if (preg_match('/http:\/\/soundcloud.com\/[^\/]+/.*/', $src) == 0) {
            return $this->INVALIDSRC;
        }
        $url_base = "https://api.soundcloud.com/resolve.json?consumer_key={ConfigBroker::getConfig('Soundcloud API')}&url=";
        $file_contents = file_get_contents($url_base . $src);
        if ($file_contents == FALSE) {
            return $this->INVALIDSRC;
        }
        $json_contents = json_decode($file_contents);
        if ($json_contents == FALSE) {
            return $this->INVALIDSRC;
        }
        if ($json_contents->downloadable != TRUE) {
            return $this->NOFILEDL;
        }
        $this->strTrackName = $json_contents->title;
        $this->strArtistName = $json_contents->user->username;
        $this->strTrackUrl = $json_contents->permalink_url;
        $this->strArtistUrl = $json_contents->user->permalink_url;
        $this->enumTrackLicense = $json_contents->license;
        $this->fileUrl = $json_contents->download_url . "?consumer_key={$soundcloud_api}";
        return $this->is_valid_cchits_submission();
    }
}

