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
 * This class gets appropriate data from an uploaded file
 *
 * @category Default
 * @package  MusicSources
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
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

        $arrLibs = new ExternalLibraryLoader();
        $GETID3 = $arrLibs->getVersion("GETID3");
        if ($GETID3 == false) {
            error_log("Failed to load Media Handler - No library exists");
            die("There was an error - please contact an administrator.");
        }
        $getid3lib = dirname(__FILE__) . '/../EXTERNALS/GETID3/' . $GETID3 . '/getid3/getid3.php';
        if (file_exists($getid3lib)) {
            include_once $getid3lib;
        } else {
            error_log("Failed to load Media Handler - include file doesn't exist (looking for $getid3lib)");
            die("There was an error - please contact an administrator.");
        }
        $getID3 = new getID3;
        $file = $getID3->analyze($src);

        if (isset($file['tags']['vorbiscomment'])) {
            $this->set_strTrackName($file['tags']['vorbiscomment']['title'][0]);
            $this->set_strArtistName($file['tags']['vorbiscomment']['artist'][0]);
        } elseif (isset($file['tags']['id3v2'])) {
            $this->set_strTrackName($file['tags']['id3v2']['title'][0]);
            $this->set_strArtistName($file['tags']['id3v2']['artist'][0]);
        } elseif (isset($file['tags']['id3v1'])) {
            $this->set_strTrackName($file['tags']['id3v1']['title'][0]);
            $this->set_strArtistName($file['tags']['id3v1']['artist'][0]);
        } elseif (isset($file['tags']['quicktime'])) {
            $this->set_strTrackName($file['tags']['quicktime']['title'][0]);
            $this->set_strArtistName($file['tags']['quicktime']['artist'][0]);
        }
        $this->set_fileName($src);
        return $this->create_pull_entry();
    }
}
