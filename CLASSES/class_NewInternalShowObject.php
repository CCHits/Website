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
 * This class extends the ShowObject class to create a new item in the database. It is specifically for internal shows.
 *
 * @category Default
 * @package  Objects
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */
class NewInternalShowObject extends ShowObject
{
    protected $arrDBItems = array('intShowUrl'=>true, 'enumShowType'=>true, 'intUserID'=>true, 'datDateAdded'=>true);

    /**
     * Establish the creation of the new item by setting the values and then calling the create function.
     *
     * @param integer $intShowUrl   The date of the show in YYYYMMDD format
     * @param string  $enumShowType The type of show this is (currently daily/weekly/monthly)
     *
     * @return boolean Creation status
     */
    public function __construct($intShowUrl = 0, $enumShowType = "")
    {
        if ($intShowUrl != 0 and $enumShowType != "") {
            $this->set_enumShowType($enumShowType);
            $this->set_intShowUrl($intShowUrl);
            $this->set_intUserID(UserBroker::getUser()->get_intUserID());
            $this->set_datDateAdded(date("Y-m-d H:i:s"));
            return $this->create();
        }
    }
}
