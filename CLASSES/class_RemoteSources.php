<?php
/**
 * CCHits.net is a website designed to promote Creative Commons Music,
 * the artists who produce it and anyone or anywhere that plays it.
 * These files are used to generate the site.
 *
 * PHP version 5
 *
 * @category Default
 * @package  MusicSources
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
/**
 * This class provides the base functionality for all of the remote sources
 *
 * @category Default
 * @package  MusicSources
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class RemoteSources extends GenericObject
{
    protected $arrDBItems = array(
    	'strTrackName'=>true,
    	'strTrackNameSounds'=>true,
    	'strTrackUrl'=>true,
    	'enumTrackLicense'=>true,
    	'intArtistID'=>true,
    	'strArtistName'=>true,
    	'strArtistNameSounds'=>true,
    	'strArtistUrl'=>true,
    	'isNSFW'=>true,
    	'fileUrl'=>true,
    	'fileName'=>true,
    	'intUserID'=>true,
    	'fileMD5'=>true,
    	'forceMD5Duplicate'=>true,
    	'forceTrackNameDuplicate'=>true,
    	'forceTrackUrlDuplicate'=>true
    );
    protected $strDBTable = "processing";
    protected $strDBKeyCol = "intProcessingID";
    protected $arrChanges = array();

    protected $intProcessingID = 0;
    protected $intTrackID = 0;
    protected $strTrackName = "";
    protected $strTrackNameSounds = "";
    protected $strTrackUrl = "";
    protected $enumTrackLicense = "";
    protected $intArtistID = 0;
    protected $strArtistName = "";
    protected $strArtistNameSounds = "";
    protected $strArtistUrl = "";
    protected $isNSFW = 1;
    protected $fileUrl = "";
    protected $fileName = "";
    protected $intUserID = 0;
    protected $fileMD5 = '';
    protected $forceMD5Duplicate = false;
    protected $forceTrackNameDuplicate = false;
    protected $forceTrackUrlDuplicate = false;
    protected $duplicateTracks = false;
    protected $e = false;

    /**
     * Remove this RemoteSource from the Processing table.
     *
     * @return boolean Action completed.
     */
    public function cancel()
    {
        $db = Database::getConnection();
        try {
            $sql = "DELETE FROM processing WHERE intProcessingID = ?";
            $query = $db->prepare($sql);
            $query->execute(array($this->intProcessingID));
            // This section of code, thanks to code example here:
            // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
            if ($query->errorCode() != 0) {
                throw new Exception("SQL Error: " . print_r($query->errorInfo(), true), 1);
            }
            return true;
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
    }


    /**
     * Add the collected generated data to the getSelf function
     *
     * @return The amassed data from this function
     */
    function getSelf()
    {
        $return = parent::getSelf();
        $return['arrTrackName'] = $this->getJson($this->strTrackName);
        $return['strTrackName'] = $this->preferredJson($this->strTrackName);
        $return['strTrackUrl'] = $this->preferredJson($this->strTrackUrl);
        $return['arrTrackUrl'] = $this->getJson($this->strTrackUrl);
        if (isset($this->intArtistID) and $this->intArtistID > 0 and ArtistBroker::getArtistByID($this->intArtistID) != false) {
            $objArtist = ArtistBroker::getArtistByID($this->intArtistID);
            $arrArtist = $objArtist->getSelf();
            $return['strArtistName'] = $arrArtist['strArtistName'];
            $return['arrArtistName'] = $arrArtist['arrArtistName'];
            $return['strArtistNameSounds'] = $arrArtist['strArtistNameSounds'];
            $return['strArtistUrl'] = $arrArtist['strArtistUrl'];
            $return['arrArtistUrl'] = $arrArtist['arrArtistUrl'];
        } else {
            $return['strArtistName'] = $this->preferredJson($this->strArtistName);
            $return['arrArtistName'] = $this->getJson($this->strArtistName);
            $return['strArtistNameSounds'] = $this->strArtistNameSounds;
            $return['strArtistUrl'] = $this->preferredJson($this->strArtistUrl);
            $return['arrArtistUrl'] = $this->getJson($this->strArtistUrl);
        }
        $return['enumTrackLicense'] = LicenseSelector::validateLicense($this->enumTrackLicense);
        $return['duplicateTracks'] = $this->duplicateTracks;
        return $return;
    }

    /**
     * This function amends data supplied by the API or HTML forms to provide additional data to process a track
     *
     * @return void
     */
    public function amendRecord()
    {
        $arrUri = UI::getUri();
        if (isset($arrUri['parameters']['_FILES'])) {
            $upload_dir = dirname(__FILE__) . '/../uploads/';
            foreach ($arrUri['parameters']['_FILES'] as $variable => $data) {
                foreach ($arrUri['parameters']['_FILES'][$variable]['error'] as $key => $error) {
                    if ($error === UPLOAD_ERR_OK) {
                        $tmp_name = $arrUri['parameters']['_FILES'][$variable]['tmp_name'][$key];
                        $file = GeneralFunctions::getTempFileName($upload_dir);
                        if ( ! move_uploaded_file($tmp_name, $file)) {
                            error_log("Unable to move the uploaded file to $file.");
                            die("Error handling uploaded file. Please speak to an administrator.");
                        }
                        $this->set_fileName($file);
                    }
                }
            }
        }
        if (isset($arrUri['parameters']['forceTrackNameDuplicate'])) {
            $this->set_forceTrackNameDuplicate($arrUri['parameters']['forceTrackNameDuplicate']);
        }
        if (isset($arrUri['parameters']['forceTrackUrlDuplicate'])) {
            $this->set_forceTrackUrlDuplicate($arrUri['parameters']['forceTrackUrlDuplicate']);
        }
        if (isset($arrUri['parameters']['forceMD5Duplicate'])) {
            $this->set_forceMD5Duplicate($arrUri['parameters']['forceMD5Duplicate']);
        }
        if (isset($arrUri['parameters']['strTrackName_preferred']) and $arrUri['parameters']['strTrackName_preferred'] != '') {
            $this->setpreferred_strTrackName($arrUri['parameters']['strTrackName_preferred']);
        }
        if (isset($arrUri['parameters']['strTrackName']) and $arrUri['parameters']['strTrackName'] != '') {
            $this->set_strTrackName($arrUri['parameters']['strTrackName']);
        }
        if (isset($arrUri['parameters']['del_strTrackName']) and $arrUri['parameters']['del_strTrackName'] != '') {
            $this->del_strTrackName($arrUri['parameters']['del_strTrackName']);
        }
        if (isset($arrUri['parameters']['strTrackNameSounds']) and $arrUri['parameters']['strTrackNameSounds'] != '') {
            $this->set_strTrackNameSounds($arrUri['parameters']['strTrackNameSounds']);
        }
        if (isset($arrUri['parameters']['strTrackUrl_preferred']) and $arrUri['parameters']['strTrackUrl_preferred'] != '') {
            $this->setpreferred_strTrackUrl($arrUri['parameters']['strTrackUrl_preferred']);
        }
        if (isset($arrUri['parameters']['strTrackUrl']) and $arrUri['parameters']['strTrackUrl'] != '') {
            $this->set_strTrackUrl($arrUri['parameters']['strTrackUrl']);
        }
        if (isset($arrUri['parameters']['del_strTrackUrl']) and $arrUri['parameters']['del_strTrackUrl'] != '') {
            $this->del_strTrackUrl($arrUri['parameters']['del_strTrackUrl']);
        }
        if (isset($arrUri['parameters']['action']) and $arrUri['parameters']['action'] == 'createartist') {
            if ($this->intArtistID == 0) {
                $artist = new NewArtistObject(
                    $this->strArtistName,
                    $this->strArtistNameSounds,
                    $this->strArtistUrl
                );
                $this->set_intArtistID($artist->get_intArtistID());
            }
        }
        if (isset($arrUri['parameters']['intArtistID']) and $arrUri['parameters']['intArtistID'] != '') {
            $this->set_intArtistID($arrUri['parameters']['intArtistID']);
        }
        if (isset($arrUri['parameters']['strArtistName_preferred']) and $arrUri['parameters']['strArtistName_preferred'] != '') {
            $this->setpreferred_strArtistName($arrUri['parameters']['strArtistName_preferred']);
        }
        if (isset($arrUri['parameters']['strArtistName']) and $arrUri['parameters']['strArtistName'] != '') {
            $this->set_strArtistName($arrUri['parameters']['strArtistName']);
        }
        if (isset($arrUri['parameters']['del_strArtistName']) and $arrUri['parameters']['del_strArtistName'] != '') {
            $this->del_strArtistName($arrUri['parameters']['del_strArtistName']);
        }
        if (isset($arrUri['parameters']['strArtistNameSounds']) and $arrUri['parameters']['strArtistNameSounds'] != '') {
            $this->set_strArtistNameSounds($arrUri['parameters']['strArtistNameSounds']);
        }
        if (isset($arrUri['parameters']['strArtistUrl_preferred']) and $arrUri['parameters']['strArtistUrl_preferred'] != '') {
            $this->setpreferred_strArtistUrl($arrUri['parameters']['strArtistUrl_preferred']);
        }
        if (isset($arrUri['parameters']['strArtistUrl']) and $arrUri['parameters']['strArtistUrl'] != '') {
            $this->set_strArtistUrl($arrUri['parameters']['strArtistUrl']);
        }
        if (isset($arrUri['parameters']['del_strArtistUrl']) and $arrUri['parameters']['del_strArtistUrl'] != '') {
            $this->del_strArtistUrl($arrUri['parameters']['del_strArtistUrl']);
        }
        if (isset($arrUri['parameters']['approved'])) {
            $this->set_isApproved($this->asBoolean($arrUri['parameters']['approved']));
        }
        if (isset($arrUri['parameters']['nsfw'])) {
            $this->set_isNSFW($arrUri['parameters']['nsfw']);
        }
        try {
            return $this->write();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * To pass an exception back, without throwing it... add it to this class and return that
     *
     * @param Exception $e
     *
     * @return void
     */
    function set_exception($e)
    {
        $this->e = $e;
    }

    /**
     * Return the exception pushed into the stack before
     *
     * @return Exception
     */
    function get_exception()
    {
        return $this->e;
    }

    /**
     * This function returns the processing ID of a track being prepared for submission
     *
     * @return integer Processing ID.
     */
    function get_intProcessingID()
    {
        return $this->intProcessingID;
    }

    /**
     * This function returns the track ID of the track which has been processed.
     *
     * @return integer TrackID.
     */
    function get_intTrackID()
    {
        return $this->intTrackID;
    }

    /**
     * This function returns the UserID of the track which has been submitted.
     *
     * @return integer UserID.
     */
    function get_intUserID()
    {
        return $this->intUserID;
    }

    /**
     * This function returns the ArtistID associated to the submitted track.
     *
     * @return integer ArtistID.
     */
    function get_intArtistID()
    {
        return $this->intArtistID;
    }

    /**
     * Set the Track Name
     *
     * @param string $strTrackName The name of the track
     *
     * @return void
     */
    function set_strTrackName($strTrackName = "")
    {
        if ( ! $this->inJson($this->strTrackName, $strTrackName)) {
            $this->strTrackName = $this->addJson($this->strTrackName, $strTrackName);
            $this->arrChanges[] = 'strTrackName';
        }
    }

    /**
     * Set the Preferred Track Name
     *
     * @param string $strTrackName The name of the track
     *
     * @return void
     */
    function setpreferred_strTrackName($strTrackName = "")
    {
        if ($this->preferredJson($this->strTrackName) != $strTrackName) {
            $this->strTrackName = $this->addJson($this->strTrackName, $strTrackName, true);
            $this->arrChanges[] = 'strTrackName';
        }
    }

    /**
     * Remove a value from the JSON array of Track Names and update the change queue
     *
     * @param string $strTrackName The string to remove
     *
     * @return void
     */
    function del_strTrackName($strTrackName = "")
    {
        $this->strTrackName = $this->delJson($this->strTrackName, $strTrackName);
        $this->arrChanges[] = 'strTrackName';
    }

    /**
     * Set the spoken version of the Track Name
     *
     * @param string $strTrackNameSounds The festival pronounciation of this track name
     *
     * @return void
     */
    function set_strTrackNameSounds($strTrackNameSounds = "")
    {
        if ($this->strTrackNameSounds != $strTrackNameSounds) {
            $this->strTrackNameSounds = $strTrackNameSounds;
            $this->arrChanges[] = 'strTrackNameSounds';
        }
    }

    /**
     * Set the URL to find more details about the track
     *
     * @param string $strTrackUrl The place to find out more about the track
     *
     * @return void
     */
    function set_strTrackUrl($strTrackUrl = "")
    {
        if ( ! $this->inJson($this->strTrackUrl, $strTrackUrl)) {
            $this->strTrackUrl = $this->addJson($this->strTrackUrl, $strTrackUrl);
            $this->arrChanges[] = 'strTrackUrl';
        }
    }

    /**
     * Set the preferred URL to find more details about the track
     *
     * @param string $strTrackUrl The preferred place to find out more about the track
     *
     * @return void
     */
    function setpreferred_strTrackUrl($strTrackUrl = '')
    {
        if ($this->preferredJson($this->strTrackUrl) != $strTrackUrl) {
            $this->strTrackUrl = $this->addJson($this->strTrackUrl, $strTrackUrl, true);
            $this->arrChanges[] = 'strTrackUrl';
        }
    }

    /**
     * Remove a value from the JSON array of URLs and update the change queue
     *
     * @param string $strTrackUrl The string to remove
     *
     * @return void
     */
    function del_strTrackUrl($strTrackUrl = "")
    {
        $this->strTrackUrl = $this->delJson($this->strTrackUrl, $strTrackUrl);
        $this->arrChanges[] = 'strTrackUrl';
    }

    /**
     * Set the track's license in the class
     *
     * @param string $enumTrackLicense The license for the use of the track
     *
     * @return void
     */
    protected function set_enumTrackLicense($enumTrackLicense = "")
    {
        $strLicense = LicenseSelector::validateLicense($enumTrackLicense);
        if ($this->enumTrackLicense != $strLicense) {
            $this->enumTrackLicense = $strLicense;
            $this->arrChanges['enumTrackLicense'] = true;
        }
    }

    /**
     * Set the existing ArtistID information URL in the class
     *
     * @param integer $intArtistID The existing ArtistID
     *
     * @return void
     */
    protected function set_intArtistID($intArtistID = 0)
    {
        if ($this->intArtistID != $intArtistID and ArtistBroker::getArtistByID($intArtistID) != false) {
            $this->intArtistID = $intArtistID;
            $objArtist = ArtistBroker::getArtistByID($intArtistID);
            foreach ($this->deobjectify_array(json_decode($this->strArtistName)) as $key=>$value) {
                if ($key != 'preferred') {
                    $objArtist->set_strArtistName($value);
                } else {
                    $objArtist->setpreferred_strArtistName($value);
                }
            }
            foreach ($this->deobjectify_array(json_decode($this->strArtistUrl)) as $key=>$value) {
                if ($key != 'preferred') {
                    $objArtist->set_strArtistUrl($value);
                } else {
                    $objArtist->setpreferred_strArtistUrl($value);
                }
            }
            $objArtist->set_strArtistNameSounds($this->strArtistNameSounds);
            $objArtist->write();
            $this->arrChanges['intArtistID'] = true;
        }
    }

    /**
     * Set or amend the value of the strArtistName
     *
     * @param string $strArtistName The new artist name
     *
     * @return void
     */
    function set_strArtistName($strArtistName = "")
    {
        if ( ! $this->inJson($this->strArtistName, $strArtistName)) {
            $this->strArtistName = $this->addJson($this->strArtistName, $strArtistName);
            $this->arrChanges['strArtistName'] = true;
        }
    }

    /**
     * Set the preferred version of the artist's name
     *
     * @param string $strArtistName The preferred version of the Artist's name
     *
     * @return void
     */
    function setpreferred_strArtistName($strArtistName = '')
    {
        if ($this->preferredJson($this->strArtistName) != $strArtistName) {
            $this->strArtistName = $this->addJson($this->strArtistName, $strArtistName, true);
            $this->arrChanges['strArtistName'] = true;
        }
    }

    /**
     * Remove a value from the JSON array of Artist Names and update the change queue
     *
     * @param string $strArtistName The string to remove
     *
     * @return void
     */
    function del_strArtistName($strArtistName = "")
    {
        $this->strArtistName = $this->delJson($this->strArtistName, $strArtistName);
        $this->arrChanges['strArtistName'] = true;
    }

    /**
     * Set or amend the value of the strArtistNameSounds
     *
     * @param string $strArtistNameSounds The new way to pronounce the artist name
     *
     * @return void
     */
    function set_strArtistNameSounds($strArtistNameSounds = "")
    {
        if ($this->strArtistNameSounds != $strArtistNameSounds) {
            $this->strArtistNameSounds = $strArtistNameSounds;
            $this->arrChanges['strArtistNameSounds'] = true;
        }
    }

    /**
     * Set or amend the value of the strArtistUrl
     *
     * @param string $strArtistUrl The new URL for the artist
     *
     * @return void
     */
    function set_strArtistUrl($strArtistUrl = "")
    {
        if ( ! $this->inJson($this->strArtistUrl, $strArtistUrl)) {
            $this->strArtistUrl = $this->addJson($this->strArtistUrl, $strArtistUrl);
            $this->arrChanges['strArtistUrl'] = true;
        }
    }

    /**
     * Set the preferred URL to find more details about the Artist
     *
     * @param string $strArtistUrl The preferred place to find out more about the Artist
     *
     * @return void
     */
    function setpreferred_strArtistUrl($strArtistUrl = '')
    {
        if ($this->preferredJson($this->strArtistUrl) != $strArtistUrl) {
            $this->strArtistUrl = $this->addJson($this->strArtistUrl, $strArtistUrl, true);
            $this->arrChanges['strArtistUrl'] = true;
        }
    }

    /**
     * Remove a value from the JSON array of URLs and update the change queue
     *
     * @param string $strArtistUrl The string to remove
     *
     * @return void
     */
    function del_strArtistUrl($strArtistUrl = "")
    {
        $this->strArtistUrl = $this->delJson($this->strArtistUrl, $strArtistUrl);
        $this->arrChanges['strArtistUrl'] = true;
    }

    /**
     * Set the worksafe status in the class
     *
     * @param integer $isNSFW Is the track work/family safe?
     *
     * @return void
     */
    protected function set_isNSFW($isNSFW = 1)
    {
        if ($this->isNSFW != $isNSFW) {
            $this->isNSFW = $isNSFW;
            $this->arrChanges['isNSFW'] = true;
        }
    }

    /**
     * Set the download location of the track. Not publically available.
     *
     * @param string $fileUrl The download location for this track.
     *
     * @return void
     */
    protected function set_fileUrl($fileUrl = "")
    {
        if ($this->fileUrl != $fileUrl) {
            $this->fileUrl = $fileUrl;
            $this->arrChanges['fileUrl'] = true;
        }
    }

    /**
     * Set the internal location of the track. Not publically available.
     *
     * @param string $fileName The post-download location for this track.
     *
     * @return void
     */
    protected function set_fileName($fileName = "")
    {
        if ($this->fileName != $fileName) {
            $this->fileName = $fileName;
            $this->arrChanges['fileName'] = true;
        }
    }

    /**
     * Set the userID of the person uploading the track
     *
     * @return void
     */
    protected function set_intUserID()
    {
        if ($this->intUserID != UserBroker::getUser()->get_intUserID()) {
            $this->intUserID = UserBroker::getUser()->get_intUserID();
            $this->arrChanges['intUserID'] = true;
        }
    }

    /**
     * This function sets the MD5 hash of the file.
     *
     * @param md5 $md5hash The hash calculated on download
     *
     * @return void
     */
    protected function set_fileMD5($md5hash)
    {
        if ($this->fileMD5 != $md5hash) {
            $this->fileMD5 = $md5hash;
            $this->arrChanges['fileMD5'] = true;
        }
    }

    /**
     * Force the processing value to ignore duplicate MD5 Sums
     *
     * @param boolean $boolean True or Force
     *
     * @return void
     */
    function set_forceMD5Duplicate($boolean)
    {
        if ($this->forceMD5Duplicate != $boolean) {
            $this->forceMD5Duplicate = $boolean;
            $this->arrChanges['forceMD5Duplicate'] = true;
        }
    }

    /**
    * Force the processing value to ignore duplicate track names
    *
    * @param boolean $boolean True or Force
    *
    * @return void
    */
    function set_forceTrackNameDuplicate($boolean)
    {
        if ($this->forceTrackNameDuplicate != $boolean) {
            $this->forceTrackNameDuplicate = $boolean;
            $this->arrChanges['forceTrackNameDuplicate'] = true;
        }
    }

    /**
    * Force the processing value to ignore duplicate track URLs
    *
    * @param boolean $boolean True or Force
    *
    * @return void
    */
    function set_forceTrackUrlDuplicate($boolean)
    {
        if ($this->forceTrackUrlDuplicate != $boolean) {
            $this->forceTrackUrlDuplicate = $boolean;
            $this->arrChanges['forceTrackUrlDuplicate'] = true;
        }
    }

    /**
     * This function creates an entry in the "processing" table, unless there is sufficient detail to process it.
     *
     * @return array(integer, boolean) The Track or ProcessingID and the state.
     */
    function create_pull_entry()
    {
        try {
            $this->is_valid_cchits_submission();
            return array($this->approveProcessing()=>true);
        } catch (Exception $e) {
            $this->set_intUserID();
            $this->set_exception($e);
            $this->create();
            return array($this->intProcessingID=>false);
        }
    }

    /**
     * Override the Write action by checking it against the validity check
     *
     * @return boolean|Exception Did this action result in a completed submission?
     */
    function write()
    {
        try {
            $this->is_valid_cchits_submission();
            return $this->approveProcessing();
        } catch (Exception $e) {
            parent::write();
            throw $e;
        }
    }

    /**
     * Continue processing the collected data
     *
     * @return const Internal response codes
     */
    function approveProcessing()
    {
        $track = new NewTrackObject(
            $this->intArtistID,
            $this->strTrackName,
            $this->strTrackNameSounds,
            $this->strTrackUrl,
            $this->enumTrackLicense,
            $this->isNSFW,
            $this->fileName
        );
        if ($this->fileUrl != '' and $track != false) {
            unlink($this->fileName);
        }
        if ($track != false) {
            $this->cancel();
            $this->intTrackID = $track->get_intTrackID();
            return $track->get_intTrackID();
        }
    }

    /**
     * Function to confirm all required data is here
     *
     * @return const Internal response codes
     */
    function is_valid_cchits_submission()
    {
        if (!isset($this->strTrackName) or '' == trim($this->strTrackName)) {
            throw new RemoteSource_NoTrackName();
        } else {
            $duplicateTracks = TrackBroker::getTrackByExactName($this->preferredJson($this->strTrackName));
            if ($duplicateTracks and $this->forceTrackNameDuplicate != true) {
                foreach($duplicateTracks as $duplicateTrack) {
                    $this->duplicateTracks[] = $duplicateTrack->getSelf();
                }
                throw new RemoteSource_DuplicateTrackName();
            }
        }
        if (!isset($this->strTrackUrl) or '' == trim($this->strTrackUrl)) {
            throw new RemoteSource_NoTrackUrl();
        } else {
            $duplicateTracks = TrackBroker::getTrackByExactUrl($this->preferredJson($this->strTrackUrl));
            if ($duplicateTracks and $this->forceTrackUrlDuplicate != true) {
                foreach($duplicateTracks as $duplicateTrack) {
                    $this->duplicateTracks[] = $duplicateTrack->getSelf();
                }
                throw new RemoteSource_DuplicateTrackUrl();
            }
        }
        if (!isset($this->enumTrackLicense) or '' == trim($this->enumTrackLicense) or 'None Selected' == LicenseSelector::validateLicense($this->enumTrackLicense)) {
            throw new RemoteSource_NoTrackLicense();
        }
        if (isset($this->intArtistID) and $this->intArtistID != 0 and false == ArtistBroker::getArtistByID($this->intArtistID)) {
            throw new RemoteSource_NoArtist();
        } elseif ($this->intArtistID == 0) {
            throw new RemoteSource_NoArtist();
        }
        if (!isset($this->isNSFW) or trim($this->isNSFW)>1 or trim($this->isNSFW)<0) {
            throw new RemoteSource_NoNSFWFlag();
        }
        if (isset($this->fileUrl) and "" != $this->fileUrl) {
            if (isset($this->fileName) and false == $this->fileName) {
                $get = $this->curl_get($this->fileUrl);
                if ($get[1]['http_code'] == 200) {
                    if (GeneralFunctions::getFileFormat($get[0]) != '') {
                        $this->set_fileName($get[0]);
                    }
                    $md5 = md5_file($get[0]);
                    $this->set_fileMD5($md5);
                    parent::write();
                    $duplicateTracks = TrackBroker::getTrackByMD5($md5);
                    if ($duplicateTracks and $this->forceMD5Duplicate != true) {
                        foreach($duplicateTracks as $duplicateTrack) {
                            $this->duplicateTracks[] = $duplicateTrack->getSelf();
                        }
                        throw new RemoteSource_DuplicateMD5();
                    }
                } else {
                    throw new RemoteSource_NoFileDl();
                }
            }
            if (!isset($this->fileName) or '' == trim($this->fileName)) {
                throw new RemoteSource_NoFileName();
            }
            if (isset($this->fileName) and !file_exists(trim($this->fileName))) {
                throw new RemoteSource_NoFileExist();
            }
        }
        return true;
    }

    /**
     * Get url content and response headers (given a url, follows all redirections on it and returned content and response headers of final url)
     *
     * @param string  $url             The URL to retrieve
     * @param integer $as_file         Return a string (0) or a filename (1)
     * @param integer $javascript_loop Follow Javascript redirection
     * @param integer $timeout         Timeout in microseconds
     * @param integer $max_loop        Number of redirections to follow
     *
     * @return  boolean|array Boolean is state of response, while the array contains ([0] => data and [1] => response headers)
     *
     * @link http://www.php.net/manual/en/ref.curl.php#93163
     */
    protected function curl_get($url, $as_file = 1, $javascript_loop = 0, $timeout = 10000, $max_loop = 10)
    {
        $url = str_replace("&amp;", "&", urldecode(trim($url)));
        $cookie = GeneralFunctions::getTempFileName(dirname(__FILE__) . '/../cookie/');
        if ($cookie == false) {
            error_log("Wasn't able to create a temporary file for the cookie jar");
            return false;
        }
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);

        if ($as_file == 1) {
            $tempname = GeneralFunctions::getTempFileName(dirname(__FILE__) . '/../upload/');
            if ($tempname == false) {
                error_log("Wasn't able to create a temporary file for uploading");
                unlink($cookie);
                return false;
            }
            $out = fopen($tempname, 'wb');
            curl_setopt($ch, CURLOPT_FILE, $out);
        }

        $content = curl_exec($ch);
        $response = curl_getinfo($ch);
        if (curl_errno($ch)) {
            error_log("Unable to download file: " . curl_error($ch));
            $error = 1;
        }
        curl_close($ch);
        if ($as_file == 1) {
            fclose($out);
        }

        if (isset($error)) {
            if ($as_file == 1) {
                unlink($tempname);
            }
            unlink($cookie);
            return false;
        }

        if ($response['http_code'] == 301 or $response['http_code'] == 302) {
            if ($headers = get_headers($response['url'])) {
                foreach ($headers as $value) {
                    if (substr(strtolower($value), 0, 9) == "location:") {
                        if ($as_file == 1) {
                            unlink($tempname);
                        }
                        unlink($cookie);
                        return $this->curl_get(trim(substr($value, 9, strlen($value))), $as_file);
                    }
                }
            }
        }

        if ($as_file == 0
            and (preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $content)
            or preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $content))
            and $javascript_loop < $max_loop
        ) {
            unlink($cookie);
            return $this->curl_get($value[1], 0, $javascript_loop+1, $max_loop);
        } else {
            if ($as_file == 1) {
                unlink($cookie);
                return array($tempname, $response);
            } else {
                unlink($cookie);
                return array($content, $response);
            }
        }
    }
}

