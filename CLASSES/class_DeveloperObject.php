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
 * This class deals with developer objects
 *
 * @category Default
 * @package  Objects
 * @author   Yannick Mauray <yannick.mauray@gmail.com>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */

class DeveloperObject extends GenericObject
{
    // Local Properties
    protected $intDeveloperID = 0;
    protected $strEmail = null;
    protected $strPassword = null;

    /**
     * Returns the developer's ID
     * 
     * @return int developer's ID
     */
    public function getID()
    {
        return $this->intDeveloperID;
    }

    /**
     * Add the collected generated data to the getSelf function
     *
     * @return array The amassed data from this function
     */
    public function getSelf()
    {
        $return = parent::getSelf();

        $return['intDeveloperID'] = $this->intDeveloperID;
        $return['strEmail'] = $this->strEmail;
        $return['strPassword'] = $this->strPassword;

        return $return;
    }    
}
