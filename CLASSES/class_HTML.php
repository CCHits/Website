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
 * This class handles all HTML requests
 *
 * @category Default
 * @package  UI
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */
class HTML
{
    protected $result = array();
    protected $response_code = 200;
    protected $format = 'html';
    protected $arrUri = array();
    protected $extLib = null;

    /**
     * The function which handles the routing
     *
     * @return void
     */
    function __construct()
    {
        $this->extLib = new ExternalLibraryLoader();
        $this->arrUri = UI::getUri();

        if (is_array($this->arrUri)
            and isset($this->arrUri['path_items'])
            and is_array($this->arrUri['path_items'])
            and count($this->arrUri['path_items']) == 0
        ) {
            $this->front_page();
        } else {
            switch($this->arrUri['format']) {
            case 'xml':
                $this->format = 'xml';
                break;
            case 'json':
                $this->format = 'json';
                break;
            case 'rss':
                switch($this->arrUri['path_items'][0]) {
                case 'daily':
                case 'weekly':
                case 'monthly':
                    $this->format = 'mp3.rss';
                    if (isset($this->arrUri['ua']) and preg_match('/^iTunes\/\d+\.\d+/', $this->arrUri['ua'])) {
                        $this->format = 'm4a.rss';
                    }
                    break;
                }
                break;
            case 'mp3':
                switch($this->arrUri['path_items'][0]) {
                case 'daily':
                case 'weekly':
                case 'monthly':
                    $this->format = 'mp3.rss';
                    break;
                }
                break;
            case 'ogg':
            case 'oga':
                switch($this->arrUri['path_items'][0]) {
                case 'daily':
                case 'weekly':
                case 'monthly':
                    $this->format = 'oga.rss';
                    break;
                }
                break;
            case 'mp4':
            case 'm4a':
                switch($this->arrUri['path_items'][0]) {
                case 'daily':
                case 'weekly':
                case 'monthly':
                    $this->format = 'm4a.rss';
                    break;
                }
            }
            if (count($this->arrUri['path_items']) == 1 and $this->arrUri['path_items'][0] == '') {
                $this->front_page();
                exit(0);
            }
            $object = array(1 => null, 2 => null, 3 => null);
            if (isset($this->arrUri['path_items'][1])) {
                $object[1] = $this->arrUri['path_items'][1];
            }
            if (isset($this->arrUri['path_items'][2])) {
                $object[2] = $this->arrUri['path_items'][2];
            }
            if (isset($this->arrUri['path_items'][3])) {
                $object[3] = $this->arrUri['path_items'][3];
            }
            switch($this->arrUri['path_items'][0]) {
            case 't':
                UI::Redirect("track/" . $object[1]);
                break;
            case 'track':
                $this->result['user'] = UserBroker::getUser()->getSelf();
                if ($this->format == 'rss') {
                    $this->change($object[1]);
                } else {
                    $this->track($object[1]);
                }
                break;
            case 's':
                UI::Redirect("show/" . $object[1]);
                break;
            case 'show':
                $this->result['user'] = UserBroker::getUser()->getSelf();
                $this->show($object[1]);
                break;
            case 'vote':
                $this->vote($object[1], $object[2]);
                break;
            case 'report':
                $this->report($object[1]);
                break;
            case 'review':
                $this->review($object[1], $this->arrUri['parameters']['isNSFW']);
                break;
            case 'chart':
                if (isset($this->arrUri['path_items'][1]) and $this->arrUri['path_items'][1] == 'rss') {
                    $this->format = 'rss';
                    $object[1] = $object[2];
                    $this->result['feedName'] = ConfigBroker::getConfig('Site Name', 'CCHits.net') . ' - ' . 
                        ConfigBroker::getConfig('Chart', 'Current Chart Places');
                }
                $this->chart($object[1]);
                break;
            case 'trend':
                if (isset($this->arrUri['path_items'][1]) and $this->arrUri['path_items'][1] == 'rss') {
                    $this->format = 'rss';
                    $object[1] = $object[2];
                    $this->result['feedName'] = ConfigBroker::getConfig('Site Name', 'CCHits.net') . ' - ' . 
                        ConfigBroker::getConfig('Chart', 'Current Trend Details');
                }
                $this->trend($object[1]);
                break;
            case 'change':
                if (isset($this->arrUri['path_items'][1]) and $this->arrUri['path_items'][1] == 'rss') {
                    $object[1] = $object[2];
                    $object[2] = $object[3];
                    $this->format = 'rss';
                    $this->result['feedName'] = ConfigBroker::getConfig('Site Name', 'CCHits.net') . ' - ' . 
                        ConfigBroker::getConfig('Change Log', 'Change Log');
                }
                $this->change($object[1], $object[2]);
                break;
            case 'd':
                if (isset($object[1]) && isset($object[2])) {
                    UI::Redirect("daily/" . $object[1] . "/" . $object[2]);
                } elseif (isset($object[1])) {
                    UI::Redirect("daily/" . $object[1]);
                } else {
                    UI::Redirect("daily");
                }
                break;
            case 'daily':
                if (isset($this->arrUri['path_items'][1]) 
                    and ($this->arrUri['path_items'][1] == 'mp3' or $this->arrUri['path_items'][1] == 'rss')
                ) {
                    $this->format = 'mp3.rss';
                } elseif (isset($this->arrUri['path_items'][1]) 
                    and ($this->arrUri['path_items'][1] == 'oga' or $this->arrUri['path_items'][1] == 'ogg')
                ) {
                    $this->format = 'oga.rss';
                } elseif (isset($this->arrUri['path_items'][1]) 
                    and ($this->arrUri['path_items'][1] == 'm4a' or $this->arrUri['path_items'][1] == 'mp4')
                ) {
                    $this->format = 'm4a.rss';
                }
                if (isset($this->arrUri['path_items'][1]) 
                    and ($this->arrUri['path_items'][1] == 'mp3' 
                    or $this->arrUri['path_items'][1] == 'rss' 
                    or $this->arrUri['path_items'][1] == 'oga' 
                    or $this->arrUri['path_items'][1] == 'ogg' 
                    or $this->arrUri['path_items'][1] == 'm4a' 
                    or $this->arrUri['path_items'][1] == 'mp4')
                ) {
                    if (isset($this->arrUri['path_items'][2])) {
                        $this->arrUri['path_items'][1] = $this->arrUri['path_items'][2];
                    } else {
                        $this->arrUri['path_items'][1] = '';
                    }
                    $this->result['feedName'] = ConfigBroker::getConfig('Site Name', 'CCHits.net') . ' - ' . 
                        ConfigBroker::getConfig('Daily Show Name', 'Daily Exposure Show');
                }
                if (array_key_exists(1, $this->arrUri['path_items'])) {
                    $this->daily($this->arrUri['path_items'][1]);
                } else {
                    $this->daily();
                }

                break;
            case 'w':
                if (isset($object[1]) && isset($object[2])) {
                    UI::Redirect("weekly/" . $object[1] . "/" . $object[2]);
                } elseif (isset($object[1])) {
                    UI::Redirect("weekly/" . $object[1]);
                } else {
                    UI::Redirect("weekly");
                }
                break;
            case 'weekly':
                if (isset($this->arrUri['path_items'][1]) 
                    and ($this->arrUri['path_items'][1] == 'mp3' or $this->arrUri['path_items'][1] == 'rss')
                ) {
                    $this->format = 'mp3.rss';
                } elseif (isset($this->arrUri['path_items'][1]) 
                    and ($this->arrUri['path_items'][1] == 'oga' or $this->arrUri['path_items'][1] == 'ogg')
                ) {
                    $this->format = 'oga.rss';
                } elseif (isset($this->arrUri['path_items'][1]) 
                    and ($this->arrUri['path_items'][1] == 'm4a' or $this->arrUri['path_items'][1] == 'mp4')
                ) {
                    $this->format = 'm4a.rss';
                }
                if (isset($this->arrUri['path_items'][1]) 
                    and ($this->arrUri['path_items'][1] == 'mp3' 
                    or $this->arrUri['path_items'][1] == 'rss' 
                    or $this->arrUri['path_items'][1] == 'oga' 
                    or $this->arrUri['path_items'][1] == 'ogg' 
                    or $this->arrUri['path_items'][1] == 'm4a' 
                    or $this->arrUri['path_items'][1] == 'mp4')
                ) {
                    if (isset($this->arrUri['path_items'][2])) {
                        $this->arrUri['path_items'][1] = $this->arrUri['path_items'][2];
                    } else {
                        $this->arrUri['path_items'][1] = '';
                    }
                    $this->result['feedName'] = ConfigBroker::getConfig('Site Name', 'CCHits.net') . ' - ' . 
                        ConfigBroker::getConfig('Weekly Show Name', 'Weekly Review Show');
                }
                $this->weekly($object[1]);
                break;
            case 'm':
                if (isset($object[1]) && isset($object[2])) {
                    UI::Redirect("monthly/" . $object[1] . "/" . $object[2]);
                } elseif (isset($object[1])) {
                    UI::Redirect("monthly/" . $object[1]);
                } else {
                    UI::Redirect("monthly");
                }
                break;
            case 'monthly':
                if (isset($this->arrUri['path_items'][1]) 
                    and ($this->arrUri['path_items'][1] == 'mp3' or $this->arrUri['path_items'][1] == 'rss')
                ) {
                    $this->format = 'mp3.rss';
                } elseif (isset($this->arrUri['path_items'][1]) 
                    and ($this->arrUri['path_items'][1] == 'oga' or $this->arrUri['path_items'][1] == 'ogg')
                ) {
                    $this->format = 'oga.rss';
                } elseif (isset($this->arrUri['path_items'][1]) 
                    and ($this->arrUri['path_items'][1] == 'm4a' or $this->arrUri['path_items'][1] == 'mp4')
                ) {
                    $this->format = 'm4a.rss';
                }
                if (isset($this->arrUri['path_items'][1]) 
                    and ($this->arrUri['path_items'][1] == 'mp3' 
                    or $this->arrUri['path_items'][1] == 'rss' 
                    or $this->arrUri['path_items'][1] == 'oga' 
                    or $this->arrUri['path_items'][1] == 'ogg' 
                    or $this->arrUri['path_items'][1] == 'm4a' 
                    or $this->arrUri['path_items'][1] == 'mp4')
                ) {
                    if (isset($this->arrUri['path_items'][2])) {
                        $this->arrUri['path_items'][1] = $this->arrUri['path_items'][2];
                    } else {
                        $this->arrUri['path_items'][1] = '';
                    }
                    $this->result['feedName'] = ConfigBroker::getConfig('Site Name', 'CCHits.net') . ' - ' .
                        ConfigBroker::getConfig('Monthly Show Name', 'Monthly Chart Show');
                }
                $this->monthly($object[1]);
                break;
            case 'extra':
                if (isset($this->arrUri['path_items'][1]) 
                    and ($this->arrUri['path_items'][1] == 'mp3' or $this->arrUri['path_items'][1] == 'rss')
                ) {
                    $this->format = 'mp3.rss';
                } elseif (isset($this->arrUri['path_items'][1]) 
                    and ($this->arrUri['path_items'][1] == 'oga' or $this->arrUri['path_items'][1] == 'ogg')
                ) {
                    $this->format = 'oga.rss';
                } elseif (isset($this->arrUri['path_items'][1]) 
                    and ($this->arrUri['path_items'][1] == 'm4a' or $this->arrUri['path_items'][1] == 'mp4')
                ) {
                    $this->format = 'm4a.rss';
                }
                if (isset($this->arrUri['path_items'][1]) 
                    and ($this->arrUri['path_items'][1] == 'mp3' 
                    or $this->arrUri['path_items'][1] == 'rss' 
                    or $this->arrUri['path_items'][1] == 'oga' 
                    or $this->arrUri['path_items'][1] == 'ogg' 
                    or $this->arrUri['path_items'][1] == 'm4a' 
                    or $this->arrUri['path_items'][1] == 'mp4')
                ) {
                    if (isset($this->arrUri['path_items'][2])) {
                        $this->arrUri['path_items'][1] = $this->arrUri['path_items'][2];
                    } else {
                        $this->arrUri['path_items'][1] = '';
                    }
                    $this->result['feedName'] = ConfigBroker::getConfig('Site Name', 'CCHits.net') . ' - ' . 
                        ConfigBroker::getConfig('Extra Show Name', 'Extra Shows');
                }
                $this->extra($object[1]);
                break;
            case 'about':
                $this->about($object[1]);
                break;
            case 'admin':
                $this->format = "html";
                $this->render();
                UI::start_session();
                if (isset($_SESSION['OPENID_AUTH']) and isset($_SESSION['cookie'])) {
                    unset($_SESSION['cookie']);
                }
                if (isset($_SESSION['addtracktoshow']) and ($object[1] != 'show' and $object[1] != 'addshow')) {
                    unset($_SESSION['addtracktoshow']);
                }
                if (isset($_SESSION['intTrackID']) and ($object[1] != 'addtrack' and $object[1] != 'track')) {
                    unset($_SESSION['intTrackID']);
                }
                $user = UserBroker::getUser();
                $this->result['user'] = $user->getSelf();
                switch ($object[1]) {
                case 'track':
                    $objTrack = TrackBroker::getTrackByID($object[2]);
                    if ($objTrack != false and ($user->get_isUploader() or $user->get_isAdmin())) {
                        $this->editTrack($objTrack);
                    } elseif ($objTrack == false) {
                        UI::sendHttpResponse(404);
                    } else {
                        $this->result['notuploader'] = true;
                        UI::SmartyTemplate("login.html", $this->result);
                    }
                    break;
                case 'show':
                    $objShow = ShowBroker::getShowByID($object[2]);
                    if ($object[2] == '' and $user->get_isAdmin()) {
                        $this->addTrackToShow();
                    } elseif ($objShow != false 
                        and $user->get_isAdmin() and $objShow->get_intUserID() == $user->get_intUserID()
                    ) {
                        $this->editShow($objShow);
                    } elseif ($objShow->get_intUserID() != $user->get_intUserID()) {
                        $this->result['notyourshow'] = true;
                        UI::SmartyTemplate("login.html", $this->result);
                    } elseif ($objShow == false) {
                        UI::sendHttpResponse(404);
                    } else {
                        $this->result['notadmin'] = true;
                        UI::SmartyTemplate("login.html", $this->result);
                    }
                    break;
                case 'artist':
                    $objArtist = ArtistBroker::getArtistByID($object[2]);
                    if ($objArtist != false and ($user->get_isUploader() or $user->get_isAdmin())) {
                        $this->editArtist($objArtist);
                    } elseif ($objTrack == false) {
                        UI::sendHttpResponse(404);
                    } else {
                        $this->result['notuploader'] = true;
                        UI::SmartyTemplate("login.html", $this->result);
                    }
                    break;
                case 'addtrack':
                    if (isset($_SESSION['intTrackID'])) {
                        $intTrackID = $_SESSION['intTrackID'];
                        unset($_SESSION['intTrackID']);
                        UI::Redirect("admin/track/" . $intTrackID);
                        break;
                    }
                    $objTrack = RemoteSourcesBroker::getRemoteSourceByID($object[2]);
                    if (($object[2] == '' or $objTrack != false) 
                        and ($user->get_isUploader() or $user->get_isAdmin())
                    ) {
                        $this->addTrack($objTrack);
                    } elseif ($objTrack == false) {
                        UI::sendHttpResponse(404);
                    } elseif ($objTrack->get_intUserID() != $user->get_intUserID()) {
                        $this->result['notyourtrack'] = true;
                        UI::SmartyTemplate("login.html", $this->result);
                    } else {
                        $this->result['notuploader'] = true;
                        UI::SmartyTemplate("login.html", $this->result);
                    }
                    break;
                case 'addartist':
                    $objArtist = ArtistBroker::getArtistByID($object[2]);
                    if (($object[2] == '' or $objArtist != false) 
                        and ($user->get_isUploader() or $user->get_isAdmin())
                    ) {
                        $this->addArtist($objArtist);
                    } elseif ($objTrack == false) {
                        UI::sendHttpResponse(404);
                    } else {
                        $this->result['notuploader'] = true;
                        UI::SmartyTemplate("login.html", $this->result);
                    }
                    break;
                case 'deltrack':
                    $objTrack = RemoteSourcesBroker::getRemoteSourceByID($object[2]);
                    if ($objTrack != false and $objTrack->get_intUserID() == $user->get_intUserID()) {
                        $objTrack->cancel();
                        UI::Redirect('admin/listtracks');
                    } elseif ($objTrack == false) {
                        UI::sendHttpResponse(404);
                    } else {
                        $this->result['notyourtrack'] = true;
                        UI::SmartyTemplate("login.html", $this->result);
                    }
                    break;
                case 'delshow':
                    $objShow = ShowBroker::getShowByID($object[2]);
                    if ($objShow != false and $objShow->get_intUserID() == $user->get_intUserID()) {
                        $objShow->cancel();
                        UI::Redirect('admin/listshows');
                    } elseif ($objTrack == false) {
                        UI::sendHttpResponse(404);
                    } else {
                        $this->result['notyourshow'] = true;
                        UI::SmartyTemplate("login.html", $this->result);
                    }
                    break;
                case 'addshow':
                    if ($user->get_isAdmin()) {
                        $this->addShow();
                    } else {
                        $this->result['notadmin'] = true;
                        UI::SmartyTemplate("login.html", $this->result);
                    }
                    break;
                case 'listtracks':
                    $tracks = RemoteSourcesBroker::getRemoteSourcesByUserID();
                    $this->result['tracks'] = array();
                    $track_counter = 0;
                    if (is_array($tracks) and count($tracks) > 0) {
                        foreach ($tracks as $track) {
                            $this->result['tracks'][$track_counter] = $track->getSelf();
                            try {
                                $track->is_valid_cchits_submission();
                            } catch (Exception $e) {
                                $this->result['tracks'][$track_counter]['error'] = $e->getMessage();
                            }
                            $track_counter++;
                        }
                    }
                    UI::SmartyTemplate('listunfinishedtracks.html', $this->result);
                    break;
                case 'listshows':
                    $this->result['previous_page'] = false;
                    $this->result['next_page'] = false;
                    $shows = ShowBroker::getShowByUserID($user);
                    foreach ($shows as $show) {
                        $this->result['shows'][$show->get_intShowID()] = $show->getSelf();
                        $this->result['shows'][$show->get_intShowID()]['countTracks'] = count($show->get_arrTracks());
                    }
                    if (isset($this->arrUri['parameters']['page']) and $this->arrUri['parameters']['page'] > 0) {
                        $this->result['previous_page'] = true;
                    }
                    $total_tracks = ShowBroker::getTotalShowsByUserID($user);
                    $current_tracks = (GeneralFunctions::getValue($this->arrUri['parameters'], 'page', 0, true) + 1) * 
                        GeneralFunctions::getValue($this->arrUri['parameters'], 'size', 25, true);
                    if ($total_tracks > $current_tracks) {
                        $this->result['next_page'] = true;
                    }
                    UI::SmartyTemplate('listmyshows.html', $this->result);
                    break;
                case 'basicauth':
                    $this->basicAuth();
                    break;
                case 'logout':
                    unset($_SESSION['OPENID_AUTH']);
                    UI::Redirect('admin');
                    break;
                case '':
                    if ($user->get_isUploader() or $user->get_isAdmin()) {
                        UI::SmartyTemplate('admin.html', $this->result);
                    } else {
                        if (isset($_SESSION['OPENID_AUTH']) 
                            and is_array($_SESSION['OPENID_AUTH']) and count($_SESSION['OPENID_AUTH']) > 0
                        ) {
                            $this->result['notuploader'] = true;
                            $this->result['notadmin'] = true;
                        }
                        UI::SmartyTemplate("login.html", $this->result);
                    }
                    break;
                default:
                    UI::Redirect('admin');
                }
                break;
            case 'statistics':
                $this->stats();
                die();
            default:
                $this->reset_page();
            }
        }
    }

