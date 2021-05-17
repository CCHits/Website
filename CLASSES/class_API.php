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
 * This class handles all API calls
 *
 * @category Default
 * @package  UI
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */

class API
{
    protected $result = null; // An object for rendering
    protected $result_array = null; // An array of objects for rendering
    protected $result_list = null; // An array for rendering
    protected $response_code = 200;
    protected $format = 'json';

    /**
     * The function which handles the API calls
     *
     * @return void
     */
    function __construct()
    {
        $arrUri = UI::getUri();
        if (GeneralFunctions::getValue(GeneralFunctions::getValue($arrUri, 'path_items', array()), 0, '') != 'api') {
            throw new API_NotApiCall();
        } else {
            header("Access-Control-Allow-Origin: *");
            switch($arrUri['format']) {
            case 'xml':
                $this->format = 'xml';
                break;
            case 'shell':
                $this->format = 'shell';
                break;
            case 'html':
                $this->format = 'html';
                break;
            }
            switch($arrUri['path_items'][1]) {
            // Diagnostic Calls
            case 'echo':
                $this->result_array = $arrUri;
                $this->render();
                break;
            case 'echologin':
                UI::requireAuth();
                $this->result_array = $arrUri;
                $this->render();
                break;
            // User Calls
            case 'getstatus':
                $objUser = UserBroker::getUser();
                $this->result_array = array(
                    'isUploader' => $objUser->get_isUploader(),
                    'isAuthorized' => $objUser->get_isAuthorized(),
                    'isAdmin' => $objUser->get_isAdmin()
                );
                $this->render();
                break;
            // Searches
            case 'searchartistbyname':
            case 'searchartistsbyname':
                $artist_name = GeneralFunctions::getValue($arrUri['parameters'], 'strArtistName', '', true);
                if ($artist_name == '' && GeneralFunctions::getValue($arrUri['path_items'], 2, false, true)) {
                    for ($arrItem = 2; $arrItem <= count($arrUri['path_items']); $arrItem++) {
                        if (isset($arrUri['path_items'][$arrItem])) {
                            if ($artist_name != '') {
                                $artist_name .= '/';
                            }
                            $artist_name .= urldecode($arrUri['path_items'][$arrItem]);
                        }
                    }
                }
            case 'listartist':
            case 'listartists':
                if (!isset($artist_name)) {
                    $artist_name = '';
                }
                $this->result_array = ArtistBroker::getArtistByPartialName($artist_name);
                $this->render();
                break;
            case 'searchartistbyurl':
            case 'searchartistsbyurl':
                $artist_url = GeneralFunctions::getValue($arrUri['parameters'], 'strArtistUrl', '', true);
                if ($artist_url == '' && GeneralFunctions::getValue($arrUri['path_items'], 2, false, true)) {
                    for ($arrItem = 2; $arrItem <= count($arrUri['path_items']); $arrItem++) {
                        if (isset($arrUri['path_items'][$arrItem])) {
                            if ($artist_url != '') {
                                $artist_url .= '/';
                            }
                            $artist_url .= urldecode($arrUri['path_items'][$arrItem]);
                        }
                    }
                }
                if ($artist_url == '') {
                    $this->render();
                    break;
                }
                $this->result_array = ArtistBroker::getArtistByPartialUrl($artist_url);
                $this->render();
                break;
            case 'searchtrackbyname':
            case 'searchtracksbyname':
                $track_name = GeneralFunctions::getValue($arrUri['parameters'], 'strTrackName', '', true);
                if ($track_name == '' && GeneralFunctions::getValue($arrUri['path_items'], 2, false, true)) {
                    for ($arrItem = 2; $arrItem <= count($arrUri['path_items']); $arrItem++) {
                        if (isset($arrUri['path_items'][$arrItem])) {
                            if ($track_name != '') {
                                $track_name .= '/';
                            }
                            $track_name .= urldecode($arrUri['path_items'][$arrItem]);
                        }
                    }
                }
            case 'listtrack':
            case 'listtracks':
                if (!isset($track_name)) {
                    $track_name = '';
                }
                $result_array = TrackBroker::getTrackByPartialName($track_name);
                foreach ($result_array as $result) {
                    $result->set_full(true);
                    $this->result_array[] = $result;
                }
                $this->render();
                break;
            case 'searchtrackbyurl':
            case 'searchtracksbyurl':
                $track_url = GeneralFunctions::getValue($arrUri['parameters'], 'strTrackUrl', '', true);
                if ($track_url == '' && GeneralFunctions::getValue($arrUri['path_items'], 2, false, true)) {
                    for ($arrItem = 2; $arrItem <= count($arrUri['path_items']); $arrItem++) {
                        if (isset($arrUri['path_items'][$arrItem])) {
                            if ($track_url != '') {
                                $track_url .= '/';
                            }
                            $track_url .= urldecode($arrUri['path_items'][$arrItem]);
                        }
                    }
                }
                if ($track_url == '') {
                    $this->render();
                    break;
                }
                $result_array = TrackBroker::getTrackByPartialUrl($track_url);
                foreach ($result_array as $result) {
                    $result->set_full(true);
                    $this->result_array[] = $result;
                }
                $this->render();
                break;
            case 'searchshowbyname':
            case 'searchshowsbyname':
                $show_name = GeneralFunctions::getValue($arrUri['parameters'], 'strShowName', '', true);
                if ($show_name == '' && GeneralFunctions::getValue($arrUri['path_items'], 2, false, true)) {
                    for ($arrItem = 2; $arrItem <= count($arrUri['path_items']); $arrItem++) {
                        if (isset($arrUri['path_items'][$arrItem])) {
                            if ($show_name != '') {
                                $show_name .= '/';
                            }
                            $show_name .= urldecode($arrUri['path_items'][$arrItem]);
                        }
                    }
                }
            case 'listshow':
            case 'listshows':
                if (!isset($show_name)) {
                    $show_name = '';
                }
                $this->result_array = ShowBroker::getShowByPartialName($show_name);
                $this->render();
                break;
            case 'searchshowbyurl':
            case 'searchshowsbyurl':
                $show_url = GeneralFunctions::getValue($arrUri['parameters'], 'strShowUrl', '', true);
                if ($show_url == '' && GeneralFunctions::getValue($arrUri['path_items'], 2, false, true)) {
                    for ($arrItem = 2; $arrItem <= count($arrUri['path_items']); $arrItem++) {
                        if (isset($arrUri['path_items'][$arrItem])) {
                            if ($show_url != '') {
                                $show_url .= '/';
                            }
                            $show_url .= urldecode($arrUri['path_items'][$arrItem]);
                        }
                    }
                }
                if ($show_url == '') {
                    $this->render();
                    break;
                }
                $this->result_array = ShowBroker::getShowByPartialUrl($show_url);
                $this->render();
                break;
            // Direct Lookups
            case 'gettrack':
                $intTrackID = GeneralFunctions::getValue(
                    $arrUri['parameters'], 'intTrackID', GeneralFunctions::getValue(
                        $arrUri['path_items'], 2, 0, true
                    ), true
                );
                $this->result = TrackBroker::getTrackByID(UI::getLongNumber($intTrackID));
                $this->result->set_full(true);
                $this->render();
                break;
            case 'getshow':
                $intShowID = GeneralFunctions::getValue(
                    $arrUri['parameters'], 'intShowID', GeneralFunctions::getValue(
                        $arrUri['path_items'], 2, 0, true
                    ), true
                );
                $this->result = ShowBroker::getShowByID(UI::getLongNumber($intShowID));
                $this->render();
                break;

            // Upload Scripts
            case 'addtracktoshow':
                $intTrackID = GeneralFunctions::getValue(
                    $arrUri['parameters'], 'intTrackID', GeneralFunctions::getValue(
                        $arrUri['path_items'], 2, 0, true
                    ), true
                );
                $intShowID = GeneralFunctions::getValue(
                    $arrUri['parameters'], 'intShowID', GeneralFunctions::getValue(
                        $arrUri['path_items'], 3, 0, true
                    ), true
                );
                if ($intShowID == 0) {
                    $show_url = GeneralFunctions::getValue($arrUri['parameters'], 'strShowUrl', '', true);
                    if ($show_url == '' && GeneralFunctions::getValue($arrUri['path_items'], 2, false, true)) {
                        for ($arrItem = 2; $arrItem <= count($arrUri['path_items']); $arrItem++) {
                            if (isset($arrUri['path_items'][$arrItem])) {
                                if ($show_url != '') {
                                    $show_url .= '/';
                                }
                                $show_url .= $arrUri['path_items'][$arrItem];
                            }
                        }
                    }
                    $show = ShowBroker::getShowByExactUrl($show_url);
                    if ($show == false) {
                        $show_name = GeneralFunctions::getValue($arrUri['parameters'], 'strShowName', $show_url, true);
                        $show = new NewExternalShowObject($show_url, $show_name);
                    }
                    if (is_object($show)) {
                        $intShowID = $show->get_intShowID();
                    }
                }
                if ($intTrackID != 0 and $intShowID != 0) {
                    $this->result = new NewShowTrackObject($intTrackID, $intShowID);
                } else {
                    $this->result = false;
                }
                $this->render();
                break;
            case 'newtrack':
            case 'pulltrack':
                $this->result = RemoteSourcesBroker::newTrackRouter($arrUri['parameters']['strTrackUrl']);
                if (is_array($this->result) and count($this->result) == 1) {
                    foreach ($this->result as $key=>$value) {
                        // Get the last key/value pair
                    }
                    if ($value == true) {
                        $track = TrackBroker::getTrackByID($key);
                        $track->amendRecord();
                        $artist = ArtistBroker::getArtistByID($track->get_intArtistID());
                        $artist->amendRecord();
                        $this->result = array('intTrackID' => $key);
                    } else {
                        $track = RemoteSourcesBroker::getRemoteSourceByID($key);
                        $track->amendRecord();
                        $this->result = array('intProcessingID' => $key);
                    }
                }
                $this->render();
                break;
            case 'completetrack':
                $intTrackID = GeneralFunctions::getValue(
                    $arrUri['parameters'], 'intTrackID', GeneralFunctions::getValue(
                        $arrUri['path_items'], 2, 0, true
                    ), true
                );
                $objSource = RemoteSourcesBroker::getRemoteSourceByID($intTrackID);
                if ($objSource == false) {
                    $this->render();
                    break;
                }
                try{
                    $objSource->amendRecord();
                    $this->result_array = array('intTrackID'=>$objSource->get_intTrackID());
                } catch (Exception $e) {
                    $this->result_array = array('Incomplete'=>$e->getMessage());
                    if (isset($objSource->duplicateTracks)) {
                        $this->result['DuplicateTracks'] = $objSource->duplicateTracks;
                    }
                }
                $this->render();
                break;
            case 'getshowid':
                $show_url = GeneralFunctions::getValue($arrUri['parameters'], 'strShowUrl', '', true);
                if ($show_url == '' && GeneralFunctions::getValue($arrUri['path_items'], 2, false, true)) {
                    for ($arrItem = 2; $arrItem <= count($arrUri['path_items']); $arrItem++) {
                        if (isset($arrUri['path_items'][$arrItem])) {
                            if ($show_url != '') {
                                $show_url .= '/';
                            }
                            $show_url .= $arrUri['path_items'][$arrItem];
                        }
                    }
                }
                if ($show_url == '') {
                    $this->render();
                    break;
                }
                $this->result = ShowBroker::getShowByExactUrl($show_url);
                if ($this->result == false) {
                    $show_name = GeneralFunctions::getValue($arrUri['parameters'], 'strShowName', $show_url, true);
                    $this->result = new NewExternalShowObject($show_url, $show_name);
                }
                $this->render();
                break;
            case 'editshow':
                $show_url = GeneralFunctions::getValue($arrUri['parameters'], 'strShowUrl', '', true);
                $show_name = GeneralFunctions::getValue($arrUri['parameters'], 'strShowName', $show_url, true);
                $show = ShowBroker::getShowByID(
                    GeneralFunctions::getValue($arrUri['parameters'], 'intShowID', false, true)
                );
                if ($show != false) {
                    $show->set_strShowUrl($show_url);
                    $show->set_strShowName($show_name);
                    $show->write();
                    UI::sendHttpResponseNote(200);
                } else {
                    $this->render();
                }
                break;

            // Get Statistical Information
            case 'gettrends':
                $strTrendDate = GeneralFunctions::getValue(
                    $arrUri['parameters'], 'strTrendDate', GeneralFunctions::getValue(
                        $arrUri['path_items'], 2, date('Y-m-d'), true
                    ), true
                );
                $this->result_array = TrendBroker::getTrendByDate($strTrendDate);
                $this->render();
                break;
            case 'getchart':
                $strChartDate = GeneralFunctions::getValue(
                    $arrUri['parameters'], 'strChartDate', GeneralFunctions::getValue(
                        $arrUri['path_items'], 2, date('Y-m-d'), true
                    ), true
                );
                $this->result_array = ChartBroker::getChartByDate($strChartDate);
                $this->render();
                break;

            // Voting
            case 'vote':
                $intTrackID = GeneralFunctions::getValue(
                    $arrUri['parameters'], 'intTrackID', GeneralFunctions::getValue(
                        $arrUri['path_items'], 2, 0, true
                    ), true
                );
                $intShowID = GeneralFunctions::getValue(
                    $arrUri['parameters'], 'intShowID', GeneralFunctions::getValue(
                        $arrUri['path_items'], 3, 0, true
                    ), true
                );
                $this->result = new NewVoteObject($intTrackID, $intShowID);
                $this->render();
                break;

            // Generate show information
            // These functions are new
            case 'runshows':
                UI::requireAuth();
                if (UserBroker::getUser()->get_isAdmin()) {
                    $date = GeneralFunctions::getValue(
                        $arrUri['parameters'], 'date', GeneralFunctions::getValue(
                            $arrUri['path_items'], 2, date('Ymd'), true
                        ), true
                    );
                    if ($date == '' || strtotime(UI::getLongDate($date)) === false) {
                        $date = date('Ymd');
                    }
                    $temp = new ChartObject($date);
                    $temp = ShowBroker::getInternalShowByDate('daily', $date);
                    if ($temp == false and ! isset($arrUri['parameters']['historic'])) {
                        $temp = new NewDailyShowObject($date);
                    }
                    if ($temp != false) {
                        if ($this->format == 'shell') {
                            $this->result_list = array('daily_show' => $temp->get_intShowID());
                        } else {
                            $this->result_array = array('daily_show' => $temp);
                        }
                    }
                    if (7 == date('N', strtotime(UI::getLongDate($date) . ' 12:00:00'))) {
                        $temp = ShowBroker::getInternalShowByDate('weekly', $date);
                        if ($temp == false  and ! isset($arrUri['parameters']['historic'])) {
                            $temp = new NewWeeklyShowObject($date);
                        }
                        if ($temp != false) {
                            if ($this->format == 'shell') {
                                $this->result_list['weekly_show'] = $temp->get_intShowID();
                            } else {
                                $this->result_array['weekly_show'] = $temp;
                            }
                        }
                    }
                    if (1 == date('d', strtotime(UI::getLongDate($date) . ' 12:00:00 + 1 day'))) {
                        $temp = ShowBroker::getInternalShowByDate('monthly', substr($date, 0, 6));
                        if ($temp == false and ! isset($arrUri['parameters']['historic'])) {
                            $temp = new NewMonthlyShowObject(substr($date, 0, 6));
                        }
                        if ($temp != false) {
                            if ($this->format == 'shell') {
                                $this->result_list['monthly_show'] = $temp->get_intShowID();
                            } else {
                                $this->result_array['monthly_show'] = $temp;
                            }
                        }
                    }
                    $this->render();
                    exit(0);
                } else {
                    $this->render();
                }

                // Finish the show generation
            case 'finalize':
            case 'finalise':
                if (isset($arrUri['path_items'][2]) and 0 + $arrUri['path_items'][2] > 0) {
                    $this->result = false;
                    $show = ShowBroker::getShowByID($arrUri['path_items'][2]);
                    if ($show == false) {
                        $this->render();
                    }
                    if (isset($arrUri['parameters']['hash']) and $arrUri['parameters']['hash'] != '') {
                        $this->result = true;
                        $show->set_shaHash($arrUri['parameters']['hash']);
                    }
                    if (isset($arrUri['parameters']['time']) and $arrUri['parameters']['time'] != '') {
                        $this->result = true;
                        $show->set_timeLength($arrUri['parameters']['time']);
                    }
                    if (isset($arrUri['parameters']['comment']) and $arrUri['parameters']['comment'] != '') {
                        $this->result = true;
                        $show->set_strCommentUrl($arrUri['parameters']['comment']);
                    }
                    if (isset($arrUri['parameters']['jsonAudioLayout']) 
                        and $arrUri['parameters']['jsonAudioLayout'] != ''
                    ) {
                        $this->result = true;
                        $show->set_jsonAudioLayout($arrUri['parameters']['jsonAudioLayout']);
                    }
                    if (isset($arrUri['parameters']['_FILES']) and $arrUri['parameters']['_FILES'] != null) {
                        $this->result = true;
                        $show->storeFiles($arrUri['parameters']['_FILES']);
                    }
                    if ($this->result == true) {
                        $show->write();
                    } else {
                        UI::sendHttpResponse(417);
                    }
                    $this->render();
                }
                break;
            case 'split':
                if (isset($arrUri['path_items'][2]) and 0 + $arrUri['path_items'][2] > 0) {
                    $this->result = false;
                    $show = ShowBroker::getShowByID($arrUri['path_items'][2]);
                    if ($show == false) {
                        $this->render();
                    }
                    if (isset($arrUri['parameters']['hash']) and $arrUri['parameters']['hash'] != '') {
                        $this->result = true;
                        $show->set_shaHash($arrUri['parameters']['hash']);
                    }
                    if (isset($arrUri['parameters']['time']) and $arrUri['parameters']['time'] != '') {
                        $this->result = true;
                        $show->set_timeLength($arrUri['parameters']['time']);
                    }
                    if (isset($arrUri['parameters']['comment']) and $arrUri['parameters']['comment'] != '') {
                        $this->result = true;
                        $show->set_strCommentUrl($arrUri['parameters']['comment']);
                    }
                    if (isset($arrUri['parameters']['json_layout']) and $arrUri['parameters']['json_layout'] != '') {
                        $this->result = true;
                        $show->set_jsonLayout($arrUri['parameters']['json_layout']);
                    }
                    if (isset($arrUri['parameters']['_FILES']) and $arrUri['parameters']['_FILES'] != null) {
                        $this->result = true;
                        $show->storeSplitFiles(
                            $arrUri['parameters']['_FILES'], 
                            $arrUri['parameters']['part'],
                            $arrUri['parameters']['split']
                        );
                    }
                    if ($this->result == true) {
                        $show->write();
                    } else {
                        UI::sendHttpResponse(417);
                    }
                    $this->render();
                }
            case 'getunplayedtracks':
                $temp = TracksBroker::getUnplayedTracks();
                if ($temp != false) {
                    foreach ($temp as $objTrack) {
                        $arrTrack = $objTrack->getSelf();
                        $this->result_list[$arrTrack['intTrackID']] 
                            = $arrTrack['strTrackName'] . ' by ' . $arrTrack['strArtistName'];
                    }
                }
                $this->render();
                break;
            case 'stats':
                $temp = StatsBroker::getStats()->getSelf();
                $this->result_array = $temp;
                $this->render();
                break;
            case 'v2':
                switch($arrUri['path_items'][2]) {
                case 'dates':
                    $plusdays = GeneralFunctions::getValue($arrUri['parameters'], 'plusdays', '0', true);
                    $this->result_array = APIv2::getDates($plusdays);
                    $this->render();
                    break;
                case 'newchart':
                    $dates = APIv2::getDates();
                    $weeks = GeneralFunctions::getValue($arrUri['parameters'], 'weeks', '4', true);
                    $date = GeneralFunctions::getValue($arrUri['parameters'], 'date', $dates['Today'], true);
                    $yearweek = APIv2::getYearWeek($date);
                    $this->result_array = APIv2::getNewChart($yearweek, $weeks);
                    $this->render();
                    break;
                case 'getchart':
                    $strChartDate = GeneralFunctions::getValue(
                        $arrUri['parameters'], 'strChartDate', GeneralFunctions::getValue(
                            $arrUri['path_items'], 3, date('Y-m-d'), true
                        ), true
                    );
                    $this->result_array = ChartBroker::getLightChartByDate($strChartDate);
                    $this->render();
                    break;
                default:
                    throw new API_NotApiCall();
                }
                break;
            default:
                throw new API_NotApiCall();
            }
        }
    }

