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
 * This class gets appropriate data from an uploaded file
 *
 * @category Default
 * @package  MusicSources
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class RemoteSourcesFile extends RemoteSources
{
    /**
    * Get all the source data we can pull from the source.
    *
    * @param string $src The path to the uploaded file
    *
    * @return const A value explaining the outcome of the fetch request
    */
    function __construct($src)
    {
        if ( ! file_exists($src)) {
            return 400;
        }
        $soxi = ConfigBroker::getAppConfig('soxi', '/usr/bin/soxi');
        $exec_command = "$soxi -a \"$src\"";
        $exec_data = exec($exec_command, $exec_output, $return);
        if (preg_match("/^[Tt][Ii][Tt][Ll][Ee]=(.*)/", $return, $arrTrackName) > 0) {
            $this->set_strTrackName($arrTrackName[1]);
        }
        if (preg_match("/^[Aa][Rr][Tt][Ii][Ss][Tt]=(.*)/", $return, $arrArtistName) > 0) {
            $this->set_strArtistName($arrArtistName[1]);
        }
        $this->set_fileName($src);
        return $this->create_pull_entry();
    }
}
