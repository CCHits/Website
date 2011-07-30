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
 * This class handles all API calls
 *
 * @category Default
 * @package  UI
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class API
{
    protected $result = null;
    protected $result_array = null;
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
        if (is_array($arrUri)
            and isset($arrUri['path_items'])
            and is_array($arrUri['path_items'])
            and count($arrUri['path_items']) > 0
            and $arrUri['path_items'][0] != 'api'
        ) {
            throw new API_NotApiCall();
        } else {
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
                $this->result = $arrUri;
                $this->render();
                break;
            case 'echologin':
                UI::requireAuth();
                $this->result = $arrUri;
                $this->render();
                break;
            // User Calls
            case 'getstatus':
                $objUser = UserBroker::getUser();
                $this->result = array(
                    'isUploader' => $objUser->get_isUploader(),
                    'isAuthorized' => $objUser->get_isAuthorized(),
                    'isAdmin' => $objUser->get_isAdmin()
                );
                $this->render();
                break;
            // Searches
            case 'searchartistbyname':
            case 'searchartistsbyname':
                if (isset($arrUri['parameters']['strArtistName']) and $arrUri['parameters']['strArtistName'] = '') {
                    $artist_name = $arrUri['parameters']['strArtistName'];
                } elseif (isset($arrUri['path_items'][2]) and $arrUri['path_items'][2] != '') {
                    $artist_name = '';
                    for ($arrItem = 2; $arrItem <= count($arrUri['path_items']); $arrItem++) {
                        if (isset ($arrUri['path_items'][$arrItem])) {
                            if ($artist_name != '') {
                                $artist_name .= '/';
                            }
                            $artist_name .= $arrUri['path_items'][$arrItem];
                        }
                    }
                } else {
                    $artist_name = '';
                }
            case 'listartist':
            case 'listartists':
                if (!isset($artist_name)) {
                    $artist_name = '';
                }
                if (isset($arrUri['parameters']['page']) and $arrUri['parameters']['page'] > 0) {
                    $page = $arrUri['parameters']['page'];
                } else {
                    $page = 0;
                }
                if (isset($arrUri['parameters']['size']) and $arrUri['parameters']['size'] > 0) {
                    $size = $arrUri['parameters']['size'];
                } else {
                    $size = 25;
                }
                $this->result_array = ArtistBroker::getArtistByPartialName($artist_name, $page, $size);
                $this->render();
                break;
            case 'searchartistbyurl':
            case 'searchartistsbyurl':
                if (isset($arrUri['path_items'][2]) and $arrUri['path_items'][2] != '') {
                    $artist_url = '';
                    for ($arrItem = 2; $arrItem <= count($arrUri['path_items']); $arrItem++) {
                        if (isset ($arrUri['path_items'][$arrItem])) {
                            if ($artist_url != '') {
                                $artist_url .= '/';
                            }
                            $artist_url .= $arrUri['path_items'][$arrItem];
                        }
                    }
                } else {
                    $this->render();
                    break;
                }
                if (isset($arrUri['parameters']['page']) and $arrUri['parameters']['page'] > 0) {
                    $page = $arrUri['parameters']['page'];
                } else {
                    $page = 0;
                }
                if (isset($arrUri['parameters']['size']) and $arrUri['parameters']['size'] > 0) {
                    $size = $arrUri['parameters']['size'];
                } else {
                    $size = 25;
                }
                $this->result_array = ArtistBroker::getArtistByPartialUrl($artist_url, $page, $size);
                $this->render();
                break;
            case 'searchtrackbyname':
            case 'searchtracksbyname':
                if (isset($arrUri['parameters']['strTrackName']) and $arrUri['parameters']['strTrackName'] = '') {
                    $track_name = $arrUri['parameters']['strTrackName'];
                } elseif (isset($arrUri['path_items'][2]) and $arrUri['path_items'][2] != '') {
                    $track_name = '';
                    for ($arrItem = 2; $arrItem <= count($arrUri['path_items']); $arrItem++) {
                        if (isset ($arrUri['path_items'][$arrItem])) {
                            if ($track_name != '') {
                                $track_name .= '/';
                            }
                            $track_name .= $arrUri['path_items'][$arrItem];
                        }
                    }
                } else {
                    $track_name = '';
                }
            case 'listtrack':
            case 'listtracks':
                if (!isset($track_name)) {
                    $track_name = '';
                }
                if (isset($arrUri['parameters']['page']) and $arrUri['parameters']['page'] > 0) {
                    $page = $arrUri['parameters']['page'];
                } else {
                    $page = 0;
                }
                if (isset($arrUri['parameters']['size']) and $arrUri['parameters']['size'] > 0) {
                    $size = $arrUri['parameters']['size'];
                } else {
                    $size = 25;
                }
                $result_array = TrackBroker::getTrackByPartialName($track_name, $page, $size);
                foreach ($result_array as $result) {
                    $result->set_full(true);
                    $this->result_array[] = $result;
                }
                $this->render();
                break;
            case 'searchtrackbyurl':
            case 'searchtracksbyurl':
                if (isset($arrUri['parameters']['strTrackUrl']) and $arrUri['parameters']['strTrackUrl'] = '') {
                    $track_url = $arrUri['parameters']['strTrackUrl'];
                } elseif (isset($arrUri['path_items'][2]) and $arrUri['path_items'][2] != '') {
                    $track_url = '';
                    for ($arrItem = 2; $arrItem <= count($arrUri['path_items']); $arrItem++) {
                        if (isset ($arrUri['path_items'][$arrItem])) {
                            if ($track_url != '') {
                                $track_url .= '/';
                            }
                            $track_url .= $arrUri['path_items'][$arrItem];
                        }
                    }
                } else {
                    $this->render();
                    break;
                }
                if (isset($arrUri['parameters']['page']) and $arrUri['parameters']['page'] > 0) {
                    $page = $arrUri['parameters']['page'];
                } else {
                    $page = 0;
                }
                if (isset($arrUri['parameters']['size']) and $arrUri['parameters']['size'] > 0) {
                    $size = $arrUri['parameters']['size'];
                } else {
                    $size = 25;
                }
                $result_array = TrackBroker::getTrackByPartialUrl($track_url, $page, $size);
                foreach ($result_array as $result) {
                    $result->set_full(true);
                    $this->result_array[] = $result;
                }
                $this->render();
                break;
            case 'searchshowbyname':
            case 'searchshowsbyname':
                if (isset($arrUri['parameters']['strShowName']) and $arrUri['parameters']['strShowName'] = '') {
                    $show_name = $arrUri['parameters']['strShowName'];
                } elseif (isset($arrUri['path_items'][2]) and $arrUri['path_items'][2] != '') {
                    $show_name = '';
                    for ($arrItem = 2; $arrItem <= count($arrUri['path_items']); $arrItem++) {
                        if (isset ($arrUri['path_items'][$arrItem])) {
                            if ($show_name != '') {
                                $show_name .= '/';
                            }
                            $show_name .= $arrUri['path_items'][$arrItem];
                        }
                    }
                } else {
                    $show_name = '';
                }
            case 'listshow':
            case 'listshows':
                if (!isset($show_name)) {
                    $show_name = '';
                }
                if (isset($arrUri['parameters']['page']) and $arrUri['parameters']['page'] > 0) {
                    $page = $arrUri['parameters']['page'];
                } else {
                    $page = 0;
                }
                if (isset($arrUri['parameters']['size']) and $arrUri['parameters']['size'] > 0) {
                    $size = $arrUri['parameters']['size'];
                } else {
                    $size = 25;
                }
                $this->result_array = ShowBroker::getShowByPartialName($show_name, $page, $size);
                $this->render();
                break;
            case 'searchshowbyurl':
            case 'searchshowsbyurl':
                if (isset($arrUri['parameters']['strShowUrl']) and $arrUri['parameters']['strShowUrl'] = '') {
                    $show_url = $arrUri['parameters']['strShowUrl'];
                } elseif (isset($arrUri['path_items'][2]) and $arrUri['path_items'][2] != '') {
                    $show_url = '';
                    for ($arrItem = 2; $arrItem <= count($arrUri['path_items']); $arrItem++) {
                        if (isset ($arrUri['path_items'][$arrItem])) {
                            if ($show_url != '') {
                                $show_url .= '/';
                            }
                            $show_url .= $arrUri['path_items'][$arrItem];
                        }
                    }
                } else {
                    $this->render();
                    break;
                }
                if (isset($arrUri['parameters']['page']) and $arrUri['parameters']['page'] > 0) {
                    $page = $arrUri['parameters']['page'];
                } else {
                    $page = 0;
                }
                if (isset($arrUri['parameters']['size']) and $arrUri['parameters']['size'] > 0) {
                    $size = $arrUri['parameters']['size'];
                } else {
                    $size = 25;
                }
                $this->result_array = TrackBroker::getShowByPartialUrl($show_url, $page, $size);
                $this->render();
                break;
            // Direct Lookups
            case 'gettrack':
                if (isset($arrUri['parameters']['intTrackID']) and $arrUri['parameters']['intTrackID'] = '') {
                    $intTrackID = $arrUri['parameters']['intTrackID'];
                } elseif (isset($arrUri['path_items'][2]) and $arrUri['path_items'][2] != '') {
                    $intTrackID = $arrUri['path_items'][2];
                } else {
                    $intTrackID = 0;
                }
                $this->result = TrackBroker::getTrackByID(UI::getLongNumber($intTrackID));
                $this->result->set_full(true);
                $this->render();
                break;
            case 'getshow':
                if (isset($arrUri['parameters']['intShowID']) and $arrUri['parameters']['intShowID'] = '') {
                    $intShowID = $arrUri['parameters']['intShowID'];
                } elseif (isset($arrUri['path_items'][2]) and $arrUri['path_items'][2] != '') {
                    $intShowID = $arrUri['path_items'][2];
                } else {
                    $intShowID = 0;
                }
                $this->result = ShowBroker::getShowByID(UI::getLongNumber($intShowID));
                $this->render();
                break;

            // Upload Scripts
            case 'addtracktoshow':
                if (isset($arrUri['parameters']['intTrackID']) and $arrUri['parameters']['intTrackID'] = '') {
                    $intTrackID = $arrUri['parameters']['intTrackID'];
                } elseif (isset($arrUri['path_items'][2]) and $arrUri['path_items'][2] != '') {
                    $intTrackID = $arrUri['path_items'][2];
                } else {
                    $intTrackID = 0;
                }
                if (isset($arrUri['parameters']['intShowID']) and $arrUri['parameters']['intShowID'] = '') {
                    $intShowID = $arrUri['parameters']['intShowID'];
                } elseif (isset($arrUri['path_items'][3]) and $arrUri['path_items'][3] != '') {
                    $intShowID = $arrUri['path_items'][3];
                } else {
                    $intShowID = 0;
                }
                if ($intTrackID != 0 and $intShowID != 0) {
                    $this->result = new NewShowTrackObject($intTrackID, $intShowID);
                } else {
                    $this->result = false;
                }
                $this->render();
                break;
            case 'newtrack':
                // TODO: Import newtrack
                break;
            case 'getshowid':
                if (isset($arrUri['parameters']['strShowUrl']) and $arrUri['parameters']['strShowUrl'] = '') {
                    $show_url = $arrUri['parameters']['strShowUrl'];
                } else {
                    $this->render();
                    break;
                }
                $this->result = ShowBroker::getShowByExactUrl($show_url);
                if ($this->result == false) {
                    if (isset($arrUri['parameters']['strShowName']) and $arrUri['parameters']['strShowName'] = '') {
                        $show_name = $arrUri['parameters']['strShowName'];
                    } else {
                        $this->render();
                        break;
                    }
                    $this->result = new NewExternalShowObject($show_url, $show_name);
                }
                $this->render();
                break;
            case 'editshow':
                if (isset($arrUri['parameters']['strShowUrl']) and $arrUri['parameters']['strShowUrl'] = '') {
                    $show_url = $arrUri['parameters']['strShowUrl'];
                } else {
                    $this->render();
                    break;
                }
                if (isset($arrUri['parameters']['strShowName']) and $arrUri['parameters']['strShowName'] = '') {
                    $show_name = $arrUri['parameters']['strShowName'];
                } else {
                    $this->render();
                    break;
                }
                if (isset($arrUri['parameters']['intShowID']) and $arrUri['parameters']['intShowID'] = '') {
                    $show = ShowBroker::getShowByID($arrUri['parameters']['intShowID']);
                    $show->set_strShowUrl($show_url);
                    $show->set_strShowName($show_name);
                    $show->write();
                    UI::sendHttpResponseNote(200);
                    break;
                } else {
                    $this->render();
                    break;
                }
                break;

            // Get Statistical Information
            case 'gettrends':
                // TODO: Import gettrends
                break;
            case 'getchart':
                // TODO: Import getchart
                break;

            // Voting
            case 'vote':
                if (isset($arrUri['path_items'][2]) and $arrUri['path_items'][2] != '') {
                    $intTrackID = $arrUri['path_items'][2];
                } elseif (isset($arrUri['parameters']['intTrackID']) and $arrUri['parameters']['intTrackID'] = '') {
                    $intTrackID = $arrUri['parameters']['intTrackID'];
                } else {
                    $intTrackID = 0;
                }
                if (isset($arrUri['path_items'][3]) and $arrUri['path_items'][3] != '') {
                    $intShowID = $arrUri['path_items'][3];
                } elseif (isset($arrUri['parameters']['intShowID']) and $arrUri['parameters']['intShowID'] = '') {
                    $intShowID = $arrUri['parameters']['intShowID'];
                } else {
                    $intShowID = 0;
                }
                if ($intTrackID != 0) {
                    $this->result = new NewVoteObject($intTrackID, $intShowID);
                } else {
                    $this->result = false;
                }
                $this->render();
                break;

            // Show generation scripts
            case 'trackprebumper':
                // TODO: Import trackprebumper
                break;
            case 'trackpostbumper':
                // TODO: Import trackpostbumper
                break;
            case 'showprebumper':
                // TODO: Import showprebumper
                break;
            case 'showmidbumper':
                // TODO: Import showmidbumper
                break;
            case 'showpostbumper':
                // TODO: Import showpostbumper
                break;

            // Generate show information
            // These functions are new
            case 'runshows':
                if (isset($arrUri['path_items'][2]) and $arrUri['path_items'][2] != '') {
                    $date = $arrUri['path_items'][2];
                } else {
                    $date = '';
                }
                $temp = new ChartObject($date);
                if ($date == '') {
                    $date = date('Ymd');
                }
                $temp = new NewDailyShowObject($date);
                $response = 'DAILY_SHOW=' . $date;
                if (7 == date('N', strtotime(makeLongDate($date) . ' 12:00:00'))) {
                    $temp = new NewWeeklyShowObject($date);
                    $response .= ' && WEEKLY_SHOW=' . $date;
                }
                if (1 == date('d', strtotime(makeLongDate($date) . ' 12:00:00 + 1 day'))) {
                    $temp = new NewMonthlyShowObject(substr($date, 0, 6));
                    $response .= ' && MONTHLY_SHOW=' . substr($date, 0, 6);
                }
                UI::sendHttpResponse(200, $response, 'text/plain');
                exit(0);
            case 'dailyshow':
                // TODO: create generatedailyshow
                break;
            case 'weeklyshow':
                // TODO: create generateweeklyshow
                break;
            case 'monthlyshow':
                // TODO: create generatemonthlyshow
                break;

            // Finish the show generation
            case 'finalize':
            case 'finalise':
                // TODO: Import finalise/finalize
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
                foreach ($this->result->getSelf() as $key=>$value) {
                    if (is_array($value)) {
                        $value = UI::utf8json($value);
                    }
                    $content .= "<tr><td>$key</td><td>$value</td></tr>";
                }
                $content .= "</table>";
                UI::sendHttpResponse(200, null, 'text/html', $content);
            } elseif (is_array($this->result_array)) {
                $content = '';
                foreach ($this->result_array as $result_item) {
                    $content .= "<table>";
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
            } else {
                UI::sendHttpResponse(404);
            }
            break;
        case 'json':
            if (is_object($this->result)) {
                UI::sendHttpResponse(200, UI::utf8json($this->result->getSelf()), 'application/json');
            } elseif (is_array($this->result_array)) {
                foreach ($this->result_array as $result_item) {
                    if (is_object($result_item)) {
                        $result_item = $result_item->getSelf();
                    }
                    $result[] = $result_item;
                }
                UI::sendHttpResponse(200, UI::utf8json($result), 'application/json');
            } else {
                list($uri, $data) = UI::getPath();
                UI::sendHttpResponse(404, json_encode(array('Error'=>'The requested URL ' . $uri . ' was not found.')), 'application/json');
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
            } elseif (is_array($this->result_array)) {
                $return = '';
                $key_inc = 0;
                foreach ($this->result_array as $result_item) {
                    $key_inc++;
                    if (is_object($result_item)) {
                        $result_item = $result_item->getSelf();
                    }
                    foreach ($result_item as $key=>$value) {
                        if (is_array($value)) {
                            foreach ($value as $v_key=>$v_value) {
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
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class API_NotApiCall extends CustomException
{
    protected $message = 'This is not an API call';
    protected $code    = 255;
}
