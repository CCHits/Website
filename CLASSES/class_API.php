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
                UI::requireAuth();
                $objUser = UserBroker::getUser();
                $this->result = array(
                    'intUserID' => $objUser->get_intUserID(),
                    'isUploader' => $objUser->get_isUploader(),
                    'isAuthorized' => $objUser->get_isAuthorized(),
                    'isAdmin' => $objUser->get_isAdmin()
                );
                $this->render();
                break;
            // Searches
            case 'searchartistbyname':
            case 'searchartistsbyname':
                if (isset($arrUri['path_items'][2]) and $arrUri['path_items'][2] != '') {
                    $artist_name = $arrUri['path_items'][2];
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
                    $artist_url = $arrUri['path_items'][2];
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
                $this->result_array = ArtistBroker::getArtistByUrl($artist_url, $page, $size);
                $this->render();
                break;
            case 'searchtrackbyname':
            case 'searchtracksbyname':
                if (isset($arrUri['path_items'][2]) and $arrUri['path_items'][2] != '') {
                    $track_name = $arrUri['path_items'][2];
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
                $this->result_array = TrackBroker::getTrackByPartialName($track_name, $page, $size);
                $this->render();
                break;
            case 'searchtrackbyurl':
            case 'searchtracksbyurl':
                if (isset($arrUri['path_items'][2]) and $arrUri['path_items'][2] != '') {
                    $track_url = $arrUri['path_items'][2];
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
                $this->result_array = TrackBroker::getTrackByUrl($track_url, $page, $size);
                $this->render();
                break;
            case 'searchshowbyname':
            case 'searchshowsbyname':
                if (isset($arrUri['path_items'][2]) and $arrUri['path_items'][2] != '') {
                    $show_name = $arrUri['path_items'][2];
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
                $this->result_array = ShowBroker::getShowByPartialName($track_name, $page, $size);
                $this->render();
                break;
            case 'searchshowbyurl':
            case 'searchshowsbyurl':
                if (isset($arrUri['path_items'][2]) and $arrUri['path_items'][2] != '') {
                    $show_url = $arrUri['path_items'][2];
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
                $this->result_array = TrackBroker::getShowByPartialUrl($track_url, $page, $size);
                $this->render();
                break;

                // Direct Lookups
            case 'gettrack':
                $this->result = TrackBroker::getTrackByID(UI::getLongNumber($arrUri['path_items'][2]));
                $this->render();
                break;
            case 'getshow':
                $this->result = ShowBroker::getShowByID(UI::getLongNumber($arrUri['path_items'][2]));
                $this->render();
                break;

            case 'getshowid':
            case 'addtracktoshow':
            case 'editshow':
            case 'gettrends':
            case 'gettrend':
            case 'gettrendsdata':
            case 'getchart':
            case 'getchartdata':
            case 'getartist':
            case 'vote':
            case 'file':
            case 'trackprebumper':
            case 'trackpostbumper':
            case 'showprebumper':
            case 'showmidbumper':
            case 'showpostbumper':
            case 'artist':
            case 'artistsounds':
            case 'title':
            case 'titlesounds':
            case 'license':
            case 'isnsfw':
            case 'votes':
            case 'shows':
            case 'votesadj':
            case 'getnewdailytrack':
            case 'getnewweeklytrack':
            case 'getoldweeklytrack':
            case 'getnewmonthlytrack':
            case 'finalize':
            case 'finalise':
            case 'showiddaily':
            case 'showurldaily':
            case 'spokenshowurldaily':
            case 'shownamedaily':
            case 'showidweekly':
            case 'showurlweekly':
            case 'spokenshowurlweekly':
            case 'shownameweekly':
            case 'showidmonthly':
            case 'showurlmonthly':
            case 'spokenshowurlmonthly':
            case 'shownamemonthly':
            case 'newtrack':
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
                foreach ($this->result_array as $result) {
                    $content .= "<table>";
                    foreach ($result->getSelf() as $key=>$value) {
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
                    $result[] = $result_item->getSelf();
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
                    foreach ($result_item->getSelf() as $key=>$value) {
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
