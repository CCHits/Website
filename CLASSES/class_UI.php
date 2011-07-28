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
 * This class handles all HTTP requirements. It is based on code by Ian Selby, as below.
 * There are some enhancements, made by me which are licensed AGPLv3. In addition, there is
 * some code which is from other sites. These URLs are clearly represented in the docblocks.
 *
 * @category Default
 * @package  UI
 * @author   Ian Selby <unknown-email-address-but-code-from@gen-x-design.com>
 * @license  http://www.gen-x-design.com All content Copyright � Gen X Design | Ian Selby
 * @link     http://www.gen-x-design.com/archives/create-a-rest-api-with-php/
 */
class UI
{
    protected static $ui_handler = null;
    protected $arrUri = null;
    protected $arrLibs = null;

    protected static $http_status_codes = Array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    );

    /**
     * An internal function to make this a singleton
     *
     * @return object This class by itself.
     */
    private static function getHandler()
    {
        if (self::$ui_handler == null) {
            self::$ui_handler = new UI();
        }
        return self::$ui_handler;
    }

    /**
     * Returns the Path and query values for this script
     *
     * @return array[0] URI
     * @return array[1] Query values
     */
    function getPath()
    {
        $handler = self::getHandler();
        if ($handler->arrUri != null) {
            return $handler->arrUri;
        }
        if ( ! isset($_SERVER['REQUEST_METHOD'])) {
            if (preg_match('/\/(.*)$/', $GLOBALS['argv'][0]) == 0) {
                $filename = trim(`pwd`) . '/' . $GLOBALS['argv'][0];
            } else {
                $filename = $GLOBALS['argv'][0];
            }
            $uri = 'file://' . $filename;
            unset($data[0]);
            $data = $GLOBALS['argv'];
        } else {
            $uri = "http";
            if (isset($_SERVER['HTTPS'])) {
                $uri .= 's';
            }
            $uri .= '://';
            list($username, $password) = self::getAuth();
            if ($username != null) {
                $uri .= "{$username}";
                if ($password != null) {
                    $uri .= ":{$password}";
                }
                $uri .= '@';
            }
            $uri .= $_SERVER['SERVER_NAME'];
            if ((isset($_SERVER['HTTPS']) and $_SERVER['SERVER_PORT'] != 443) or ( ! isset($_SERVER['HTTPS']) and $_SERVER['SERVER_PORT'] != 80)) {
                $uri .= ':' . $_SERVER['SERVER_PORT'];
            }
            $uri .= $_SERVER['REQUEST_URI'];
            switch(strtolower($_SERVER['REQUEST_METHOD'])) {
            case 'get':
                $data = $_GET;
                break;
            case 'post':
                $data = $_POST;
                if (isset($_FILES) and is_array($_FILES)) {
                    $data['_FILES'] = $_FILES;
                }
                break;
            case 'put':
                parse_str(file_get_contents('php://input'), $_PUT);
                $data = $_PUT;
                break;
            case 'delete':
            case 'head':
                $data = $_REQUEST;
            }
        }
        $handler->arrUri = array($uri, $data);
        return array($uri, $data);
    }

    /**
     * Returns the URI for this script
     *
     * @return array URI
     */
    function getUri()
    {
        list($uri, $data) = self::getPath();
        $arrUrl = parse_url($uri);
        $arrUrl['full'] = $uri;
        $arrUrl['parameters'] = $data;
        if (substr($arrUrl['path'], -1) == '/') {
            $arrUrl['path'] = substr($arrUrl['path'], 0, -1);
        }
        $match = preg_match('/\/(.*)/', $arrUrl['path'], $matches);
        if ($match > 0) {
            $arrUrl['path'] = $matches[1];
        }
        $arrUrl['site_path'] = '';
        $arrUrl['router_path'] = $arrUrl['path'];
        if (isset($_SERVER['SCRIPT_NAME']) and isset($_SERVER['REQUEST_METHOD'])) {
            $path_elements = str_split($arrUrl['path']);
            $match = preg_match('%/(.*)$%', $_SERVER['SCRIPT_NAME'], $matches);
            $script_elements = str_split($matches[1]);
            $char = 0;
            while ($char <= count($path_elements) and $path_elements[$char] == $script_elements[$char]) {
                $char++;
            }
            $arrUrl['site_path'] = substr($arrUrl['path'], 0, $char);
            $arrUrl['router_path'] = substr($arrUrl['path'], $char);
        }
        $arrUrl['path_items'] = explode('/', $arrUrl['router_path']);
        $arrLastUrlItem = explode('.', $arrUrl['path_items'][count($arrUrl['path_items'])-1]);
        if (count($arrLastUrlItem) > 1) {
            $arrUrl['path_items'][count($arrUrl['path_items'])-1] = '';
            foreach ($arrLastUrlItem as $key=>$UrlItem) {
                if ($key + 1 == count($arrLastUrlItem)) {
                    $arrUrl['format'] = $UrlItem;
                } else {
                    if ($arrUrl['path_items'][count($arrUrl['path_items'])-1] != '') {
                        $arrUrl['path_items'][count($arrUrl['path_items'])-1] .= '.';
                    }
                    $arrUrl['path_items'][count($arrUrl['path_items'])-1] .= $UrlItem;
                }
            }
        } else {
            $arrUrl['format'] = '';
        }
        $arrUrl['basePath'] = "{$arrUrl['scheme']}://{$arrUrl['host']}";
        if (isset($arrUrl['port']) and $arrUrl['port'] != '') {
            $arrUrl['basePath'] .= ':' . $arrUrl['port'];
        }
        if (isset($arrUrl['site_path']) and $arrUrl['site_path'] != '') {
            $arrUrl['basePath'] .= '/' . $arrUrl['site_path'];
        }
        $arrUrl['basePath'] .=  '/';
        return $arrUrl;
    }

    /**
     * As there are several ways of getting HTTP authentication, this function should handle all of these
     *
     * @return array The username and password provided by authentication, or nulls for both.
     */
    function getAuth()
    {
        $username = null;
        $password = null;
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $auth_params = explode(":", base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
            $username = $auth_params[0];
            unset($auth_params[0]);
            $password = implode('', $auth_params);
        } elseif (isset($_SERVER['PHP_AUTH_USER']) and isset($_SERVER['PHP_AUTH_PW'])) {
            $username = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];
            $uri .= "{$username}:{$password}@";
        }
        return array($username, $password);
    }

    /**
     * A helper function to ensure pages that require authentication, get them.
     *
     * @return void
     */
    function requireAuth()
    {
        list($username, $password) = self::getAuth();
        if ($username == null) {
            sendHttpResponse(401);
        }
    }

    /**
     * Send a correctly formatted HTTP response to a request
     *
     * @param integer $status       HTTP response code
     * @param string  $body         Message to be sent
     * @param string  $content_type MIME type to send
     * @param string  $extra        Additional information beyond the routine HTTP status message
     *
     * @return void
     */
    function sendHttpResponse($status = 200, $body = null, $content_type = 'text/html', $extra = '')
    {
        header('HTTP/1.1 ' . $status . ' ' . self::$http_status_codes[$status]);
        header('Content-type: ' . $content_type);

        if ($body != '' and $body != null) {
            echo $body;
            exit;
        } elseif ($content_type != 'text/html') {
            // We can't send anything because it's not a valid response.
        } else {
            $message = '';
            switch($status) {
            case 204:
                $message = '';
                break;
            case 401:
                header('WWW-Authenticate: Basic realm="Authentication Required"');
                $message = 'You must be authorized to view this page.';
                break;
            case 404:
                list($uri, $data) = self::getPath();
                $message = 'The requested URL ' . $uri . ' was not found.';
                break;
            case 500:
                $message = 'The server encountered an error processing your request.';
                break;
            case 501:
                $message = 'The requested method is not implemented.';
                break;
            }

            if ($status != 204) {
                $message_content = "<p>{$message}</p>";
                if ($extra != '') {
                    $message_content .= "\r\n    <p>$extra</p>";
                }
                if (isset($GLOBALS['generator'])) {
                    $message_content .= "\r\n    <p>This page took " . round(microtime(true) - $GLOBALS['generator'], 3) . ' seconds to complete.</p>';
                }
                $body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
' .                     '<html>
' .                     '  <head>
' .                     '    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
' .                     '    <title>' . $status . ' ' . self::$http_status_codes[$status] . '</title>
' .                     '  </head>
' .                     '  <body>
' .                     '    <h1>' . self::$http_status_codes[$status] . '</h1>
' .                     '    ' . $message_content . '
' .                     '  </body>
' .                     '</html>';
                echo $body;
            }
            exit(0);
        }
    }

    /**
     * Send an extended, yet still correctly formatted HTTP response to a request
     *
     * @param integer $status HTTP response code
     * @param string  $extra  Additional information beyond the routine HTTP status message
     *
     * @return void
     */
    function sendHttpResponseNote($status = 200, $extra = '')
    {
        sendHttpResponse($status, null, 'text/html', $extra);
    }

    /**
     * Provide a downloadable file
     *
     * @param string  $file      File to send
     * @param boolean $is_resume Can we supply headers to make this file resumable?
     *
     * @return void
     *
     * @link http://www.php.net/manual/en/function.fread.php#84115
     */
    function dl_file_resumable($file, $is_resume=TRUE)
    {
        //First, see if the file exists
        if (!is_file($file)) {
            self::sendHttpResponse(404);
        }

        //Gather relevent info about file
        $size = filesize($file);
        $fileinfo = pathinfo($file);

        //workaround for IE filename bug with multiple periods / multiple dots in filename
        //that adds square brackets to filename - eg. setup.abc.exe becomes setup[1].abc.exe
        $filename = (strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) ?
                      preg_replace('/\./', '%2e', $fileinfo['basename'], substr_count($fileinfo['basename'], '.') - 1) :
                      $fileinfo['basename'];

        $file_extension = strtolower($path_info['extension']);

        //This will set the Content-Type to the appropriate setting for the file
        switch($file_extension) {
        case 'exe':
            $ctype='application/octet-stream';
            break;
        case 'zip':
            $ctype='application/zip';
            break;
        case 'mp3':
            $ctype='audio/mpeg';
            break;
        case 'mpg':
            $ctype='video/mpeg';
            break;
        case 'avi':
            $ctype='video/x-msvideo';
            break;
        default:
            $ctype='application/force-download';
        }

        //check if http_range is sent by browser (or download manager)
        if ($is_resume && isset($_SERVER['HTTP_RANGE'])) {
            list($size_unit, $range_orig) = explode('=', $_SERVER['HTTP_RANGE'], 2);

            if ($size_unit == 'bytes') {
                //multiple ranges could be specified at the same time, but for simplicity only serve the first range
                //http://tools.ietf.org/id/draft-ietf-http-range-retrieval-00.txt
                list($range, $extra_ranges) = explode(',', $range_orig, 2);
            } else {
                $range = '';
            }
        } else {
            $range = '';
        }

        //figure out download piece from range (if set)
        list($seek_start, $seek_end) = explode('-', $range, 2);

        //set start and end based on range (if set), else set defaults
        //also check for invalid ranges.
        $seek_end = (empty($seek_end)) ? ($size - 1) : min(abs(intval($seek_end)),($size - 1));
        $seek_start = (empty($seek_start) || $seek_end < abs(intval($seek_start))) ? 0 : max(abs(intval($seek_start)),0);

        //add headers if resumable
        if ($is_resume) {
            //Only send partial content header if downloading a piece of the file (IE workaround)
            if ($seek_start > 0 || $seek_end < ($size - 1)) {
                header('HTTP/1.1 206 Partial Content');
            }

            header('Accept-Ranges: bytes');
            header('Content-Range: bytes '.$seek_start.'-'.$seek_end.'/'.$size);
        }

        //headers for IE Bugs (is this necessary?)
        //header("Cache-Control: cache, must-revalidate");
        //header("Pragma: public");

        header('Content-Type: ' . $ctype);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: '.($seek_end - $seek_start + 1));

        //open the file
        $fp = fopen($file, 'rb');
        //seek to start of missing part
        fseek($fp, $seek_start);

        //start buffered download
        while (!feof($fp)) {
            //reset time limit for big files
            set_time_limit(0);
            print(fread($fp, 1024*8));
            flush();
            ob_flush();
        }

        fclose($fp);
        exit;
    }

    /**
     * Return UTF8 encoded array
     *
     * @param array $array Incoming array
     *
     * @return array UTF8 encoded array
     *
     * @link http://www.php.net/manual/en/function.json-encode.php#99837
     */
    function utf8element($array = null)
    {
        if ($array == null) {
            $array = array();
        }
        $newArray = array();
        if (is_object($array)) {
            $array = (array) $array;
        }
        if ($array == null) {
            return null;
        }
        foreach ($array as $key=>$val) {
            if (is_array($val)) {
                $newArray[utf8_encode($key)] = self::utf8element($val);
            } elseif (is_object($val)) {
                $newArray[utf8_encode($key)] = self::utf8element((array) $val);
            } elseif ($val == false) {
                $newArray[utf8_encode($key)] = '0';
            } else {
                $newArray[utf8_encode($key)] = utf8_encode($val);
            }
        }
        return $newArray;
    }

    /**
     * Return utf8 encoded JSON
     *
     * @param Array|object $array Incoming data
     *
     * @return json UTF8 encoded JSON string
     */
    function utf8json($array = array())
    {
        return json_encode(self::utf8element($array));
    }

    /**
     * For shortening purposes, check trackids and showids against a Base36 scheme.
     *
     * These IDs that are Base36 encoded will be prefixed x
     *
     * @param string $id The ID to parse
     *
     * @return integer The resulting integer
     */
    function getLongNumber($id = '')
    {
        if (strtolower(substr($id, 0, 1)) == 'x') {
            return base_convert(strtolower(substr($id, 1)), 36, 10);
        } else {
            return $id;
        }
    }

    /**
     * For shortening purposes, convert trackIDs and showIDs to a Base36 scheme, prefixed x
     *
     * @param integer $intID The ID to parse
     *
     * @return string The resulting ID
     */
    function setLongNumber($intID = 0)
    {
        return 'x' . strtoupper(base_convert($intID, 10, 36));
    }

    /**
     * Return the non-abbreviated version of the license terms
     *
     * @param string $license The license terms to be translated
     *
     * @return string The license terms in non-abbreviated language.
     */
    function get_enumTrackLicenseFull($license = '')
    {
        $cc = "Creative Commons";
        $by = "By Attribution";
        $sa = "Share Alike";
        $nc = "Non-Commercial";
        $nd = "No Derivatives";
        $sp = "Sampling+";
        $z  = "Zero";
        return self::get_enumTrackLicenseSolve($license, $cc, $by, $sa, $nc, $nd, $sp, $z);
    }

    /**
     * Return the spoken version of the license terms
     *
     * @param string $license The license terms to be translated
     *
     * @return string The license terms, non-abbreviated, in spoken format.
     */
    function get_enumTrackLicensePronouncable($license = '')
    {
        $cc = "Creative Commons";
        $by = "By Attribution";
        $sa = "Share Ae-like";
        $nc = "Non Commercial";
        $nd = "No Der-i-vat-ives";
        $sp = "Samp-ling plus";
        $z  = "Zero";
        return self::get_enumTrackLicenseSolve($license, $cc, $by, $sa, $nc, $nd, $sp, $z);
    }

    /**
     * A helper function to return the license terms using appropriate terminology for each of the signals
     *
     * @param string $license The license terms to be translated
     * @param string $cc      The text to return, indicating Creative Commons.
     * @param string $by      The text to return, indicating By Attribution.
     * @param string $sa      The text to return, indicating Share Alike
     * @param string $nc      The text to return, indicating Non Commercial
     * @param string $nd      The text to return, indicating No Derivatives
     * @param string $sp      The text to return, indicating Sampling+
     * @param string $z       The text to return, indicating Zero
     *
     * @return string License terms as per the function logic
     */
    protected function get_enumTrackLicenseSolve(
        $license = '',
        $cc = "Creative Commons",
        $by = "By Attribution",
        $sa = "Share Alike",
        $nc = "Non-Commercial",
        $nd = "No Derivatives",
        $sp = "Sharing+",
        $z  = "Zero"
    ) {
        switch($license) {
        case 'cc-by':
            return "$cc, $by";
        case 'cc-by-sa':
            return "$cc, $by, $sa";
        case 'cc-sa':
            return "$cc, $sa";
        case 'cc-by-nc':
            return "$cc, $by, $nc";
        case 'cc-nc':
            return "$cc, $nc";
        case 'cc-by-nd':
            return "$cc, $by, $nd";
        case 'cc-nd':
            return "$cc, $nd";
        case 'cc-by-nc-sa':
            return "$cc, $by, $nc, $sa";
        case 'cc-nc-sa':
            return "$cc, $nc, $sa";
        case 'cc-by-nc-nd':
            return "$cc, $by, $nc, $nd";
        case 'cc-nc-nd':
            return "$cc, $nc, $nd";
        case 'cc-sampling+':
            return "$cc, $sp";
        case 'cc-nc-sampling+':
            return "$cc, $nc, $sp";
        case 'cc-0':
            return "$cc $z";
        }
    }

    /**
     * Return the date in Y-m-d format from Ymd format
     *
     * @param integer $date The date in Ymd format
     *
     * @return string the date in Y-m-d format
     */
    function makeLongDate($date)
    {
        if (preg_match('/(\d\d\d\d)(\d\d)(\d\d)|(\d\d\d\d)(\d\d)/', $date, $matches) == 1) {
            if (isset($matches[3])) {
                return $matches[1] . '-' . $matches[2] . '-' . $matches[3];
            } else {
                return $matches[1] . '-' . $matches[2];
            }
        } else {
            return false;
        }
    }

    /**
     * Return the date in Ymd format from Y-m-d format
     *
     * @param string $date The date in Y-m-d format
     *
     * @return integer The date in Ymd format
     */
    function makeShortDate($date)
    {
        if (preg_match('/(\d\d\d\d)-(\d\d)-(\d\d)|(\d\d\d\d)-(\d\d)/', $date, $matches) == 1) {
            if (isset($matches[3])) {
                return $matches[1] . $matches[2] . $matches[3];
            } else {
                return $matches[1] . $matches[2];
            }
        } else {
            return false;
        }
    }


    /**
     * Return the spoken version of the show date
     *
     * @param integer $date The date to be read in format YYYYMMDD
     *
     * @return string The date, in an easily parsible, spoken format.
     */
    function getPronouncableDate($date = '0')
    {
        if (preg_match('/(\d\d)(\d\d)(\d\d)(\d\d)|(\d\d)(\d\d)(\d\d)/', $date, $matches) == 1) {
            foreach ($matches as $match) {
                switch($match) {
                case '01':
                    $arrReturn[] = "zero One";
                    break;
                case '02':
                    $arrReturn[] = "zero Two";
                    break;
                case '03':
                    $arrReturn[] = "zero Three";
                    break;
                case '04':
                    $arrReturn[] = "zero Four";
                    break;
                case '05':
                    $arrReturn[] = "zero Five";
                    break;
                case '06':
                    $arrReturn[] = "zero Six";
                    break;
                case '07':
                    $arrReturn[] = "zero seven";
                    break;
                case '08':
                    $arrReturn[] = "zero eight";
                    break;
                case '09':
                    $arrReturn[] = "zero nine";
                    break;
                case '10':
                    $arrReturn[] = "ten";
                    break;
                case '11':
                    $arrReturn[] = "eleven";
                    break;
                case '12':
                    $arrReturn[] = "twelve";
                    break;
                case '13':
                    $arrReturn[] = "thirteen";
                    break;
                case '14':
                    $arrReturn[] = "fourteen";
                    break;
                case '15':
                    $arrReturn[] = "fifteen";
                    break;
                case '16':
                    $arrReturn[] = "sixteen";
                    break;
                case '17':
                    $arrReturn[] = "seventeen";
                    break;
                case '18':
                    $arrReturn[] = "eighteen";
                    break;
                case '19':
                    $arrReturn[] = "nineteen";
                    break;
                case '20':
                    $arrReturn[] = "twenty";
                    break;
                case '21':
                    $arrReturn[] = "twenty One";
                    break;
                case '22':
                    $arrReturn[] = "twenty two";
                    break;
                case '23':
                    $arrReturn[] = "twenty three";
                    break;
                case '24':
                    $arrReturn[] = "twenty four";
                    break;
                case '25':
                    $arrReturn[] = "twenty five";
                    break;
                case '26':
                    $arrReturn[] = "twenty six";
                    break;
                case '27':
                    $arrReturn[] = "twenty seven";
                    break;
                case '28':
                    $arrReturn[] = "twenty eight";
                    break;
                case '29':
                    $arrReturn[] = "twenty nine";
                    break;
                case '30':
                    $arrReturn[] = "thirty";
                    break;
                case '31':
                    $arrReturn[] = "Thirty one";
                    break;
                default:
                    // You shouldn't really have anything in here!
                }
            }
            $return = '';
            foreach ($arrReturn as $arrRet) {
                if ($return != '') {
                    $return .= ', ';
                }
                $return .= $arrRet;
            }
        }
        return $return;
    }

    /**
     * Do a redirection to the $new_page (relative to the base URI of the site)
     *
     * @param string  $new_page  New page to refer to
     * @param boolean $permanant Is this redirection permanant or not?
     *
     * @return void
     */
    function Redirect($new_page = '', $permanant = true)
    {
        $arrUri = self::getUri();
        $redirect_url = $arrUri['basePath'] . '/' . $new_page;
        if ($permanant) {
            $code = 301;
        } else {
            $code = 307;
        }
        self::SendHttpResponse($code, "Location: $redirect_url", '');
    }

    /**
     * This function ensures we've got the Smarty library loaded, and then
     * starts the template associated to it.
     *
     * @param string $template       Template to load
     * @param array  $arrAssignments Variables to be assigned to the template
     *
     * @return void
     */
    function SmartyTemplate($template = '', $arrAssignments = array())
    {
        $handler = self::getHandler();
        if ($handler->arrLibs == null) {
            $handler->arrLibs = new ExternalLibraryLoader();
        }
        $SmartyVersion = $handler->arrLibs->getVersion("SMARTY");
        if ($SmartyVersion == false) {
            var_dump($handler);
            die("Failed to load Smarty");
        }
        $libSmarty = dirname(__FILE__) . '/../EXTERNALS/SMARTY/' . $SmartyVersion . '/libs/Smarty.class.php';
        $baseSmarty = dirname(__FILE__) . '/../TEMPLATES/';
        include_once $libSmarty;
        $objSmarty = new Smarty();
        $objSmarty->debugging = ConfigBroker::getAppConfig('smarty_debug', 'false');
        $objSmarty->setTemplateDir($baseSmarty . 'Source');
        $objSmarty->setCompileDir($baseSmarty . 'Compiled');
        $objSmarty->setCacheDir($baseSmarty . 'Cache');
        $objSmarty->setConfigDir($baseSmarty . 'Config');
        if (is_array($arrAssignments) and count($arrAssignments) > 0) {
            foreach ($arrAssignments as $key=>$value) {
                $objSmarty->assign($key, $value);
            }
        }
        $objSmarty->display($template . '.tpl');
    }
}
