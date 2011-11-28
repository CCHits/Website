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
 * This class extends the TrackObject class to create a new item in the database.
 *
 * @category Default
 * @package  Objects
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class NewTrackObject extends TrackObject
{
    /**
     * Establish the creation of the new item by setting the values and then calling the create function.
     *
     * @param object  $objArtist          ArtistObject
     * @param string  $strTrackName       The name of the track
     * @param string  $strTrackNameSounds How to pronounce the name of the track
     * @param string  $strTrackUrl        The location to find out more about the track
     * @param string  $enumTrackLicense   A string representing the license criteria
     * @param boolean $isNSFW             A value indicating the Work/Family Safe Status of the track
     * @param string  $fileSource         The file name in the media directory
     *
     * @return true|false The state of the creation act
     */
    public function __construct(
        $objArtist = null,
        $strTrackName = "",
        $strTrackNameSounds = "",
        $strTrackUrl = "",
        $enumTrackLicense = "",
        $isNSFW = false,
        $fileSource = ""
    ) {
        if (! is_object($objArtist) and 0 + $objArtist > 0) {
            $objArtist = ArtistBroker::getArtistByID($objArtist);
            if ($objArtist == false) {
                return false;
            }
        }
        $user = UserBroker::getUser();
        $is_uploader = $user->get_isUploader();
        $is_admin = $user->get_isAdmin();
        if ($strTrackNameSounds == '') {
            $strTrackNameSounds = $strTrackName;
        }
        if (($is_uploader or $is_admin)
            and $objArtist != null
            and $strTrackName != ""
            and $strTrackUrl != ""
            and $enumTrackLicense != ""
            and $fileSource != ""
        ) {
            $this->set_intArtistID($objArtist->get_intArtistID());
            $this->set_strTrackName($strTrackName);
            $this->set_strTrackNameSounds($strTrackNameSounds);
            $this->set_strTrackUrl($strTrackUrl);
            $this->set_enumTrackLicense($enumTrackLicense);
            $this->set_isNSFW($isNSFW);
            $this->set_fileSource($fileSource);
            $this->set_md5FileHash('');
            $this->set_isApproved(UserBroker::getUser()->get_isAuthorized());
            return $this->create();
        } else {
            return false;
        }
    }
}