    /**
     * Begin or complete a RemoteSources process for a new track
     *
     * @param false|object $objTrack False for an empty new track, or RemoteSources object for a track in-progress
     *
     * @return void
     */
    protected function addTrack($objTrack = null)
    {
        if ($objTrack == false) {
            $trackurl = GeneralFunctions::getValue($this->arrUri['parameters'], 'trackurl', '');
            $arrData = RemoteSourcesBroker::newTrackRouter($trackurl);
            if (is_object($arrData) and $arrData->get_intTrackID() > 0) {
                UI::Redirect("admin/track/" . $arrData->get_intTrackID());
            } elseif (is_object($arrData) and $arrData->get_intProcessingID() > 0) {
                $this->result['track'] = $arrData->getSelf();
                if ($arrData->get_intArtistID() == 0) {
                    $artists = ArtistBroker::getArtistByPartialUrl($this->result['track']['strArtistUrl'], 0, 10000);
                } else {
                    $artists = array(
                        $arrData->get_intArtistID() => ArtistBroker::getArtistByID($arrData->get_intArtistID())
                    );
                }
                if (!is_array($artists)) {
                    $artists = array();
                } else {
                    foreach ($artists as $artist) {
                        $this->result['artists'][] = $artist->getSelf();
                    }
                }
                $new_artists = ArtistBroker::getArtistByPartialName($this->result['track']['strArtistName'], 0, 10000);
                if (is_array($new_artists)) {
                    foreach ($new_artists as $artist) {
                        $this->result['artists'][] = $artist->getSelf();
                    }
                }
                if ($arrData->get_exception() != false) {
                    $this->result['error'] = $arrData->get_exception();
                }
                if (isset($_SESSION['intTrackID'])) {
                    $intTrackID = $_SESSION['intTrackID'];
                    unset($_SESSION['intTrackID']);
                    UI::Redirect("admin/track/" . $intTrackID);
                }
                UI::SmartyTemplate('trackimporter.html', $this->result);
            } elseif (is_array($arrData) and count($arrData) == 1) {
                foreach ($arrData as $key=>$value) {
                    // Get the last key/value pair from the array
                }
                if ($value == true) {
                    $track = TrackBroker::getTrackByID($key);
                    if (is_object($track)) {
                        $this->result['track'] = $track->getSelf();
                    } elseif (is_array($track)) {
                        $this->result['track'] = $track[0];
                    } else {
                        error_log("Response is : " . print_r($arrData));
                        UI::Redirect("admin");
                    }
                    $this->result['postimport'] = true;
                    UI::SmartyTemplate('trackeditor.html', $this->result);
                } else {
                    $objTrack = RemoteSourcesBroker::getRemoteSourceByID($key);
                    $this->result['track'] = $objTrack->getSelf();
                    if ($objTrack->get_intArtistID() == 0) {
                        $artists = ArtistBroker::getArtistByPartialUrl(
                            $this->result['track']['strArtistUrl'], 0, 10000
                        );
                    } else {
                        $artists = array(
                            $objTrack->get_intArtistID() => ArtistBroker::getArtistByID($objTrack->get_intArtistID())
                        );
                    }
                    if (!is_array($artists)) {
                        $artists = array();
                    } else {
                        foreach ($artists as $artist) {
                            $this->result['artists'][] = $artist->getSelf();
                        }
                    }
                    $new_artists = ArtistBroker::getArtistByPartialName(
                        $this->result['track']['strArtistName'], 0, 10000
                    );
                    if (is_array($new_artists)) {
                        foreach ($new_artists as $artist) {
                            $this->result['artists'][] = $artist->getSelf();
                        }
                    }
                    if ($objTrack->get_exception() != false) {
                        $this->result['error'] = $objTrack->get_exception();
                    }
                    UI::SmartyTemplate('trackimporter.html', $this->result);
                }
            } elseif (is_integer($arrData)) {
                // NEXTRELEASE: Improve the errors returned from the newTrackRouter!
                switch ($arrData) {
                case 406:
                    $this->result['errorcode'] = 406;
                    break;
                case 400:
                    $this->result['errorcode'] = 400;
                    break;
                case 404:
                    $this->result['errorcode'] = 404;
                    break;
                case 412:
                    $this->result['errorcode'] = 412;
                    break;
                case 417:
                    $this->result['errorcode'] = 417;
                    break;
                }
                UI::SmartyTemplate("trackimporter.html", $this->result);
            } else {
                UI::Redirect("admin");
            }
        } else {
            try {
                $intTrackID = $objTrack->amendRecord();
            } catch (Exception $e) {
                $this->result['error'] = $e;
            }
            if (isset($_SESSION['intTrackID'])) {
                $intTrackID = $_SESSION['intTrackID'];
                unset($_SESSION['intTrackID']);
                UI::Redirect("admin/track/" . $intTrackID);
            }
            $this->result['track'] = $objTrack->getSelf();
            if ($objTrack->get_intArtistID() == 0) {
                $artists = ArtistBroker::getArtistByPartialUrl($this->result['track']['strArtistUrl'], 0, 10000);
            } else {
                $artists = array(
                    $objTrack->get_intArtistID() => ArtistBroker::getArtistByID($objTrack->get_intArtistID())
                );
            }
            if (!is_array($artists)) {
                $artists = array();
            } else {
                foreach ($artists as $artist) {
                    $this->result['artists'][] = $artist->getSelf();
                }
            }
            $new_artists = ArtistBroker::getArtistByPartialName($this->result['track']['strArtistName'], 0, 10000);
            if (is_array($new_artists)) {
                foreach ($new_artists as $artist) {
                    $this->result['artists'][] = $artist->getSelf();
                }
            }
            if ($objTrack->get_exception() != false) {
                $this->result['error'] = $objTrack->get_exception();
            }
            UI::SmartyTemplate('trackimporter.html', $this->result);
        }
    }

