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
 * This class pulls appropriate data from Soundcloud.com
 *
 * @category Default
 * @package  MusicSources
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
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
            return 412;
        }
        if (preg_match('/http:\/\/soundcloud.com\/[^\/]+/.*/', $src) == 0) {
            return 406;
        }
        $url_base = "https://api.soundcloud.com/resolve.json?consumer_key=" . 
            ConfigBroker::getConfig('Soundcloud API') . "&url=";
        $file_contents = file_get_contents($url_base . $src);
        if ($file_contents == false) {
            return 406;
        }
        $json_contents = json_decode($file_contents);
        if ($json_contents == false) {
            return 406;
        }
        if ($json_contents->downloadable != true) {
            return 417;
        }
        $this->set_strTrackName($json_contents->title);
        $this->set_strArtistName($json_contents->user->username);
        $this->set_strTrackUrl($json_contents->permalink_url);
        $this->set_strArtistUrl($json_contents->user->permalink_url);
        $this->set_enumTrackLicense(LicenseSelector::validateLicense($json_contents->license));
        $this->set_fileUrl($json_contents->download_url . "?consumer_key={$soundcloud_api}");
        return $this->create_pull_entry();
    }
}

