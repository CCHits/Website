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
 * This class deals with the act of voting, and the tallying of votes
 *
 * @category Default
 * @package  Objects
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class VoteObject extends GenericObject
{
    // Inherited Properties
    protected $arrDBItems = array(
        'intTrackID'=>true,
        'intShowID'=>true,
        'intUserID'=>true
    );
    protected $strDBTable = "votes";
    protected $strDBKeyCol = "intVoteID";

    protected $intVoteID = 0;
    protected $intTrackID = 0;
    protected $intShowID = 0;
    protected $intUserID = 0;

    protected $intCount = 0; // Only used in Vote Counters

    /**
     * Return the track ID
     *
     * @return integer The track ID
     */
    function get_intTrackID()
    {
        return $this->intTrackID;
    }

    /**
     * Return the show ID
     *
     * @return integer The show ID
     */
    function get_intShowID()
    {
        return $this->intShowID;
    }

    /**
     * Return the user ID who created the vote
     *
     * @return integer The user ID
     */
    function get_intUserID()
    {
        return $this->intUserID;
    }

    /**
     * Return the user object linked to this vote
     *
     * @return object The user object
     */
    function get_objUser()
    {
        return UserBroker::getUserByID($this->intUserID);
    }

    /**
     * Return the number of votes for this track
     *
     * @return integer The total of votes for this track.
     */
    function get_intCount()
    {
        return $this->intCount;
    }


    /**
     * Set the TrackID
     *
     * @param integer $intTrackID Track the vote is for
     *
     * @return void
     */
    function set_intTrackID($intTrackID = 0)
    {
        if ($this->intTrackID != $intTrackID and 0 + $intTrackID > 0) {
            $this->intTrackID = $intTrackID;
            $this->arrChanges['intTrackID'] = true;
        }
    }

    /**
     * Set the ShowID
     *
     * @param integer $intShowID Show the track is in
     *
     * @return void
     */
    function set_intShowID($intShowID = 0)
    {
        if ($this->intShowID != $intShowID and 0 + $intShowID > 0) {
            $this->intShowID = $intShowID;
            $this->arrChanges['intShowID'] = true;
        }
    }

    /**
     * Set the UserID from the User Object
     *
     * @param object $objUser The User Object
     *
     * @return void
     */
    function set_intUserID($objUser = null)
    {
        if ($objUser != null and $objUser != false and is_object($objUser)) {
            $this->intUserID = $objUser->get_intUserID();
            $this->arrChanges['intUserID'] = true;
        }
    }

    /**
     * While, it shouldn't be needed, if a track has been voted for on a show which doesn't exist,
     * increment the "show" 0 (aka not a show) counter.
     *
     * @param integer $intCount The value to be added to the counter
     *
     * @return void
     */
    function inc_intCount($intCount = 0)
    {
        if ($intCount > 0) {
            $this->intCount = $this->intCount + $intCount;
        }
    }
}