    /**
     * Begin or complete adding an artist
     *
     * @param false|object $objArtist False for an empty new artist, or ArtistObject object for an Artist in-progress
     *
     * @return void
     */
    protected function addArtist($objArtist = null)
    {
        if ($objArtist == false) {
            $arrData = new NewArtistObject(
                $this->arrUri['parameters']['artistname'],
                $this->arrUri['parameters']['artistnamesounds'],
                $this->arrUri['parameters']['artisturl']
            );
            if (is_object($arrData) and $arrData->get_intArtistID() > 0) {
                $this->result['artist'] = $arrData->getSelf();
                $this->result['postimport'] = true;
                UI::SmartyTemplate('artisteditor.html', $this->result);
            } else {
                UI::SmartyTemplate('artisteditor.html', $this->result);
            }
        } else {
            try {
                $objArtist->amendRecord();
            } catch (Exception $e) {
                $this->result['error']=$e;
            }
            $this->result['artist'] = $objArtist->getSelf();
            // TODO: Create artisteditor.html.tpl
            UI::SmartyTemplate('artisteditor.html', $this->result);
        }
    }


    /**
     * Begin the process of associating a track to a show.
     * TODO: Tidy up addtracktoshow.html.tpl
     *
     * @return void
     */
    protected function addTrackToShow()
    {
        $this->result['track'] = TrackBroker::getTrackByID($this->arrUri['parameters']['intTrackID']);
        if (isset($this->arrUri['parameters']['intTrackID']) 
            and $this->arrUri['parameters']['intTrackID'] != '' and $this->result['track'] != false
        ) {
            $_SESSION['addtracktoshow'] = $this->arrUri['parameters']['intTrackID'];
            $this->result['track'] = $this->result['track']->getSelf();
            $shows = ShowBroker::getShowByUserID(UserBroker::getUser()->get_intUserID(), 0, 100);
            foreach ($shows as $show) {
                $this->result['shows'][] = $show->getSelf();
            }
            UI::SmartyTemplate('addtracktoshow.html', $this->result);
        } else {
            UI::Redirect("admin");
        }
    }

