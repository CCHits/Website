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
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */
/**
 * This class extends the ShowObject class to create a new item in the database. It is specifically for external shows.
 *
 * @category Default
 * @package  Objects
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */
class NewExternalShowObject extends ShowObject
{
    protected $arrDBItems = array(
        'strShowName'=>true,
        'strShowUrl'=>true,
        'enumShowType'=>true,
        'intUserID'=>true,
        'datDateAdded'=>true
    );

    /**
     * Establish the creation of the new item by setting the values and then calling the create function.
     *
     * @param integer $strShowUrl  The URL for the show notes
     * @param string  $strShowName The name of the show in the track-plays sections
     *
     * @return boolean Creation Status
     */
    public function __construct($strShowUrl = "", $strShowName = "")
    {
        if ($strShowUrl != "" and UserBroker::getUser()->get_isAdmin()) {
            if ($strShowName == "") {
                $strShowName = $strShowUrl;
            }
            $this->set_enumShowType("external");
            $this->set_strShowName($strShowName);
            $this->set_strShowUrl($strShowUrl);
            $this->set_intUserID(UserBroker::getUser()->get_intUserID());
            $this->set_datDateAdded(date("Y-m-d H:i:s"));
            if ($this->create()) {
                return $this->get_intShowID();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
