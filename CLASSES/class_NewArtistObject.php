<?php
/**
 * CCHits.net is a website designed to promote Creative Commons Music,
 * the artists who produce it and anyone or anywhere that plays it.
 * These files are used to generate the site.
 *
 * PHP version 5
 *
 * @category Default
 * @package  CCHitsClass
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
/**
 * This class creates new artist objects
 *
 * @category Default
 * @package  Objects
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class NewArtistObject extends ArtistObject
{
    /**
     * Establish the creation of the new item by setting the values and then calling the create function.
     *
     * @param string $strArtistName       The name of the Artist
     * @param string $strArtistNameSounds How to pronounce the name of the Artist
     * @param string $strArtistUrl        The location to find out more about the Artist
     *
     * @return true|false The state of the creation act
     */
    public function __construct(
        $strArtistName = "",
        $strArtistNameSounds = "",
        $strArtistUrl = ""
    ) {
        if ($strArtistName != "" 
            and $strArtistUrl != "" 
        ) {
            $this->set_strArtistName($strArtistName);
            if ($strArtistNameSounds == "") {
                $this->set_strArtistNameSounds($strArtistName);
            } else {
                $this->set_strArtistNameSounds($strArtistNameSounds);
            }
            $this->set_strArtistUrl($strArtistUrl);
            return $this->create();
        } else {
            return false;
        }
    }
}
