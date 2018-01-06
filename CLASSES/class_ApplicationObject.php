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
 * @author   Yannick Mauray <yannick.mauray@gmail.com>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */
/**
 * This class deals with application objects
 *
 * @category Default
 * @package  Objects
 * @author   Yannick Mauray <yannick.mauray@gmail.com>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */

class ApplicationObject extends GenericObject
{
    // Local Properties
    protected $intApplicationID = 0;
    protected $intDeveloperID = 0;
    protected $strApplicationName = null;
    protected $strApplicationClientID = null;
    protected $strApplicationState = null;

    /**
     * Add the collected generated data to the getSelf function
     *
     * @return array The amassed data from this function
     */
    public function getSelf()
    {
        $return = parent::getSelf();
        $return['intApplicationID'] = $this->intApplicationID;
        $return['intDeveloperID'] = $this->intDeveloperID;
        $return['strApplicationName'] = $this->strApplicationName;
        $return['strApplicationClientID'] = $this->strApplicationClientID;
        $return['strApplicationState'] = $this->strApplicationState;

        return $return;
    }
}
