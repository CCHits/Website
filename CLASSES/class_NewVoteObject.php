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
 * This class creates new Vote Objects.
 *
 * @category Default
 * @package  Objects
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */
class NewVoteObject extends VoteObject
{
    /**
     * Create the vote object
     *
     * @param integer $intTrackID Track to vote for
     * @param integer $intShowID  Show the track was in
     *
     * @return integer intVoteID of cast vote.
     */
    public function __construct(
        $intTrackID = 0,
        $intShowID = 0
    ) {
        $track = TrackBroker::getTrackByID($intTrackID);
        if ($track == false) {
            return false;
        }
        if (! VoteBroker::hasMyUserIDVotedForThisTrack($intTrackID)) {
            if ($intShowID != 0) {
                $show = ShowBroker::getShowByID($intShowID);
                if ($show == false) {
                    $intShowID = 0;
                }
            }
            $this->set_intTrackID($intTrackID);
            $this->set_intShowID($intShowID);
            $this->set_intUserID(UserBroker::getUser());
            return $this->create();
        } else {
            return false;
        }
    }
}
