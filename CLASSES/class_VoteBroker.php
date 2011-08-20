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
 * This class knows how to do everything with Vote Objects
 *
 * @category Default
 * @package  Brokers
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class VoteBroker
{
    /**
     * Get the number of votes for a track, as a total, and per-show.
     *
     * @param integer $intTrackID The Track ID
     *
     * @return false|array Either false or an array of the votes
     */
    function getVotesForTrackByShow($intTrackID = 0)
    {
        $db = Database::getConnection();
        try {
            $voteadj = 0;
            $sql = "SELECT count(intVoteID) as intCount, intShowID FROM votes WHERE intTrackID = ? GROUP BY intShowID";
            $query = $db->prepare($sql);
            $query->execute(array($intTrackID));
            $item = $query->fetchObject('VoteObject');
            if ($item == false) {
                return false;
            } else {
                $return[] = $item;
                while ($item = $query->fetchObject('VoteObject')) {
                    $return[] = $item;
                }
                foreach ($return as $object) {
                    $count = $count + $object->get_intCount();
                }
                $shows = ShowBroker::getShowsByIDs($return);
                foreach ($shows as $show) {
                    switch($show->get_enumShowType) {
                    case 'weekly':
                    case 'monthly':
                        $voteadj++;
                    }
                }
                return array('total'=>$count, 'adjusted'=>$count * ((100 - ($voteadj * 5)) / 100), 'shows'=>$return);
            }
        } catch(Exception $e) {
            echo "SQL Died: " . $e->getMessage();;
            return false;
        }
    }

    /**
     * This function checks whether this user has voted for this track before.
     *
     * @param integer $intTrackID The track being voted upon
     *
     * @return boolean True if they've voted, false if they haven't.
     */
    function hasMyUserIDVotedForThisTrack($intTrackID = 0)
    {
        $db = Database::getConnection();
        try {
            $voteadj = 0;
            $sql = "SELECT intVoteID FROM votes WHERE intTrackID = ? AND intUserID = ? LIMIT 1";
            $query = $db->prepare($sql);
            $query->execute(array($intTrackID, UserBroker::getUser()->get_intUserID()));
            if ($query->fetch()) {
                return true;
            } else {
                return false;
            }
        } catch(Exception $e) {
            echo "SQL Died: " . $e->getMessage();;
            return false;
        }
    }

    /**
     * This function ensures that there are no duplicated votes for a track which has been duplicated.
     *
     * @param integer $intOldTrackID The Duplicated Track
     * @param integer $intNewTrackID The Original Track
     *
     * @return boolean Worked or it didn't
     */
    public function MergeVotes($intOldTrackID = 0, $intNewTrackID = 0)
    {
        $db = Database::getConnection();
        try {
            $sql = "UPDATE IGNORE votes SET intTrackID = ? WHERE intTrackID = ?; DELETE FROM votes WHERE intTrackID = ?";
            $query = $db->prepare($sql);
            $query->execute(array($intNewTrackID, $intOldTrackID, $intOldTrackID));
            return true;
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
    }
}
