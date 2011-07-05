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
    protected $arrDBItems = array('strTrackName'=>true, 'strTrackNameSounds'=>true, 'strTrackUrl'=>true, 'enumTrackLicense'=>true, 'intArtistID'=>true, 'strArtistName'=>true, 'strArtistNameSounds'=>true, 'strArtistUrl'=>true, 'isNSFW'=>true, 'fileUrl'=>true, 'fileName'=>true, 'intUserID'=>true);
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

    const NOTRACKNAME = -255;
    const NOTRACKURL = -254;
    const NOTRACKLIC = -253;
    const NOARTISTNAME = -252;
    const NOARTISTURL = -251;
    const NONSFWFLAG = -250;
    const NOFILEDL = -249;
    const NOFILENAME = -248;
    const NOFILEEXIST = -247;
    const INVALIDLIC = -246;
    const INVALIDSRC = -245;
    const INVALIDAPICODE = -244;
    const INVALIDCODE = -243;
    const VALID = 0;

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
        if ($this->intArtistID != $intArtistID) {
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
     * @param integer $intUserID The userID uploading the track
     *
     * @return void
     */
    protected function set_intUserID($intUserID = 0)
    {
        if ($this->intUserID != $intUserID) {
            $this->intUserID = $intUserID;
            $arrChanges['intUserID'] = true;
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
            $this->intArtistID = addArtist(
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
                return $this->NOFILEDL;
            }
        } elseif ($this->fileName == '') {
            return $this->NOFILENAME;
        } else {
            if ( ! file_exists($this->fileName)) {
                return $this->NOFILEEXIST;
            }
        }
        $this->intTrackID = addTrack(
            $this->intArtistID, 
            $this->strTrackName, 
            $this->strTrackNameSounds, 
            $this->strTrackUrl, 
            $this->enumTrackLicense, 
            $this->isNSFW, 
            $this->intUserID, 
            $this->fileName
        );
        if ($this->fileUrl != '') {
            unlink($this->fileName);
        }
        return $this->VALID;
    }

    /**
     * Function to confirm all required data is here
     *
     * @return const Internal response codes
     */
    protected function is_valid_cchits_submission()
    {
        if (!isset($this->strTrackName) or '' == trim($this->strTrackName)) {
            return $this->NOTRACKNAME;
        }
        if (!isset($this->strTrackUrl) or '' == trim($this->strTrackUrl)) {
            return $this->NOTRACKURL;
        }
        if (!isset($this->enumTrackLicense) or '' == trim($this->enumTrackLicense)) {
            return $this->NOTRACKLIC;
        }
        if (!isset($this->strArtistName) or '' == trim($this->strArtistName)) {
            return $this->NOARTISTNAME;
        }
        if (!isset($this->strArtistUrl) or '' == trim($this->strArtistUrl)) {
            return $this->NOARTISTURL;
        }
        if (!isset($this->isNSFW) or trim($this->isNSFW)>1 or trim($this->isNSFW)<0) {
            return $this->NONSFWFLAG;
        }
        if (isset($this->fileUrl) and "" != $this->fileUrl) {
            if (isset($this->fileName) and false == $this->fileName) {
                return $this->NOFILEDL;
            }
            if (!isset($this->fileName) or '' == trim($this->fileName)) {
                return $this->NOFILENAME;
            }
            if (isset($this->fileName) and !file_exists(trim($this->fileName))) {
                return $this->NOFILEEXIST;
            }
        }
        return $this->VALID;
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

