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
    protected $result = null;
    protected $response_code = 200;
    protected $format = 'html';

    /**
     * The function which handles the routing
     *
     * @return void
     */
    function __construct()
    {
        $extLib = new ExternalLibraryLoader();
        $arrUri = UI::getUri();
        $this->result = array(
            'ServiceName'=>ConfigBroker::getConfig('ServiceName', 'CCHits'),
            'Slogan'=>ConfigBroker::getConfig('Slogan', 'Where you make the charts'),
            'baseURL'=>$arrUri['basePath'],
            'jquery'=>$extLib->getVersion('JQUERY'),
            'jplayer'=>$extLib->getVersion('JPLAYER')
        );

        if (is_array($arrUri)
            and isset($arrUri['path_items'])
            and is_array($arrUri['path_items'])
            and count($arrUri['path_items']) == 0
        ) {
            $this->front_page();
        } else {
            switch($arrUri['format']) {
            case 'xml':
                $this->format = 'xml';
                break;
            case 'json':
                $this->format = 'json';
                break;
            case 'rss':
                switch($arrUri['path_items'][0]) {
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
            if (count($arrUri['path_items']) == 1 and $arrUri['path_items'][0] == '') {
                $this->front_page();
                exit(0);
            }
            $object = array(1 => null, 2 => null);
            if (isset($arrUri['path_items'][1])) {
                $object[1] = $arrUri['path_items'][1];
            }
            if (isset($arrUri['path_items'][2])) {
                $object[2] = $arrUri['path_items'][2];
            }
            switch($arrUri['path_items'][0]) {
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
                if (isset($arrUri['path_items'][1]) and $arrUri['path_items'][1] == 'rss') {
                    $this->format = 'rss';
                    $arrUri['path_items'][1] = $arrUri['path_items'][2];
                }
                $this->daily($object[1]);
                break;
            case 'weekly':
                if (isset($arrUri['path_items'][1]) and $arrUri['path_items'][1] == 'rss') {
                    $this->format = 'rss';
                    $arrUri['path_items'][1] = $arrUri['path_items'][2];
                }
                $this->weekly($object[1]);
                break;
            case 'monthly':
                if (isset($arrUri['path_items'][1]) and $arrUri['path_items'][1] == 'rss') {
                    $this->format = 'rss';
                    $arrUri['path_items'][1] = $arrUri['path_items'][2];
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
        $chart = ChartBroker::getChartByDate('', 0, 15);
        $counter = 0;
        foreach ($chart as $objTrack) {
            $this->result['chart'][++$counter] = $objTrack->getSelf();
        }
        $this->result['daily'] = end(ShowBroker::getInternalShowByType('daily', 1))->getSelf();
        $this->result['weekly'] = end(ShowBroker::getInternalShowByType('weekly', 1))->getSelf();
        $this->result['monthly'] = end(ShowBroker::getInternalShowByType('monthly', 1))->getSelf();
        if ($this->render()) {
            if ($this->format == 'html') {
                $this->result['daily_player_json'] = json_encode(array($this->result['daily']['player_data']));
                $this->result['weekly_player_json'] = json_encode(array($this->result['weekly']['player_data']));
                $this->result['monthly_player_json'] = json_encode(array($this->result['monthly']['player_data']));
                UI::SmartyTemplate("frontpage.html", $this->result);
            } elseif ($this->format == 'rss') {
                // TODO: Write this template
                UI::SmartyTemplate("frontpage.rss", $this->result);
            }
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
            $this->result['track'] = TrackBroker::getTrackByID(UI::getLongNumber($track));
            if ($this->render()) {
                // TODO: Write this template
                UI::SmartyTemplate("track.{$this->format}", $this->result);
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
            $this->result['show'] = ShowBroker::getShowByID(UI::getLongNumber($show));
            switch($this->result['show']->get_enumShowType()) {
            case 'daily':
            case 'weekly':
            case 'monthly':
                UI::Redirect($this->result['show']->get_enumShowType() . '/' . $this->result['show']->get_intShowUrl());
            }
            if ($this->render()) {
                // TODO: Write this template
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
        $arrUri = UI::getUri();
        $this->result['track'] = TrackBroker::getTrackByID(UI::getLongNumber($track));
        if ($show != 0) {
            $this->result['show'] = ShowBroker::getShowByID(UI::getLongNumber($show));
        }
        if ($this->result['track'] != false) {
            if ($this->result['show'] == false or ShowTrackBroker::getShowTracksByShowTrackID($show, $track) == false) {
                $show = 0;
            }
        } else {
            UI::sendHttpResponse(404);
        }
        if (isset($arrUri['parameters']['go']) and new NewVoteObject(UI::getLongNumber($track), UI::getLongNumber($show))) {
            $this->result['vote'] = true;
            if ($this->render()) {
                UI::SmartyTemplate("voted.html", $this->result);
            }
        } else {
            $this->result['vote_url'] = $arrUri['full'] . '?go';
            if ($this->render()) {
                // TODO: Write this template
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
        $arrUri = UI::getUri();
        $page = $arrUri['parameters']['page'];
        if (0 + $page <= 0) {
            $page = 0;
        }
        $size = $arrUri['parameters']['size'];
        if (0 + $size <= 0 or $size > 100) {
            $size = 25;
        }
        $this->result['chart'] = ChartBroker::getChartByDate($date, $page, $size);
        if ($this->render()) {
            // TODO: Write this template
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
        if ($showdate == '') {
            $show = ShowBroker::getInternalShowByDate('daily', $showdate);
            if ($show != false) {
                UI::Redirect('show/' . $show->get_intShowID());
                exit(0);
            }
        }
        $arrUri = UI::getUri();
        $page = $arrUri['parameters']['page'];
        if (0 + $page <= 0) {
            $page = 0;
        }
        $size = $arrUri['parameters']['size'];
        if (0 + $size <= 0 or $size > 100) {
            $size = 25;
        }
        $this->result['shows'] = ShowBroker::getInternalShowByType('daily', $page, $size);
        if ($this->render()) {
            // TODO: Write this template
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
        if ($showdate == '') {
            $show = ShowBroker::getInternalShowByDate('weekly', $showdate);
            if ($show != false) {
                UI::Redirect('show/' . $show->get_intShowID());
                exit(0);
            }
        }
        $arrUri = UI::getUri();
        $page = $arrUri['parameters']['page'];
        if (0 + $page <= 0) {
            $page = 0;
        }
        $size = $arrUri['parameters']['size'];
        if (0 + $size <= 0 or $size > 100) {
            $size = 25;
        }
        $this->result['shows'] = ShowBroker::getInternalShowByType('weekly', $page, $size);
        if ($this->render()) {
            // TODO: Write this template
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
        if ($showdate == '') {
            $show = ShowBroker::getInternalShowByDate('monthly', $showdate);
            if ($show != false) {
                UI::Redirect('show/' . $show->get_intShowID());
                exit(0);
            }
        }
        $arrUri = UI::getUri();
        $page = $arrUri['parameters']['page'];
        if (0 + $page <= 0) {
            $page = 0;
        }
        $size = $arrUri['parameters']['size'];
        if (0 + $size <= 0 or $size > 100) {
            $size = 25;
        }
        $this->result['shows'] = ShowBroker::getInternalShowByType('monthly', $page, $size);
        if ($this->render()) {
            // TODO: Write this template
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
            $arrUri = UI::getUri();
            if (isset($arrUri['parameters']['go'])) {
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
            // TODO: Write this template
            UI::SmartyTemplate("about.{$this->format}", $this->result);
            break;
        }
    }

    /**
     * Return an export of the whole database
     * FIXME: Uses old function calls. Needs rewriting with PDO in mind.
     *
     * @return void
     */
    protected function database_export()
    {
        /* Removed for your own protection.
        header('Content-type: text/plain');
        header('Content-Disposition: attachment; filename="cchits.' . date("Y-m-d_Hi") . '.sql"');

        echo " /* This DATABASE and it's DATA is made available under a Creative Commons Zero license: http://creativecommons.org/publicdomain/zero/1.0/ *" . "/\r\n\r\n";
        $qryTables = sqlCommand("show tables");
        if (mysql_errno() == 0) {
            while ($arrTable = mysql_fetch_row($qryTables)) {
                $qryCreate = sqlCommand("show create table `{$arrTable[0]}`");
                if (mysql_errno() == 0) {
                    if($arrCreate = mysql_fetch_assoc($qryCreate)) {
                        echo $arrCreate['Create Table'] . ";\r\n";
                    }
                }
                $qryData = sqlCommand("SELECT * FROM `{$arrTable[0]}`");
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
                    echo ";\r\n";
                }
            }
        }
        */
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
        case 'rss':
            return true;
        default:
            UI::sendHttpResponse(404);
        }
    }
}