    /**
     * Begin a New Show process. This will redirect to editShow once the new show process has been started.
     *
     * @return void
     */
    protected function addShow()
    {
        if (isset($this->arrUri['parameters']['strShowUrl']) and $this->arrUri['parameters']['strShowUrl'] != '') {
            $showUrl = $this->arrUri['parameters']['strShowUrl'];
            $showName = $this->arrUri['parameters']['strShowUrl'];
            if (isset($this->arrUri['parameters']['strShowName']) 
                and $this->arrUri['parameters']['strShowName'] != ''
            ) {
                $showName = $this->arrUri['parameters']['strShowName'];
            }
            $show = new NewExternalShowObject($showUrl, $showName);
            if (is_object($show)) {
                $intShowID = $show->get_intShowID();
                if (isset($this->arrUri['parameters']['intTrackID']) 
                    and $this->arrUri['parameters']['intTrackID'] != ''
                ) {
                    $temp = new NewShowTrackObject($this->arrUri['parameters']['intTrackID'], $show->get_intShowID());
                }
                UI::Redirect('admin/show/' . $intShowID);
            } else {
                UI::Redirect('admin');
            }
        }
    }

    /**
     * Edit an existing track.
     *
     * @param object $objTrack The track object
     *
     * @return void
     */
    protected function editTrack($objTrack = null)
    {
        if ($objTrack != false) {
            $objTrack->amendRecord();
            $this->result['track'] = $objTrack->getSelf();
            UI::SmartyTemplate('trackeditor.html', $this->result);
        }
    }