    /**
     * Render
     *
     * @return void
     */
    protected function render()
    {
        switch($this->format) {
        case 'html':
            if (is_object($this->result)) {
                $content = "<table>";
                foreach ($this->result->getSelf() as $key => $value) {
                    if (is_array($value)) {
                        $value = UI::utf8json($value);
                    }
                    $content .= "<tr><td>$key</td><td>$value</td></tr>";
                }
                $content .= "</table>";
                UI::sendHttpResponse(200, null, 'text/html', $content);
            } elseif (is_array($this->result_list)) {
                $content = '<table>';
                foreach ($this->result_list as $key => $value) {
                    $content .= "<tr><td>$key</td><td>$value</td></tr>";
                }
                $content .= "</table>";
                UI::sendHttpResponse(200, null, 'text/html', $content);
            } elseif (is_array($this->result_array)) {
                $content = '';
                foreach ($this->result_array as $result_key => $result_item) {
                    $content .= "<h1>$result_key</h1><table>";
                    if (is_object($result_item)) {
                        $result_item = $result_item->getSelf();
                    }
                    foreach ($result_item as $key=>$value) {
                        if (is_array($value)) {
                            $value = UI::utf8json($value);
                        }
                        $content .= "<tr><td>$key</td><td>$value</td></tr>";
                    }
                    $content .= "</table><br />";
                }
                UI::sendHttpResponse(200, null, 'text/html', $content);
            } elseif ($this->result == true) {
                UI::sendHttpResponse(200, "OK");
            } elseif (0 + $this->result > 0) {
                UI::sendHttpResponse($this->result);
            } else {
                UI::sendHttpResponse(404);
            }
            break;
        case 'json':
            if (is_object($this->result)) {
                UI::sendHttpResponse(200, UI::utf8json($this->result->getSelf()), 'application/json');
            } elseif (is_array($this->result_list)) {
                UI::sendHttpResponse(200, UI::utf8json($this->result_list), 'application/json');
            } elseif (is_array($this->result_array)) {
                foreach ($this->result_array as $result_key => $result_item) {
                    if (is_object($result_item)) {
                        $result_item = $result_item->getSelf();
                    }
                    $result[$result_key] = $result_item;
                }
                UI::sendHttpResponse(200, UI::utf8json($result), 'application/json');
            } elseif ($this->result == true) {
                UI::sendHttpResponse(200, json_encode("OK"), 'application/json');
            } elseif (0 + $this->result > 0 and UI::returnHttpResponseString($this->result) != false) {
                UI::sendHttpResponse(
                    $this->result, json_encode(
                        array('Status'=>UI::returnHttpResponseString($this->result))
                    ), 'application/json'
                );
            } else {
                list($uri, $data) = UI::getPath();
                UI::sendHttpResponse(
                    404, json_encode(
                        array('Error'=>'The requested URL ' . $uri . ' was not found.')
                    ), 'application/json'
                );
            }
            break;
        case 'shell':
            if (is_object($this->result)) {
                $return = '';
                foreach ($this->result->getSelf() as $key=>$value) {
                    if (is_array($value)) {
                        foreach ($value as $v_key=>$v_value) {
                            if ($return != '') {
                                $return .= " && ";
                            }
                            $return .= "{$v_key}=\"$v_value\"";
                        }
                    } else {
                        if ($return != '') {
                            $return .= " && ";
                        }
                        $return .= "{$key}=\"$value\"";
                    }
                }
                UI::sendHttpResponse(200, $return, 'text/plain');
            } elseif (is_array($this->result_list)) {
                $return = '';
                foreach ($this->result_list as $key=>$value) {
                    if (is_array($value)) {
                        foreach ($value as $v_key=>$v_value) {
                            if ($return != '') {
                                $return .= " && ";
                            }
                            $return .= "{$v_key}=\"$v_value\"";
                        }
                    } else {
                        if ($return != '') {
                            $return .= " && ";
                        }
                        $return .= "{$key}=\"$value\"";
                    }
                }
                UI::sendHttpResponse(200, $return, 'text/plain');
            } elseif (is_array($this->result_array)) {
                $return = '';
                $key_inc = 0;
                foreach ($this->result_array as $result_key => $result_item) {
                    $key_inc++;
                    if (is_object($result_item)) {
                        $result_item = $result_item->getSelf();
                    }
                    foreach ($result_item as $key => $value) {
                        if (is_array($value)) {
                            foreach ($value as $v_key => $v_value) {
                                if ($return != '') {
                                    $return .= " && ";
                                }
                                $return .= "{$v_key}_{$key_inc}=\"$v_value\"";
                            }
                        } else {
                            if ($return != '') {
                                $return .= " && ";
                            }
                            $return .= "{$key}_{$key_inc}=\"$value\"";
                        }
                    }
                }
                UI::sendHttpResponse(200, $return, 'text/plain');
            } elseif ($this->result == true) {
                UI::sendHttpResponse(200, "state=OK");
            } elseif (0 + $this->result > 0) {
                UI::sendHttpResponse(
                    $this->result, "Status=\"" . UI::returnHttpResponseString($this->result) . "\"", 'text/plain'
                );
            } else {
                list($uri, $data) = UI::getPath();
                UI::sendHttpResponse(404, "Error=\"The requested URL ' . $uri . ' was not found.\"", 'text/plain');
            }
            break;
        case 'xml':
            // Not yet supported :(
        default:
            UI::sendHttpResponse(500);
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
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */
class API_NotApiCall extends CustomException
{
    protected $message = "This is not an API call. Please see https:\/\/github.com\/CCHits\/Website\/wiki\/Using-the-API for details on the API";
    protected $code    = 255;
}
