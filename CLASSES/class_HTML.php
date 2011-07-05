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
 * @package  HTML
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
        $this->result = array(
            'ServiceName'=>ConfigBroker::getConfig('ServiceName', 'CCHits'),
            'Slogan'=>ConfigBroker::getConfig('Slogan', 'Where you make the charts')
        );
        $arrUri = UI::getUri();

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
            switch($arrUri['path_items'][0]) {
            case 'track':
            case 't':
                $this->track();
                break;
            case 'show':
            case 's':
                $this->show();
                break;
            case 'vote':
                $this->vote();
                break;
            case 'chart':
                $this->chart();
                break;
            case 'daily':
                if (isset($arrUri['path_items'][1]) and $arrUri['path_items'][1] == 'rss') {
                    $this->format = 'rss';
                }
                $this->daily();
                break;
            case 'weekly':
                if (isset($arrUri['path_items'][1]) and $arrUri['path_items'][1] == 'rss') {
                    $this->format = 'rss';
                }
                $this->weekly();
                break;
            case 'monthly':
                if (isset($arrUri['path_items'][1]) and $arrUri['path_items'][1] == 'rss') {
                    $this->format = 'rss';
                }
                $this->monthly();
                break;
            case 'about':
                if (isset($arrUri['path_items'][1])) {
                    $this->about();
                } else {
                    switch($arrUri['path_items'][1]) {
                    case 'goals':
                        $this->about_goals();
                        break;
                    case 'licenses':
                        $this->about_licenses();
                        break;
                    case 'source':
                        $this->about_source();
                        break;
                    case 'database':
                        $this->about_database();
                        break;
                    case 'api':
                        $this->about_api();
                        break;
                    case 'voteadjust':
                        $this->about_voteadjust();
                        break;
                    case 'theme':
                        $this->about_theme();
                        break;
                    default:
                        $redirect_url = "{$arrUri['scheme']}://{$arrUri['scheme']}";
                        if (isset($arrUri['port']) and $arrUri['port'] != '') {
                            $redirect_url .= ':' . $arrUri['port'];
                        }
                        if (isset($arrUri['site_path']) and $arrUri['site_path'] != '') {
                            $redirect_url .= '/' . $arrUri['site_path'];
                        }
                        $redirect_url .=  '/about';
                        UI::SendHttpResponse(307, "Location: $redirect_url", '');
                    }
                }
                break;
            default:
                $redirect_url = "{$arrUri['scheme']}://{$arrUri['host']}";
                if (isset($arrUri['port']) and $arrUri['port'] != '') {
                    $redirect_url .= ':' . $arrUri['port'];
                }
                if (isset($arrUri['site_path']) and $arrUri['site_path'] != '') {
                    $redirect_url .= '/' . $arrUri['site_path'];
                }
                $redirect_url .=  '/';
                UI::SendHttpResponse(307, "Location: $redirect_url", '');
            }
        }
    }

    /**
     * Return the front page
     *
     * @return void
     */
    protected function front_page()
    {
        $this->result['chart'] = ChartBroker::getChartByDate('', 0, 15);
        $this->result['daily'] = end(ShowBroker::getInternalShowByType('daily', 1));
        $this->result['weekly'] = end(ShowBroker::getInternalShowByType('weekly', 1));
        $this->result['monthly'] = end(ShowBroker::getInternalShowByType('monthly', 1));
        if ($this->render()) {
            if ($this->format == 'html') {
                UI::SmartyTemplate("frontpage.html", $this->result);
            } elseif ($this->format == 'rss') {
                UI::SmartyTemplate("frontpage.rss", $this->result);
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
        case 'rss':
            return true;
        case 'json':
            header("Content-type: application/json");
            echo UI::utf8json($this->result);
            return false;
        case 'xml':
            header("Content-type: application/xml");
            echo UI::utf8xml($this->result->getSelf());
            return false;
        }
    }
}