    /**
     * Edit an existing Show.
     *
     * @param object $objShow The show object
     *
     * @return void
     */
    protected function editShow($objShow = null)
    {
        if ($objShow != false) {
            if (isset($_SESSION['intTrackID']) and TrackBroker::getTrackByID($_SESSION['intTrackID']) != false) {
                $temp = new NewShowTrackObject($_SESSION['intTrackID'], $objShow->get_intShowID());
                unset($_SESSION['intTrackID']);
            }
            if (isset($this->arrUri['parameters']['intTrackID']) 
                and TrackBroker::getTrackByID($this->arrUri['parameters']['intTrackID']) != false
            ) {
                $temp = new NewShowTrackObject($this->arrUri['parameters']['intTrackID'], $objShow->get_intShowID());
            }
            if (isset($this->arrUri['parameters']['strShowName']) 
                and $this->arrUri['parameters']['strShowName'] != ""
            ) {
                $objShow->set_strShowName($this->arrUri['parameters']['strShowName']);
                $objShow->write();
            }
            if (isset($this->arrUri['parameters']['strShowUrl']) and $this->arrUri['parameters']['strShowUrl'] != "") {
                $objShow->set_strShowUrl($this->arrUri['parameters']['strShowUrl']);
                $objShow->write();
            }
            if (isset($this->arrUri['parameters']['moveup']) 
                and TrackBroker::getTrackByID($this->arrUri['parameters']['moveup']) != false
            ) {
                ShowTrackBroker::MoveShowTrackUp($objShow, $this->arrUri['parameters']['moveup']);
            }
            if (isset($this->arrUri['parameters']['movedown']) 
                and TrackBroker::getTrackByID($this->arrUri['parameters']['movedown']) != false
            ) {
                ShowTrackBroker::MoveShowTrackDown($objShow, $this->arrUri['parameters']['movedown']);
            }
            if (isset($this->arrUri['parameters']['remove']) 
                and TrackBroker::getTrackByID($this->arrUri['parameters']['remove']) != false
            ) {
                ShowTrackBroker::RemoveShowTrack($objShow, $this->arrUri['parameters']['remove']);
            }
        }
        $objShow->get_arrTracks(true);
        $this->result['show'] = $objShow->getSelf();
        UI::SmartyTemplate('showeditor.html', $this->result);
    }

    /**
     * Set or amend the BasicAuth credentials for this OpenID account
     *
     * @return void
     */
    protected function basicAuth()
    {
        $user = UserBroker::getUser();
        $this->result['error'] = false;
        if (isset($this->arrUri['parameters']['strUsername']) 
            and isset($this->arrUri['parameters']['strPassword']) 
            and $this->arrUri['parameters']['strUsername'] != '' 
            and $this->arrUri['parameters']['strPassword'] != ''
        ) {
            $newCredentials = "{$this->arrUri['parameters']['strUsername']}:" . 
                sha1($this->arrUri['parameters']['strPassword']);
            $user->set_sha1Pass($newCredentials);
            if ($user->write()) {
                UI::Redirect('admin');
            }
            $this->result['error'] = true;
        }
        UI::SmartyTemplate('setcredentials.html', $this->result);
    }

    /**
     * Force the user back to the home page
     *
     * @return void
     */
    protected function reset_page()
    {
        UI::Redirect('');
    }

    /**
     * Render the front page
     *
     * @return void
     */
    protected function front_page()
    {
        $chart = ChartBroker::getChartByDate('', 0, 15);
        $this->result['chart'] = $chart['position'];
        $internal_show = ShowBroker::getInternalShowByType('daily', 1);
        $show = end($internal_show);
        $show->set_featuring(false);
        $this->result['daily'] = $show->getSelf();
        $internal_show = ShowBroker::getInternalShowByType('weekly', 1);
        $show = end($internal_show);
        $show->set_featuring(false);
        $this->result['weekly'] = $show->getSelf();
        $internal_show = ShowBroker::getInternalShowByType('monthly', 1);
        $show = end($internal_show);
        $show->set_featuring(false);
        $this->result['monthly'] = $show->getSelf();
        if ($this->render()) {
            if ($this->format == 'html') {
                $this->result['daily_player_json'] = json_encode(array($this->result['daily']['player_data']));
                $this->result['weekly_player_json'] = json_encode(array($this->result['weekly']['player_data']));
                $this->result['monthly_player_json'] = json_encode(array($this->result['monthly']['player_data']));
            }
            UI::SmartyTemplate("frontpage.{$this->format}", $this->result);
        }
    }

