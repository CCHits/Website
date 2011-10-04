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
 * This class knows every way to get an incomplete Remote Source entry
 *
 * @category Default
 * @package  Brokers
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */

class RemoteSourcesBroker
{
    /**
     * This function finds a Remote Source by it's ID
     *
     * @param integer $intSourceID SourceID to search for
     *
     * @return object|false RemoteSources or false if not existing
     */
    public function getRemoteSourceByID($intSourceID = 0)
    {
        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM processing WHERE intProcessingID = ? LIMIT 1";
            $query = $db->prepare($sql);
            $query->execute(array($intSourceID));
            return $query->fetchObject('RemoteSources');
        } catch(Exception $e) {
            error_log("SQL Died: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get a list of all non-completed tracks for processing
     *
     * @return false|array False if there's an error or no entries, array of RemoteSources if there is data to process.
     */
    public function getRemoteSourcesByUserID()
    {
        $db = Database::getConnection();
        try {
            $sql = "SELECT * FROM processing WHERE intUserID = ? LIMIT 1";
            $query = $db->prepare($sql);
            $query->execute(array(UserBroker::getUser()->get_intUserID()));
            $item = $query->fetchObject('RemoteSources');
            if ($item == false) {
                return false;
            } else {
                $return[] = $item;
                while ($item = $query->fetchObject('RemoteSources')) {
                    $return[] = $item;
                }
                return $return;
            }
        } catch(Exception $e) {
            error_log($e);
            return false;
        }
    }

    /**
     * A router for the newTrack requests used by the API and HTML classes
     *
     * @param String $url The track to be used by the site
     *
     * @return Array|Integer|False One of the following, an array of the
     * TrackID and the boolean value true if there was enough data to create a
     * new track object from it, an array of the ProcessingID and the boolean
     * value false if there wasn't enough data to create a new track object
     * from the supplied data, an integer if there was a specific error with
     * the supplied data (to be used as an HTTP Error Status), or false if
     * no URL was supplied.
     */
    public function newTrackRouter($url = '')
    {
        if ($url != '') {
            if (preg_match('/^http[s]*:\/\/([^\/]+)/', $url, $matches) > 0) {
                switch (strtolower($matches[1])) {
                case 'alonetone.com':
                case 'www.alonetone.com':
                    return new RemoteSourcesAlonetone($url);
                case 'ccmixter.org':
                case 'www.ccmixter.org':
                    return new RemoteSourcesCCMixter($url);
                case 'freemusicarchive.org':
                case 'www.freemusicarchive.org':
                    return new RemoteSourcesFMA($url);
                case 'jamendo.com':
                case 'www.jamendo.com':
                    return new RemoteSourcesJamendo($url);
                case 'macjams.com':
                case 'www.macjams.com':
                    return new RemoteSourcesMacjams($url);
                case 'riffworld.com':
                case 'www.riffworld.com':
                    return new RemoteSourcesRiffworld($url);
                case 'sectionz.com':
                case 'www.sectionz.com':
                    return new RemoteSourcesSectionz($url);
                case 'soundcloud.com':
                case 'www.soundcloud.com':
                    return new RemoteSourcesSoundcloud($url);
                case 'sutros.com':
                case 'www.sutros.com':
                    return new RemoteSourcesSutros($url);
                case 'vimeo.com':
                case 'www.vimeo.com':
                    return new RemoteSourcesVimeo($url);
                }
            } else {
                $remoteSource = new RemoteSources();
                $remoteSource->set_strTrackUrl($url);
                return $remoteSource->create_pull_entry();
            }
        } else {
            $arrUri = UI::getUri();
            if (isset($arrUri['parameters']['_FILES'])) {
                $upload_dir = dirname(__FILE__) . '/../uploads/';
                foreach ($arrUri['parameters']['_FILES'] as $variable => $data) {
                    foreach ($arrUri['parameters']['_FILES'][$variable]['error'] as $key => $error) {
                        if ($error === UPLOAD_ERR_OK) {
                            $tmp_name = $arrUri['parameters']['_FILES'][$variable]['tmp_name'][$key];
                            $file = GenericObject::getTempFileName($upload_dir);
                            if ( ! move_uploaded_file($tmp_name, $file)) {
                                error_log("Unable to move the uploaded file to $file.");
                                die("Error handling uploaded file. Please speak to an administrator.");
                            }
                        }
                    }
                }
                $remoteSource = new RemoteSourcesFile($file);
                return false;
            } else {
                return false;
            }
        }
    }
}
