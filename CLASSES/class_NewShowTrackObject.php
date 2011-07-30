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
 * This class creates ShowTrack Objects
 *
 * @category Default
 * @package  Objects
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class NewShowTrackObject extends ShowTrackObject
{
    /**
     * Create a new track in the show.
     *
     * @param integer $intTrackID Track to be added
     * @param integer $intShowID  Show the track was in
     *
     * @return boolean The state of the vote insertion.
     */
    public function __construct(
        $intTrackID = 0,
        $intShowID = 0
    ) {
        $track = TrackBroker::getTrackByID($intTrackID);
        $show = ShowBroker::getShowByID($intShowID);
        if ($track == false or $show == false or $show->get_intUserID() != UserBroker::getUser()->get_intUserID()) {
            return false;
        }
        $this->set_intTrackID($intTrackID);
        $this->set_intShowID($intShowID);
        try {
            $db = CF::getFactory()->getConnection();
            $sql = "SELECT max(intPartID) FROM showtracks WHERE intShowID = ? LIMIT 1";
            $query = $db->prepare($sql);
            $query->execute(array($intShowID));
            $intPartID = $query->fetchColumn();
            if ($intPartID == false) {
                $intPartID = 0;
            }
            $intPartID++;
            $this->set_intPartID($intPartID);
        } catch(Exception $e) {
            return false;
        }
        $this->create();
        return $this;
    }

}