    /**
     * Renders the stats page
     * 
     * @return void
     */
    protected function stats()
    {
        $internal_show = ShowBroker::getInternalShowByType('daily', 1);
        $show = end($internal_show);
        $show->set_featuring(false);
        $daily = $show->getSelf();
        $internal_show = ShowBroker::getInternalShowByType('weekly', 1);
        $show = end($internal_show);
        $show->set_featuring(false);
        $weekly = $show->getSelf();
        $internal_show = ShowBroker::getInternalShowByType('monthly', 1);
        $show = end($internal_show);
        $show->set_featuring(false);
        $monthly = $show->getSelf();
        if ($this->render()) {
            if ($this->format == 'html') {
                $this->result['daily_player_json'] = json_encode(array($daily['player_data']));
                $this->result['weekly_player_json'] = json_encode(array($weekly['player_data']));
                $this->result['monthly_player_json'] = json_encode(array($monthly['player_data']));
            }
        }
        $this->result['stats'] = StatsBroker::getStats()->getSelf();
        if ($this->render()) {
            UI::SmartyTemplate("stats.{$this->format}", $this->result);
        }
    }

    /**
     * Render track data
     *
     * @param integer $track The track to return data upon
     *
     * @return void
     */
    function track($track = 0)
    {
        if ($track != null and (0 + $track > 0)) {
            $track = TrackBroker::getTrackByID(UI::getLongNumber($track));
            if ($track != false) {
                $track->set_full(true);
                $this->result['track'] = $track->getSelf();
                if ($this->render()) {
                    $show = [
                        "name" => $this->result['track']['strArtistName'],
                        "title" => $this->result['track']['strTrackName'],
                        "free" => "true",
                        "link" => $this->result['track']['localSource'],
                        "mp3_len" => 0,
                        "oga_len" => 0,
                        "m4a_len" => 0,
                    ];
                    $this->result['single_player_json'] = json_encode(array($show));
                    UI::SmartyTemplate("track.{$this->format}", $this->result);
                }
            } else {
                UI::sendHttpResponse(404);
            }
        } else {
            UI::sendHttpResponse(404);
        }
    }

    /**
     * Render show data, or redirect to appropriate internal pages
     *
     * @param integer $show The show to render
     *
     * @return void
     */
    function show($show = 0)
    {
        if ($show != null and (0 + $show > 0)) {
            $show = ShowBroker::getShowByID(UI::getLongNumber($show));
            if ($show != false) {
                $this->result['show'] = $show->getSelf();
            }
            if ($this->render()) {
                $this->result['playlist_json'] = json_encode(array($this->result['show']['player_data']));
                // TODO: Write show.rss.tpl
                UI::SmartyTemplate("show.{$this->format}", $this->result);
            }
        } else {
            UI::sendHttpResponse(404);
        }
    }

    /**
     * Vote for a track.
     *
     * @param integer $track Track to vote for
     * @param integer $show  Track within which show
     *
     * @return void
     */
    function vote($track = 0, $show = 0)
    {
        $vote = false;
        $this->result['show'] = false;
        $this->arrUri = UI::getUri();
        $objTrack = TrackBroker::getTrackByID(UI::getLongNumber($track));
        $objShow = false;
        if ($show != 0) {
            $objShow = ShowBroker::getShowByID(UI::getLongNumber($show));
        }
        if ($objTrack != false) {
            if ($objShow == true) {
                $objShowTrack = ShowTrackBroker::getShowTracksByShowTrackID($show, $track);
            }
            if ($objShow == false or $objShowTrack == false) {
                $show = 0;
            } else {
                $this->result['show'] = $objShow->getSelf();
            }
        } else {
            UI::sendHttpResponse(404);
        }
        if (isset($this->arrUri['parameters']['go']) or VoteBroker::hasMyUserIDVotedForThisTrack($track)) {
            $vote = new NewVoteObject(UI::getLongNumber($track), UI::getLongNumber($show));
            if ($this->render()) {
                $objTrack->set_full(true);
                $this->result['track'] = $objTrack->getSelf();
                UI::SmartyTemplate("voted.html", $this->result);
            }
        } else {
            $this->result['vote_url'] = $this->arrUri['full'] . '?go';
            if ($this->render()) {
                $this->result['track'] = $objTrack->getSelf();
                UI::SmartyTemplate("vote.html", $this->result);
            }
        }
    }

    /**
     * Report a track as not safe for familly or work.
     *
     * @param integer $track The reported track
     *
     * @return void
     */
    function report($track = 0)
    {
        if ($track == 0) {
            return;
        }

        $objTrack = TrackBroker::getTrackByID(UI::getLongNumber($track));

        if ($objTrack->get_isNSFW()) {
            return;
        }

        $objTrack->set_needsReview(true);
        $objTrack->write();

        UI::Redirect("track/" . $track);
    }

    /**
     * Review a track : set the isNSFW flag.
     *
     * @param integer $track  Track to review
     * @param bool    $isNSFW sets the "isNSFW" flag.
     *
     * @return void
     */
    function review($track = 0, $isNSFW = false)
    {
        if ($track == 0) {
            return;
        }

        $objTrack = TrackBroker::getTrackByID(UI::getLongNumber($track));

        $isNSFW = GenericObject::asBoolean($isNSFW);

        $objTrack->set_isNSFW($isNSFW);
        $objTrack->set_needsReview(false);
        $objTrack->write();

        UI::Redirect("track/" . $track);
    }

    /**
     * Render a chart for the site
     *
     * @param integer $date The date of the chart to return
     *
     * @return void
     */
    function chart($date = null)
    {
        if ($this->format == 'rss') {
            $this->result['chart'] = ChartBroker::getChartByDate($date, 0, TrackBroker::getTotalTracks());
        } else {
            $this->result['chart'] = ChartBroker::getChartByDate($date);
        }
        if ($this->render()) {
            if (isset($this->arrUri['parameters']['page']) and $this->arrUri['parameters']['page'] > 0) {
                $this->result['previous_page'] = true;
            }
            if (! array_key_exists(TrackBroker::getTotalTracks(), $this->result['chart'])) {
                $this->result['next_page'] = true;
            }
            UI::SmartyTemplate("chart.{$this->format}", $this->result);
        }
    }

    /**
     * Render a trend for the site
     *
     * @param integer $date The date of the chart to return
     *
     * @return void
     */
    function trend($date = null)
    {
        if ($this->format == 'rss') {
            $this->result['trend'] = TrendBroker::getTrendByDate($date, 0, TrackBroker::getTotalTracks());
        } else {
            $this->result['trend'] = TrendBroker::getTrendByDate($date);
        }
        if ($this->render()) {
            if (isset($this->arrUri['parameters']['page']) and $this->arrUri['parameters']['page'] > 0) {
                $this->result['previous_page'] = true;
            }
            if (! array_key_exists(TrackBroker::getTotalTracks(), $this->result['trend'])) {
                $this->result['next_page'] = true;
            }
            UI::SmartyTemplate("trend.{$this->format}", $this->result);
        }
    }

