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
                $this->track($object[1]);
                break;
            case 'show':
            case 's':
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
                }
                $this->daily($object[1]);
                break;
            case 'weekly':
                if (isset($this->arrUri['path_items'][1]) and $this->arrUri['path_items'][1] == 'rss') {
                    $this->format = 'rss';
                    $this->arrUri['path_items'][1] = $this->arrUri['path_items'][2];
                }
                $this->weekly($object[1]);
                break;
            case 'monthly':
                if (isset($this->arrUri['path_items'][1]) and $this->arrUri['path_items'][1] == 'rss') {
                    $this->format = 'rss';
                    $this->arrUri['path_items'][1] = $this->arrUri['path_items'][2];
                }
                $this->monthly($object[1]);
                break;
            case 'about':
                $this->about($object[1]);
                break;
            default:
                $this->reset_page();
            }
        }
    }

    /**
     * Force the user back to the home page
     *
     * @return void
     */
    protected function reset_page()
    {
        UI::Redirect('', false);
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
                UI::SmartyTemplate("vote.{$this->format}", $this->result);
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
        $this->result['chart'] = ChartBroker::getChartByDate($date);
        if ($this->render()) {
            if (isset($this->arrUri['parameters']['page']) and $this->arrUri['parameters']['page'] > 0) {
                $this->result['previous_page'] = true;
            }
            if ( ! array_key_exists(TrackBroker::getTotalTracks(), $this->result['chart'])) {
                $this->result['next_page'] = true;
            }
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
        }
        if ($this->render()) {
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
        }
        if ($this->render()) {
            // FIXME: This keeps returning array instead of a playlist.
            foreach ($this->result['shows'] as $intShowID=>$show) {
                $this->result['show_playlists'][$intShowID] = json_encode(array($show['player_data']));
            }
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
        }
        if ($this->render()) {
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
            UI::SmartyTemplate("about.{$this->format}", $this->result);
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
        case 'rss':
            return true;
        default:
            UI::sendHttpResponse(404);
        }
    }
}

