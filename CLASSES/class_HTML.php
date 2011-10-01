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
 * This class handles all HTML requests
 *
 * @category Default
 * @package  UI
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
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
                    $this->format = 'rss';
                    break;
                case 'weekly':
                    $this->format = 'rss';
                    break;
                case 'monthly':
                    $this->format = 'rss';
                    break;
                }
            }
            if (count($this->arrUri['path_items']) == 1 and $this->arrUri['path_items'][0] == '') {
                $this->front_page();
                exit(0);
            }
            $object = array(1 => null, 2 => null);
            if (isset($this->arrUri['path_items'][1])) {
                $object[1] = $this->arrUri['path_items'][1];
            }
            if (isset($this->arrUri['path_items'][2])) {
                $object[2] = $this->arrUri['path_items'][2];
            }
            switch($this->arrUri['path_items'][0]) {
            case 'track':
            case 't':
                $this->result['user'] = UserBroker::getUser()->getSelf();
                $this->track($object[1]);
                break;
            case 'show':
            case 's':
                $this->result['user'] = UserBroker::getUser()->getSelf();
                $this->show($object[1]);
                break;
            case 'vote':
                $this->vote($object[1], $object[2]);
                break;
            case 'chart':
                $this->chart($object[1]);
                break;
            case 'daily':
                if (isset($this->arrUri['path_items'][1]) and $this->arrUri['path_items'][1] == 'rss') {
                    $this->format = 'rss';
                    $this->arrUri['path_items'][1] = $this->arrUri['path_items'][2];
                    $this->result['feedName'] = ConfigBroker::getConfig('Site Name', 'CCHits.net') . ' - ' . ConfigBroker::getConfig('Daily Show Name', 'Daily Exposure Show');
                }
                $this->daily($object[1]);
                break;
            case 'weekly':
                if (isset($this->arrUri['path_items'][1]) and $this->arrUri['path_items'][1] == 'rss') {
                    $this->format = 'rss';
                    $this->arrUri['path_items'][1] = $this->arrUri['path_items'][2];
                    $this->result['feedName'] = ConfigBroker::getConfig('Site Name', 'CCHits.net') . ' - ' . ConfigBroker::getConfig('Weekly Show Name', 'Weekly Review Show');
                }
                $this->weekly($object[1]);
                break;
            case 'monthly':
                if (isset($this->arrUri['path_items'][1]) and $this->arrUri['path_items'][1] == 'rss') {
                    $this->format = 'rss';
                    $this->arrUri['path_items'][1] = $this->arrUri['path_items'][2];
                    $this->result['feedName'] = ConfigBroker::getConfig('Site Name', 'CCHits.net') . ' - ' . ConfigBroker::getConfig('Monthly Show Name', 'Monthly Chart Show');
                }
                $this->monthly($object[1]);
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
                    if ($objShow != false and $user->get_isAdmin() and $objShow->get_intUserID() == $user->get_intUserID()) {
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
                case 'addtrack':
                    $objTrack = RemoteSourcesBroker::getRemoteSourceByID($object[2]);
                    if (($object[2] == '' or $objTrack != false) and ($user->get_isUploader() or $user->get_isAdmin())) {
                        $this->addTrack($objTrack);
                    } elseif ($objTrack->get_intUserID() != $user->get_intUserID()) {
                        $this->result['notyourtrack'] = true;
                        UI::SmartyTemplate("login.html", $this->result);
                    } elseif ($objTrack == false) {
                        UI::sendHttpResponse(404);
                    } else {
                        $this->result['notuploader'] = true;
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
                    $this->listtracks(RemoteSourcesBroker::getRemoteSourcesByUserID());
                    break;
                case 'listshows':
                    $this->listshows(ShowBroker::getShowByUserID($user));
                    break;
                case 'basicauth':
                    $this->basicAuth();
                    break;
                case '':
                    if ($user->get_isUploader() or $user->get_isAdmin()) {
                        UI::SmartyTemplate('admin.html', $this->result);
                    } else {
                        if (isset($_SESSION['OPENID_AUTH']) and is_array($_SESSION['OPENID_AUTH']) and count($_SESSION['OPENID_AUTH']) > 0) {
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
            default:
                $this->reset_page();
            }
        }
    }

    /**
     * Begin or complete a RemoteSources process for a new track
     * TODO: Write HTML::addTrack() function
     *
     * @param false|object $objTrack False for an empty new track, or RemoteSources object for a track in-progress
     *
     * @return void
     */
    protected function addTrack($objTrack = null)
    {
        if ($objTrack == false) {
            $arrData = RemoteSourcesBroker::newTrackRouter($this->arrUri['parameters']['trackurl']);
            if (is_array($arrData) and count($arrData) == 1) {
                foreach ($arrData as $key=>$value) {}
                if ($value == true) {
                    $this->result['track'] = TrackBroker::getTrackByID($key)->getSelf();
                    $this->result['postimport'] = true;
                    UI::SmartyTemplate('trackeditor.html', $this->result);
                } else {
                    $this->result['track'] = RemoteSourcesBroker::getRemoteSourceByID($key);
                    // TODO: Create trackimporter.html.tpl
                    UI::SmartyTemplate('trackimporter.html', $this->result);
                }
            } elseif (is_integer($arrData)) {
                // Improve these!
                switch ($arrData) {
                case 406:
                    $this->result['error'] = 406;
                    break;
                case 400:
                    $this->result['error'] = 400;
                    break;
                case 404:
                    $this->result['error'] = 404;
                    break;
                case 412:
                    $this->result['error'] = 412;
                    break;
                case 417:
                    $this->result['error'] = 417;
                    break;
                }
                UI::SmartyTemplate("error_with_track.html", $this->result);
            } else {
                UI::Redirect("admin");
            }
        } else {
            $this->result['track'] = $objTrack;
            UI::SmartyTemplate('trackimporter.html', $this->result);
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
            if (isset($this->arrUri['parameters']['strShowName']) and $this->arrUri['parameters']['strShowName'] != '') {
                $showName = $this->arrUri['parameters']['strShowName'];
            }
            $intShowID = new NewExternalShowObject($showUrl, $showName);
            UI::Redirect('admin/show/' . $intShowID);
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
            if (isset($this->arrUri['parameters']['strTrackName_preferred']) and $this->arrUri['parameters']['strTrackName_preferred'] != '') {
                $objTrack->setpreferred_strTrackName($this->arrUri['parameters']['strTrackName_preferred']);
            }
            if (isset($this->arrUri['parameters']['strTrackName']) and $this->arrUri['parameters']['strTrackName'] != '') {
                $objTrack->set_strTrackName($this->arrUri['parameters']['strTrackName']);
            }
            if (isset($this->arrUri['parameters']['del_strTrackName']) and $this->arrUri['parameters']['del_strTrackName'] != '') {
                $objTrack->del_strTrackName($this->arrUri['parameters']['del_strTrackName']);
            }
            if (isset($this->arrUri['parameters']['strTrackNameSounds']) and $this->arrUri['parameters']['strTrackNameSounds'] != '') {
                $objTrack->set_strTrackNameSounds($this->arrUri['parameters']['strTrackNameSounds']);
            }
            if (isset($this->arrUri['parameters']['strTrackUrl_preferred']) and $this->arrUri['parameters']['strTrackUrl_preferred'] != '') {
                $objTrack->setpreferred_strTrackUrl($this->arrUri['parameters']['strTrackUrl_preferred']);
            }
            if (isset($this->arrUri['parameters']['strTrackUrl']) and $this->arrUri['parameters']['strTrackUrl'] != '') {
                $objTrack->set_strTrackUrl($this->arrUri['parameters']['strTrackUrl']);
            }
            if (isset($this->arrUri['parameters']['del_strTrackUrl']) and $this->arrUri['parameters']['del_strTrackUrl'] != '') {
                $objTrack->del_strTrackUrl($this->arrUri['parameters']['del_strTrackUrl']);
            }
            if (isset($this->arrUri['parameters']['strArtistName_preferred']) and $this->arrUri['parameters']['strArtistName_preferred'] != '') {
                $objTrack->get_objArtist()->setpreferred_strArtistName($this->arrUri['parameters']['strArtistName_preferred']);
            }
            if (isset($this->arrUri['parameters']['strArtistName']) and $this->arrUri['parameters']['strArtistName'] != '') {
                $objTrack->get_objArtist()->set_strArtistName($this->arrUri['parameters']['strArtistName']);
            }
            if (isset($this->arrUri['parameters']['del_strArtistName']) and $this->arrUri['parameters']['del_strArtistName'] != '') {
                $objTrack->get_objArtist()->del_strArtistName($this->arrUri['parameters']['del_strArtistName']);
            }
            if (isset($this->arrUri['parameters']['strArtistNameSounds']) and $this->arrUri['parameters']['strArtistNameSounds'] != '') {
                $objTrack->get_objArtist()->set_strArtistNameSounds($this->arrUri['parameters']['strArtistNameSounds']);
            }
            if (isset($this->arrUri['parameters']['strArtistUrl_preferred']) and $this->arrUri['parameters']['strArtistUrl_preferred'] != '') {
                $objTrack->get_objArtist()->setpreferred_strArtistUrl($this->arrUri['parameters']['strArtistUrl_preferred']);
            }
            if (isset($this->arrUri['parameters']['strArtistUrl']) and $this->arrUri['parameters']['strArtistUrl'] != '') {
                $objTrack->get_objArtist()->set_strArtistUrl($this->arrUri['parameters']['strArtistUrl']);
            }
            if (isset($this->arrUri['parameters']['del_strArtistUrl']) and $this->arrUri['parameters']['del_strArtistUrl'] != '') {
                $objTrack->get_objArtist()->del_strArtistUrl($this->arrUri['parameters']['del_strArtistUrl']);
            }
            if (isset($this->arrUri['parameters']['approved'])) {
                $objTrack->set_isApproved($this->asBoolean($this->arrUri['parameters']['approved']));
            }
            if (isset($this->arrUri['parameters']['nsfw'])) {
                $objTrack->set_isNSFW($this->arrUri['parameters']['nsfw']);
            }
            if (isset($this->arrUri['parameters']['duplicate'])) {
                $objTrack->set_intDuplicateID($this->arrUri['parameters']['duplicate']);
            }
            $objTrack->get_objArtist()->write();
            $objTrack->write();

            $this->result['track'] = $objTrack->getSelf();
            UI::SmartyTemplate('trackeditor.html', $this->result);
        }
    }

    /**
     * Edit an existing Show.
     * TODO: Write HTML::editShow() function
     *
     * @param object $objShow The show object
     *
     * @return void
     */
    protected function editShow($objShow = null)
    {

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
        if (isset($this->arrUri['parameters']['strUsername']) and isset($this->arrUri['parameters']['strPassword']) and $this->arrUri['parameters']['strUsername'] != '' and $this->arrUri['parameters']['strPassword'] != '' ) {
            $newCredentials = "{$this->arrUri['parameters']['strUsername']}:" . sha1($this->arrUri['parameters']['strPassword']);
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
        $this->result['chart'] = ChartBroker::getChartByDate('', 0, 15);
        $this->result['daily'] = end(ShowBroker::getInternalShowByType('daily', 1))->getSelf();
        $this->result['weekly'] = end(ShowBroker::getInternalShowByType('weekly', 1))->getSelf();
        $this->result['monthly'] = end(ShowBroker::getInternalShowByType('monthly', 1))->getSelf();
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
                    // TODO: Write track.rss.tpl
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
            new NewVoteObject(UI::getLongNumber($track), UI::getLongNumber($show));
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
            if ( ! array_key_exists(TrackBroker::getTotalTracks(), $this->result['chart'])) {
                $this->result['next_page'] = true;
            }
            // TODO: Write chart.rss.tpl
            UI::SmartyTemplate("chart.{$this->format}", $this->result);
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
            $this->result['jquerysparkline'] = $this->extLib->getVersion('JQUERY.SPARKLINE');
            $this->result['previous_page'] = false;
            UI::SmartyTemplate("about.html", $this->result);
            break;
        }
    }

    /**
     * Return an export of the whole database. Yehr, I know it's using MySQL libraries, rather than PDO, but frankly, I couldn't figure out how to do this in PDO.
     *
     * @return void
     */
    protected function database_export()
    {
        set_time_limit(0);
        header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename="cchits.' . date("Y-m-d_Hi") . '.sql"');

        echo "/* This DATABASE and it's DATA is made available under a Creative Commons Zero license: http://creativecommons.org/publicdomain/zero/1.0/ */". "\r\n\r\n";

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
                                } elseif ($key == 'value' and ($last_val == 'CronTab User' or $last_val == 'CronTab Pass')) {
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
            $this->result['jplayer'] = $this->extLib->getVersion('JPLAYER');
            $this->result['jquerysparkline'] = $this->extLib->getVersion('JQUERY.SPARKLINE');
            $this->result['previous_page'] = false;
            if (isset($this->arrUri['parameters']['page']) and $this->arrUri['parameters']['page'] > 0) {
                $this->result['previous_page'] = true;
            }
            $this->result['next_page'] = false;
            $this->result['ShowDaily'] = ConfigBroker::getConfig('Daily Show Name', 'Daily Exposure Show');
            $this->result['ShowWeekly'] = ConfigBroker::getConfig('Weekly Show Name', 'Weekly Review Show');
            $this->result['ShowMonthly'] = ConfigBroker::getConfig('Monthly Show Name', 'Monthly Chart Show');
            return true;
        case 'rss':
            $this->result['feedName'] = ConfigBroker::getConfig('Site Name', 'CCHits.net');
            $this->result['feedDescription'] = ConfigBroker::getConfig('About The Site', 'CCHits.net is designed to provide a Chart for Creative Commons Music, in a way that is easily able to be integrated into other music shows that play Creative Commons Music. CCHits.net has a daily exposure podcast, playing one new track every day, a weekly podcast, playing the last week of tracks played on the podcast, plus the top rated three tracks from the previous week. There is also a monthly podcast which features the top rated tracks over the whole system.');
            $this->result['feedWhen'] = $this->arrUri['path_items'][0];
            $this->result['showsLink'] = $this->arrUri['basePath'] . $this->arrUri['path_items'][0];
            $this->result['feedLink'] = $this->arrUri['basePath'] . $this->arrUri['path_items'][0] . '/rss';
            $this->result['siteCopyright'] = ConfigBroker::getConfig('System License', 'The content created by this site is generated by a script which is licensed under the Affero General Public License version 3 (AGPL3). The generated content is released under a Creative Commons By-Attribution License.');
            $this->result['feedDate'] = date('r', strtotime(date('Y-m-d') . ' 00:00:00'));
            $this->result['baseURL'] = $this->arrUri['basePath'];
            $this->result['feedOwner'] = ConfigBroker::getConfig('Contact EMail', 'show@cchits.net') . ' (' . ConfigBroker::getConfig('Contact Name', 'CCHits.net Show Admin') . ')';
            $this->result['contactName'] = ConfigBroker::getConfig('Contact Name', 'CCHits.net Show Admin');
            $this->result['contactEmail'] = ConfigBroker::getConfig('Contact EMail', 'show@cchits.net');
            return true;
        default:
            UI::sendHttpResponse(404);
        }
    }
}