    /**
     * Find and list all the changes for one day
     *
     * @param integer $intTrackID The optional trackID to search for
     * @param integer $date       The date to search for
     *
     * @return void
     */
    function change($intTrackID = null, $date = null)
    {
        $this->result['changes'] = ChangeBroker::getChangeByDate($intTrackID, $date);
        if ($this->render()) {
            UI::SmartyTemplate("change.{$this->format}", $this->result);
        }
    }

    /**
     * Either redirect from the daily page to the /show/showid or return an RSS feed.
     *
     * @param integer $showdate The date of the show to return. Leave blank for an RSS feed.
     *
     * @return void
     */
    function daily($showdate = '')
    {
        if ($showdate != '') {
            $show = ShowBroker::getInternalShowByDate('daily', $showdate);
            if ($show != false) {
                UI::Redirect('show/' . $show->get_intShowID());
                exit(0);
            }
        }
        $shows = ShowBroker::getInternalShowByType('daily');
        foreach ($shows as $intShowID=>$show) {
            $this->result['shows'][$intShowID] = $show->getSelf();
            $playlist[$intShowID] = $this->result['shows'][$intShowID]['player_data'];
        }
        if ($this->render()) {
            $this->result['playlist_json'] = json_encode($playlist);
            /*
            var_dump($playlist);
            die();
            */
            UI::SmartyTemplate("shows.{$this->format}", $this->result);
        }
    }

    /**
     * Either redirect from the weekly page to the /show/showid or return an RSS feed.
     *
     * @param integer $showdate The date of the show to return. Leave blank for an RSS feed.
     *
     * @return void
     */
    function weekly($showdate = '')
    {
        if ($showdate != '') {
            $show = ShowBroker::getInternalShowByDate('weekly', $showdate);
            if ($show != false) {
                UI::Redirect('show/' . $show->get_intShowID());
                exit(0);
            }
        }
        $shows = ShowBroker::getInternalShowByType('weekly');
        foreach ($shows as $intShowID=>$show) {
            $this->result['shows'][$intShowID] = $show->getSelf();
            $playlist[$intShowID] = $this->result['shows'][$intShowID]['player_data'];
        }
        if ($this->render()) {
            $this->result['playlist_json'] = json_encode($playlist);
            UI::SmartyTemplate("shows.{$this->format}", $this->result);
        }
    }

    /**
     * Either redirect from the monthly page to the /show/showid or return an RSS feed.
     *
     * @param integer $showdate The date of the show to return. Leave blank for an RSS feed.
     *
     * @return void
     */
    function monthly($showdate = '')
    {
        if ($showdate != '') {
            $show = ShowBroker::getInternalShowByDate('monthly', $showdate);
            if ($show != false) {
                UI::Redirect('show/' . $show->get_intShowID());
                exit(0);
            }
        }
        $shows = ShowBroker::getInternalShowByType('monthly');
        foreach ($shows as $intShowID=>$show) {
            $this->result['shows'][$intShowID] = $show->getSelf();
            $playlist[$intShowID] = $this->result['shows'][$intShowID]['player_data'];
        }
        if ($this->render()) {
            $this->result['playlist_json'] = json_encode($playlist);
            UI::SmartyTemplate("shows.{$this->format}", $this->result);
        }
    }

    /**
     * Either redirect from the monthly page to the /show/showid or return an RSS feed.
     *
     * @param integer $showid The show ID to return. Leave blank for an RSS feed.
     *
     * @return void
     */
    function extra($showid = '')
    {
        if ($showid != '') {
            $show = ShowBroker::getInternalShowByDate('extra', $showid);
            if ($show != false) {
                UI::Redirect('show/' . $show->get_intShowID());
                exit(0);
            }
        }
        $shows = ShowBroker::getInternalShowByType('extra');
        foreach ($shows as $intShowID=>$show) {
            $this->result['shows'][$intShowID] = $show->getSelf();
            $playlist[$intShowID] = $this->result['shows'][$intShowID]['player_data'];
        }
        if ($this->render()) {
            $this->result['playlist_json'] = json_encode($playlist);
            UI::SmartyTemplate("shows.{$this->format}", $this->result);
        }
    }

    /**
     * Render the FAQ, or direct to external services
     *
     * @param string $page The page to render
     *
     * @return void
     */
    function about($page = '')
    {
        switch($page) {
        case 'goals':
            UI::Redirect('about/#goals');
            break;
        case 'source':
            UI::Redirect('about/#source');
            break;
        case 'database':
            $this->arrUri = UI::getUri();
            if (isset($this->arrUri['parameters']['go'])) {
                $this->database_export();
            } else {
                UI::Redirect('about/#database');
            }
            break;
        case 'api':
            UI::Redirect('about/#api');
            break;
        case 'voteadjust':
            UI::Redirect('about/#voteadjust');
            break;
        case 'theme':
            UI::Redirect('about/#theme');
            break;
        case 'faq':
        default:
            $this->result['ServiceName'] = ConfigBroker::getConfig('ServiceName', 'CCHits');
            $this->result['Slogan'] = ConfigBroker::getConfig('Slogan', 'Where you make the charts');
            $this->result['baseURL'] = $this->arrUri['basePath'];
            $this->result['arrUri'] = $this->arrUri;
            $this->result['jquery'] = $this->extLib->getVersion('JQUERY');
            $this->result['jplayer'] = $this->extLib->getVersion('JPLAYER');
            $this->result['jplayer29'] = $this->extLib->getVersion('JPLAYER29');
            $this->result['jquerysparkline'] = $this->extLib->getVersion('JQUERY.SPARKLINE');
            $this->result['bootstrap4'] = $this->extLib->getVersion('BOOTSTRAP4');
            $this->result['jquery3'] = $this->extLib->getVersion('JQUERY3');
            $this->result['popperjs'] = $this->extLib->getVersion('POPPERJS');
            $this->result['chartjs'] = $this->extLib->getVersion('CHARTJS');
            $this->result['fontawesome'] = $this->extLib->getVersion('FONTAWESOME');
            $this->result['previous_page'] = false;
            UI::SmartyTemplate("about.html", $this->result);
            break;
        }
    }

