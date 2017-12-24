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
 * This class scrapes appropriate data from www.riffworld.com
 * Use http://www.riffworld.com/Members/Brutu/carajillo as the template
 *
 * @category Default
 * @package  MusicSources
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */
class RemoteSourcesRiffworld extends RemoteSources
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
        if (preg_match('/http[s]*:\/\/.*riffworld.com\/[Mm]embers\/[^\/]+\/[^\/]+/', $src) == 0) {
            return 406;
        }
        $file_contents = file_get_contents($src);
        if ($file_contents == FALSE or $file_contents == '') {
            return 406;
        }
        $regex_strArtistName = '/<strong>Artist:<\/strong> <span>([^<]*)<\/span>/';
        $regex_strTrackName = '/<strong>Title:<\/strong> <span>([^<]*)<\/span>/';
        $regex_strArtistUrl = '/<a href="(http[s]*:\/\/.*riffworld.com\/[Mm]embers\/[^"^\/]+)"/';
        $regex_strFileUrl = '/so.addVariable\("mp3URL", "([^"]*)"\);/';
        $regex_enumTrackLicense = '/\s+<strong>License:<\/strong>\s+<span>([^<]*)<\/span>\s+<br \/>/';
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
        if (preg_match($regex_strFileUrl, $src, $arrFileUrl) > 0) {
            $this->set_fileUrl($arrFileUrl[1]);
        }
        $license = '';
        if (preg_match($regex_enumTrackLicense, $file_contents, $arrTrackLicense) > 0) {
            if (preg_match('/(Attribution)/', $arrTrackLicense[1]) > 0) {
                if ($license != '') {
                    $license .= '-';
                }
                $license .= 'by';
            }
            if (preg_match('/(Noncommercial)/', $arrTrackLicense[1]) > 0) {
                if ($license != '') {
                    $license .= '-';
                }
                $license .= 'nc';
            }
            if (preg_match('/(No Derivative)/', $arrTrackLicense[1]) > 0) {
                if ($license != '') {
                    $license .= '-';
                }
                $license .= 'nd';
            }
            if (preg_match('/(Share Alike)/i', $arrTrackLicense[1]) > 0) {
                if ($license != '') {
                    $license .= '-';
                }
                $license .= 'sa';
            }
            $this->set_enumTrackLicense(LicenseSelector::validateLicense('cc-' . $license));
        }
        return $this->create_pull_entry();
    }
}
