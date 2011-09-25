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
    	'intUserID'=>true
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

    /**
     * This function amends data supplied by the API or HTML forms to provide additional data to process a track
     *
     * @return void
     */
    public function amendRecord()
    {
        $arrUri = UI::getUri();
        if (isset($arrUri['parameters']['strTrackName'])) {
            $this->addJson($this->strTrackName, $arrUri['parameters']['strTrackName'], true);
        }
        if (isset($arrUri['parameters']['strTrackNameSounds'])) {
            $this->strTrackNameSounds = $arrUri['parameters']['strTrackNameSounds'];
        }
        if (isset($arrUri['parameters']['strTrackUrl'])) {
            $this->addJson($this->strTrackUrl, $arrUri['parameters']['strTrackUrl'], true);
        }
        if (isset($arrUri['parameters']['enumTrackLicense'])) {
            $this->enumTrackLicense = $arrUri['parameters']['enumTrackLicense'];
        }
        if (isset($arrUri['parameters']['strArtistName'])) {
            $this->addJson($this->strArtistName, $arrUri['parameters']['strArtistName'], true);
        }
        if (isset($arrUri['parameters']['strArtistNameSounds'])) {
            $this->strArtistNameSounds = $arrUri['parameters']['strArtistNameSounds'];
        }
        if (isset($arrUri['parameters']['strArtistUrl'])) {
            $this->addJson($this->strArtistUrl, $arrUri['parameters']['strArtistUrl'], true);
        }
        if (isset($arrUri['parameters']['intArtistID'])) {
            $this->intArtistID = $arrUri['parameters']['intArtistID'];
        }
        if (isset($arrUri['parameters']['isNSFW'])) {
            $this->isNSFW = $arrUri['parameters']['isNSFW'];
        }
        $this->write();
    }

    /**
     * Set the track name in the class
     *
     * @param string $strTrackName Track Name
     *
     * @return void
     */
    protected function set_strTrackName($strTrackName = "")
    {
        if ($this->strTrackName != $strTrackName) {
            $this->strTrackName = $strTrackName;
            $arrChanges['strTrackName'] = true;
        }
    }

    /**
     * Set the sound of the track name in the class
     *
     * @param string $strTrackNameSounds Sound of the Track Name
     *
     * @return void
     */
    protected function set_strTrackNameSounds($strTrackNameSounds = "")
    {
        if ($this->strTrackNameSounds != $strTrackNameSounds) {
            $this->strTrackNameSounds = $strTrackNameSounds;
            $arrChanges['strTrackNameSounds'] = true;
        }
    }

    /**
     * Set the track's information URL in the class
     *
     * @param string $strTrackUrl The external location of the Track information
     *
     * @return void
     */
    protected function set_strTrackUrl($strTrackUrl = "")
    {
        if ($this->strTrackUrl != $strTrackUrl) {
            $this->strTrackUrl = $strTrackUrl;
            $arrChanges['strTrackUrl'] = true;
        }
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
        if ($this->enumTrackLicense != $enumTrackLicense) {
            $this->enumTrackLicense = $enumTrackLicense;
            $arrChanges['enumTrackLicense'] = true;
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
            $arrChanges['intArtistID'] = true;
        }
    }

    /**
     * Set the Artist name in the class
     *
     * @param string $strArtistName The name of the artist
     *
     * @return void
     */
    protected function set_strArtistName($strArtistName = "")
    {
        if ($this->strArtistName != $strArtistName) {
            $this->strArtistName = $strArtistName;
            $arrChanges['strArtistName'] = true;
        }
    }

    /**
     * Set the sound of the Artist name in the class
     *
     * @param string $strArtistNameSounds The sound of the artist's name
     *
     * @return void
     */
    protected function set_strArtistNameSounds($strArtistNameSounds = "")
    {
        if ($this->strArtistNameSounds != $strArtistNameSounds) {
            $this->strArtistNameSounds = $strArtistNameSounds;
            $arrChanges['strArtistNameSounds'] = true;
        }
    }

    /**
     * Set the artist's URL in the class
     *
     * @param string $strArtistUrl The external location of the Artist's information
     *
     * @return void
     */
    protected function set_strArtistUrl($strArtistUrl = "")
    {
        if ($this->strArtistUrl != $strArtistUrl) {
            $this->strArtistUrl = $strArtistUrl;
            $arrChanges['strArtistUrl'] = true;
        }
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
            $arrChanges['isNSFW'] = true;
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
            $arrChanges['fileUrl'] = true;
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
            $arrChanges['fileName'] = true;
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
            $arrChanges['intUserID'] = true;
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
        } catch (exception $e) {
            $this->set_intUserID();
            $this->create();
            return array($this->intProcessingID=>false);
        }
    }

    /**
     * Override the Write action by checking it against the validity check
     *
     * @return boolean Did this action result in a completed submission?
     */
    function write()
    {
        try {
            $this->is_valid_cchits_submission();
            $this->approveProcessing();
            return true;
        } catch (exception $e) {
            parent::write();
            return false;
        }
    }

    /**
     * Continue processing the collected data
     *
     * @return const Internal response codes
     */
    function approveProcessing()
    {
        if ($this->intArtistID == 0) {
            $this->intArtistID = new NewArtistObject(
                $this->strArtistName,
                $this->strArtistNameSounds,
                $this->strArtistUrl
            );
        }
        if ($this->fileUrl != '') {
            $get = $this->curl_get($url);
            if ($get[1]['http_code'] == 200) {
                $this->fileName = $get[0];
            } else {
                unlink($get[0]);
                throw new RemoteSource_NoFileDl();
            }
        } elseif ($this->fileName == '') {
            throw new RemoteSource_NoFileName();
        } else {
            if ( ! file_exists($this->fileName)) {
                throw new RemoteSource_NoFileExist();
            }
        }
        $this->intTrackID = new NewTrackObject(
            $this->intArtistID,
            $this->strTrackName,
            $this->strTrackNameSounds,
            $this->strTrackUrl,
            $this->enumTrackLicense,
            $this->isNSFW,
            $this->fileName
        );
        if ($this->fileUrl != '') {
            unlink($this->fileName);
        }
        return $this->intTrackID;
    }

    /**
     * Function to confirm all required data is here
     *
     * @return const Internal response codes
     */
    protected function is_valid_cchits_submission()
    {
        if (!isset($this->strTrackName) or '' == trim($this->strTrackName)) {
            throw new RemoteSource_NoTrackName();
        }
        if (!isset($this->strTrackUrl) or '' == trim($this->strTrackUrl)) {
            throw new RemoteSource_NoTrackUrl();
        }
        if (!isset($this->enumTrackLicense) or '' == trim($this->enumTrackLicense)) {
            throw new RemoteSource_NoTrackLicense();
        }
        if (isset($this->intArtistID) and false == ArtistBroker::getArtistByID($this->intArtistID)) {
            throw new RemoteSource_NoArtistName();
        } else {
            if (!isset($this->intArtistID) and (!isset($this->strArtistName) or '' == trim($this->strArtistName))) {
                throw new RemoteSource_NoArtistName();
            }
            if (!isset($this->intArtistID) and (!isset($this->strArtistUrl) or '' == trim($this->strArtistUrl))) {
                throw new RemoteSource_NoArtistUrl();
            }
        }
        if (!isset($this->isNSFW) or trim($this->isNSFW)>1 or trim($this->isNSFW)<0) {
            throw new RemoteSource_NoNSFWFlag();
        }
        if (isset($this->fileUrl) and "" != $this->fileUrl) {
            if (isset($this->fileName) and false == $this->fileName) {
                throw new RemoteSource_NoFileDl();
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
        $cookie = tempnam(sys_get_temp_dir(), "CURLCOOKIE_");
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
            $tmpfname = tempnam(sys_get_temp_dir(), "UP_");
            $out = fopen($tmpfname, 'wb');
            if ($out == FALSE) {
                return false;
            }
            curl_setopt($ch, CURLOPT_FILE, $out);
        }

        $content = curl_exec($ch);
        $response = curl_getinfo($ch);
        if (curl_errno($ch)) {
            $error = 1;
        }
        curl_close($ch);
        if ($as_file == 1) {
            fclose($out);
        }

        if (isset($error)) {
            return false;
        }

        if ($response['http_code'] == 301 or $response['http_code'] == 302) {
            if ($headers = get_headers($response['url'])) {
                foreach ($headers as $value) {
                    if (substr(strtolower($value), 0, 9) == "location:") {
                        return get_url(trim(substr($value, 9, strlen($value))), $as_file);
                    }
                }
            }
        }

        if ($as_file == 0
            and (preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $content)
            or preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $content))
            and $javascript_loop < $max_loop
        ) {
            return get_url($value[1], 0, $javascript_loop+1, $max_loop);
        } else {
            if ($as_file == 1) {
                return array($tmpfname, $response);
            } else {
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
class RemoteSource_NoArtistName extends CustomException
{
    protected $message = "This Artist has no name.";
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
class RemoteSource_NoArtistUrl extends CustomException
{
    protected $message = "This Artist has no Url.";
    protected $code = 251;
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