    /**
     * Return an export of the whole database. Yehr, I know it's using MySQL libraries, rather than PDO, but frankly, 
     * I couldn't figure out how to do this in PDO.
     *
     * @return void
     */
    protected function database_export()
    {
        set_time_limit(0);
        header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename="cchits.' . date("Y-m-d_Hi") . '.sql"');

        echo "/* This DATABASE and it's DATA is made available under a Creative Commons Zero license: " .
            "http://creativecommons.org/publicdomain/zero/1.0/ */". "\r\n\r\n";

        include dirname(__FILE__) . '/../CONFIG/CONFIG_DEFAULT.php';
        if ($SPLIT_RO_RW == false) {
            mysql_connect($RW_HOST, $RW_USER, $RW_PASS);
            mysql_select_db($RW_BASE);
        } else {
            mysql_connect($RO_HOST, $RO_USER, $RO_PASS);
            mysql_select_db($RO_BASE);
        }

        $qryTables = mysql_query("show tables");
        if (mysql_errno() == 0) {
            while ($arrTable = mysql_fetch_row($qryTables)) {
                $qryCreate = mysql_query("show create table `{$arrTable[0]}`");
                if (mysql_errno() == 0) {
                    if ($arrCreate = mysql_fetch_assoc($qryCreate)) {
                        echo $arrCreate['Create Table'] . ";\r\n\r\n";
                    }
                }
                $qryData = mysql_query("SELECT * FROM `{$arrTable[0]}`");
                if (mysql_errno() == 0 and mysql_num_rows($qryData) > 0) {
                    echo "INSERT INTO {$arrTable[0]} VALUES \r\n";
                    $first_row = 1;
                    while ($arrData = mysql_fetch_array($qryData, MYSQL_ASSOC)) {
                        if ($first_row != 1) {
                            echo ", \r\n";
                        } else {
                            $first_row = 0;
                        }
                        $first_col = 1;
                        echo "(";
                        foreach ($arrData as $key=>$value) {
                            if ($first_col != 1) {
                                echo ", ";
                            } else {
                                $first_col = 0;
                            }
                            if (is_null($value)) {
                                echo "NULL";
                            } else {
                                if (($key == 'strOpenID' or $key == 'sha1Pass') and $value != '') {
                                    echo "'" . mysql_real_escape_string(sha1($value)) . "'";
                                } elseif ($key == 'value' 
                                    and ($last_val == 'CronTab User' or $last_val == 'CronTab Pass')
                                ) {
                                    echo "'" . mysql_real_escape_string(sha1($value)) . "'";
                                } else {
                                    echo "'" . mysql_real_escape_string($value) . "'";
                                    if ($key=='key') {
                                        $last_val = $value;
                                    } else {
                                        $last_val = '';
                                    }
                                }
                            }
                        }
                        echo ")";
                    }
                    echo ";\r\n\r\n";
                }
            }
        }
    }

    /**
     * Render content in the above function, or just return data?
     *
     * @return boolean Returns whether the data needs to be rendered in the function.
     */
    protected function render()
    {
        switch($this->format) {
        case 'json':
            if ($this->result != null) {
                header("Content-type: application/json");
                echo UI::utf8json($this->result);
                return false;
            } else {
                UI::sendHttpResponse(500);
            }
        case 'xml':
            if ($this->result != null) {
                header("Content-type: application/xml");
                echo UI::utf8xml($this->result->getSelf());
                return false;
            } else {
                UI::sendHttpResponse(500);
            }
        case 'html':
            $this->result['config'] = ConfigBroker::getAllConfig();
            $this->result['ServiceName'] = ConfigBroker::getConfig('ServiceName', 'CCHits');
            $this->result['Slogan'] = ConfigBroker::getConfig('Slogan', 'Where you make the charts');
            $this->result['baseURL'] = $this->arrUri['basePath'];
            $this->result['arrUri'] = $this->arrUri;
            $this->result['jquery'] = $this->extLib->getVersion('JQUERY');
            $this->result['bootstrap'] = $this->extLib->getVersion('BOOTSTRAP');
            $this->result['jplayer'] = $this->extLib->getVersion('JPLAYER');
            $this->result['jplayer29'] = $this->extLib->getVersion('JPLAYER29');
            $this->result['jquerysparkline'] = $this->extLib->getVersion('JQUERY.SPARKLINE');
            $this->result['bootstrap4'] = $this->extLib->getVersion('BOOTSTRAP4');
            $this->result['jquery3'] = $this->extLib->getVersion('JQUERY3');
            $this->result['popperjs'] = $this->extLib->getVersion('POPPERJS');
            $this->result['chartjs'] = $this->extLib->getVersion('CHARTJS');
            $this->result['fontawesome'] = $this->extLib->getVersion('FONTAWESOME');
            $this->result['previous_page'] = false;
            if (isset($this->arrUri['parameters']['page']) and $this->arrUri['parameters']['page'] > 0) {
                $this->result['previous_page'] = true;
            }
            $this->result['next_page'] = false;
            $this->result['ShowDaily'] = ConfigBroker::getConfig('Daily Show Name', 'Daily Exposure Show');
            $this->result['ShowWeekly'] = ConfigBroker::getConfig('Weekly Show Name', 'Weekly Review Show');
            $this->result['ShowMonthly'] = ConfigBroker::getConfig('Monthly Show Name', 'Monthly Chart Show');
            $this->result['contactName'] = ConfigBroker::getConfig('Contact Name', 'CCHits.net Show Admin');
            $this->result['contactEmail'] = ConfigBroker::getConfig('Contact EMail', 'show@cchits.net');
            header('Content-Type:text/html; charset=UTF-8');
            return true;
        case 'rss':
        case 'oga.rss':
        case 'mp3.rss':
        case 'm4a.rss':
            $this->result['feedName'] = ConfigBroker::getConfig('Site Name', 'CCHits.net');
            $this->result['feedDescription'] = ConfigBroker::getConfig(
                'About The Site', 'CCHits.net is designed to provide a Chart for Creative Commons Music, in a way ' .
                'that is easily able to be integrated into other music shows that play Creative Commons Music. ' .
                'CCHits.net has a daily exposure podcast, playing one new track every day, a weekly podcast, ' .
                'playing the last week of tracks played on the podcast, plus the top rated three tracks from the ' .
                'previous week. There is also a monthly podcast which features the top rated tracks over the whole ' .
                'system.'
            );
            $this->result['feedWhen'] = $this->arrUri['path_items'][0];
            $this->result['showsLink'] = $this->arrUri['basePath'] . $this->arrUri['path_items'][0];
            $this->result['feedLink'] = $this->arrUri['basePath'] . $this->arrUri['path_items'][0] . '/rss';
            $this->result['siteCopyright'] = ConfigBroker::getConfig(
                'System License', 'The content created by this site is generated by a script which is licensed under ' .
                'the Affero General Public License version 3 (AGPL3). The generated content is released under a ' .
                'Creative Commons By-Attribution License.'
            );
            $this->result['feedDate'] = date('r', strtotime(date('Y-m-d') . ' 00:00:00'));
            $this->result['baseURL'] = $this->arrUri['basePath'];
            $this->result['feedOwner'] = ConfigBroker::getConfig('Contact EMail', 'show@cchits.net') . ' (' . 
                ConfigBroker::getConfig('Contact Name', 'CCHits.net Show Admin') . ')';
            $this->result['contactName'] = ConfigBroker::getConfig('Contact Name', 'CCHits.net Show Admin');
            $this->result['contactEmail'] = ConfigBroker::getConfig('Contact EMail', 'show@cchits.net');
            header("Content-Type: application/rss+xml");
            return true;
        default:
            UI::sendHttpResponse(404);
        }
    }
}