/**
 * This class handles custom exceptions
 *
 * @category Default
 * @package  Exceptions
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class RemoteSource_NoTrackName extends CustomException
{
    protected $message = "This track has no name.";
    protected $code = 255;
}

/**
 * This class handles custom exceptions
 *
 * @category Default
 * @package  Exceptions
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class RemoteSource_NoTrackUrl extends CustomException
{
    protected $message = "This track has no URL.";
    protected $code = 253;
}

/**
 * This class handles custom exceptions
 *
 * @category Default
 * @package  Exceptions
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class RemoteSource_NoTrackLicense extends CustomException
{
    protected $message = "There is no valid license associated to this track.";
    protected $code = 253;
}

/**
 * This class handles custom exceptions
 *
 * @category Default
 * @package  Exceptions
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class RemoteSource_NoArtist extends CustomException
{
    protected $message = "The Artist is not set or created.";
    protected $code = 252;
}

/**
 * This class handles custom exceptions
 *
 * @category Default
 * @package  Exceptions
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class RemoteSource_NoNSFWFlag extends CustomException
{
    protected $message = "There is no NSFW flag associated with this track.";
    protected $code = 250;
}

/**
 * This class handles custom exceptions
 *
 * @category Default
 * @package  Exceptions
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class RemoteSource_NoFileDl extends CustomException
{
    protected $message = "Couldn't download this file.";
    protected $code = 249;
}

/**
 * This class handles custom exceptions
 *
 * @category Default
 * @package  Exceptions
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class RemoteSource_NoFileName extends CustomException
{
    protected $message = "You didn't specify a filename.";
    protected $code = 248;
}

/**
 * This class handles custom exceptions
 *
 * @category Default
 * @package  Exceptions
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class RemoteSource_NoFileExist extends CustomException
{
    protected $message = "You specified a filename which doesn't exist.";
    protected $code = 247;
}

/**
 * This class handles custom exceptions
 *
 * @category Default
 * @package  Exceptions
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class RemoteSource_InvalidLicense extends CustomException
{
    protected $message = "This license is not appropriate for this site.";
    protected $code = 246;
}

/**
 * This class handles custom exceptions
 *
 * @category Default
 * @package  Exceptions
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class RemoteSource_InvalidSource extends CustomException
{
    protected $message = "That source is not appropriate for this class.";
    protected $code = 245;
}

/**
 * This class handles custom exceptions
 *
 * @category Default
 * @package  Exceptions
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class RemoteSource_InvalidAPICode extends CustomException
{
    protected $message = "The supplied API code isn't suitable for that resource.";
    protected $code = 244;
}

/**
 * This class handles custom exceptions
 *
 * @category Default
 * @package  Exceptions
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class RemoteSource_DuplicateTrackName extends CustomException
{
    protected $message = "The supplied track name already exists - please verify this track isn't a duplicate, and then set the ForceDuplicateTrackName flag for this track.";
    protected $code = 243;
}
/**
* This class handles custom exceptions
*
* @category Default
* @package  Exceptions
* @author   Jon Spriggs <jon@sprig.gs>
* @license  http://www.gnu.org/licenses/agpl.html AGPLv3
* @link     http://cchits.net Actual web service
* @link     http://code.cchits.net Developers Web Site
* @link     http://gitorious.net/cchits-net Version Control Service
*/
class RemoteSource_DuplicateTrackUrl extends CustomException
{
    protected $message = "The supplied track URL already exists - please verify this track isn't a duplicate, and then set the ForceDuplicateTrackUrl flag for this track.";
    protected $code = 242;
}

/**
* This class handles custom exceptions
*
* @category Default
* @package  Exceptions
* @author   Jon Spriggs <jon@sprig.gs>
* @license  http://www.gnu.org/licenses/agpl.html AGPLv3
* @link     http://cchits.net Actual web service
* @link     http://code.cchits.net Developers Web Site
* @link     http://gitorious.net/cchits-net Version Control Service
*/
class RemoteSource_DuplicateMD5 extends CustomException
{
    protected $message = "A track with this exact MD5 sum already exists - please verify this track hasn't already been submitted already, and then set the ForceDuplicateMD5 flag for this track.";
    protected $code = 241;
}