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
 * This class deals with all things ShowTrack related.
 *
 * @category Default
 * @package  Objects
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */

class ShowTrackObject extends GenericObject
{
    // Inherited Properties
    protected $arrDBItems = array(
        'intTrackID'=>true,
        'intShowID'=>true,
        'intPartID'=>true
    );
    protected $arrDBKeyCol = array(
        'intTrackID'=>true,
        'intShowID'=>true
    );
    protected $strDBTable = "showtracks";
    // Local Properties
    protected $intTrackID = 0;
    protected $intShowID = 0;
    protected $intPartID = 0;
    protected $objTrack = null;

    /**
     * The function to set the track ID
     *
     * @param integer $intTrackID The intTrackID to set
     *
     * @return void
     */
    function set_intTrackID($intTrackID = 0)
    {
        if ($this->intTrackID != $intTrackID) {
            $this->intTrackID = $intTrackID;
            $this->arrChanges['intTrackID'] = true;
        }
    }

    /**
     * The function to set the Show ID
     *
     * @param integer $intShowID The intShowID to set
     *
     * @return void
     */
    function set_intShowID($intShowID = 0)
    {
        if ($this->intShowID != $intShowID) {
            $this->intShowID = $intShowID;
            $this->arrChanges['intShowID'] = true;
        }
    }

    /**
     * The function to set the place this track was in the show
     *
     * @param integer $intPartID The intPartID to set
     *
     * @return void
     */
    function set_intPartID($intPartID = 0)
    {
        if ($this->intPartID != $intPartID) {
            $this->intPartID = $intPartID;
            $this->arrChanges['intPartID'] = true;
        }
    }

    /**
     * Return the intTrackID
     *
     * @return integer TrackID
     */
    function get_intTrackID()
    {
        return $this->intTrackID;
    }

    /**
     * Return the intPartID
     *
     * @return integer PartID
     */
    function get_intPartID()
    {
        return $this->intPartID;
    }

    /**
     * Return the intShowID
     *
     * @return integer ShowID
     */
    function get_intShowID()
    {
        return $this->intShowID;
    }

    /**
     * A wrapper to the track broker
     *
     * @return object TrackObject
     */
    function get_objTrack()
    {
        if ($this->intTrackID != 0) {
            if ($objTrack == null or ($this->intTrackID != $objTrack->get_intTrackID())) {
                $objTrack = TrackBroker::getTrackByID($this->intTrackID);
            }
        }
        return $objTrack;
    }
}
