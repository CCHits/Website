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
 * This class pulls appropriate data from CCMixter.org
 *
 * @category Default
 * @package  MusicSources
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */
class RemoteSourcesCCMixter extends RemoteSources
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
        if (preg_match('/(^\d+)|http:\/\/ccmixter.org\/files\/[^\/]+\/(\d+)/', $src, $match) == 0) {
            return 406;
        }
        if ($match[1] == "" and isset($match[2]) and $match[2] != "") {
            $match[1] = $match[2];
        }
        $return = array();
        $url_base = 'http://ccmixter.org/api/query?f=json&ids=';
        $file_contents = file_get_contents($url_base . $match[1]);
        if ($file_contents == FALSE) {
            return 406;
        }
        $json_contents = json_decode($file_contents);
        if ($json_contents == FALSE) {
            return 406;
        }
        preg_match("/licenses\/(.*)\/\d/", $json_contents[0]->license_url, $matches);
        $this->set_strTrackName($json_contents[0]->upload_name);
        $this->set_strArtistName($json_contents[0]->user_real_name);
        $this->set_strTrackUrl($json_contents[0]->file_page_url);
        $this->set_strArtistUrl($json_contents[0]->artist_page_url);
        $this->set_enumTrackLicense(LicenseSelector::validateLicense($matches[1]));
        if ($json_contents[0]->upload_extra->nsfw == false) {
            $this->set_isNSFW(0);
        } else {
            $this->set_isNSFW(1);
        }
        $this->set_fileUrl($json_contents[0]->files[0]->download_url);
        return $this->create_pull_entry();
    }
}

