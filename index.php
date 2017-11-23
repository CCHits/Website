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

require_once dirname(__FILE__) . '/CLASSES/autoloader.php';

$generator = microtime(true);

$arrUri = UI::getUri();
$content = null;
try {
    if (is_array($arrUri)
        and isset($arrUri['path_items'])
        and is_array($arrUri['path_items'])
        and count($arrUri['path_items']) > 0
    ) {
        switch($arrUri['path_items'][0]) {
        case 'media':
            switch($arrUri['path_items'][1]) {
            case 'track': // MP3/OGA/M4A Audio, PNG Images
            case 'show': // PNG Images
            case 'daily':
            case 'weekly':
            case 'monthly':
            case 'extra':
                // This handles offloading to a 3rd party media store (e.g. archive.org)
                $remotepath = MediaRedirect::getNewUrl($arrUri['path_items'][0], $arrUri['path_items'][1], $arrUri['format']);
                if ($remotepath) {
                  header("Location: $remotepath");
                  exit(0);
                }
                $file = ConfigBroker::getConfig('fileBase', '/var/www/media') . '/' . $arrUri['path_items'][1] . '/';
                break;
            default:
                UI::sendHttpResponse(404);
            }
            $file .= $arrUri['path_items'][2] . '.' . $arrUri['format'];
            if (!file_exists($file)) {
                error_log("Could not find $file", 3, dirname(__FILE__) . '/../php.log');
                UI::sendHttpResponse(404);
            } else {
                if ($arrUri['path_items'][1] == 'track' and TrackBroker::getTrackByID($arrUri['path_items'][2])->get_isApproved() == false and UserBroker::getUser()->get_isAdmin()) {
                    UI::sendHttpResponse(401);
                } else {
                    UI::dl_file_resumable($file, TRUE);
                }
            }
        case 'api':
            $content = new API();
            break;
        case 'openid':
            if (isset($_POST['id'])) {
                if (isset($_SESSION['OPENID_AUTH'])) {
                    unset($_SESSION['OPENID_AUTH']);
                }
                $content = OpenID::request($_POST['id'], $arrUri['basePath'] . 'openid', $arrUri['basePath'] . 'admin', $arrUri['basePath'] . 'admin');
            } elseif (isset($_REQUEST['return'])) {
                $content = OpenID::response($arrUri['basePath'] . 'openid');
            } elseif (isset($_GET['logout'])) {
                UI::start_session();
                unset($_SESSION['OPENID_AUTH']);
                UI::redirect('admin');
            } else {
                UI::redirect('admin');
            }
            break;
        case 'google': // When the user clicks on the "Login with your Google Account (OpenID Connect)" button
            // Start session
            UI::start_session();

            // Retrieve config
            $client_id = GoogleConfigBroker::getClientId();
            $redirect_uri = GoogleConfigBroker::getRedirectUri();

            // Get google's authorization endpoint
            $authorization_endpoint = GoogleConfigBroker::getAuthorizationEndpoint();

            // Generate CSRF token, and store it in the session
            $csrf_token = sha1(openssl_random_pseudo_bytes(1024));
            $_SESSION['CSRF_TOKEN'] = $csrf_token;

            // Redirect to Google's authorization page
            $google_url = $authorization_endpoint . "?client_id=" . $client_id . "&response_type=code&scope=openid%20email%20profile&redirect_uri=" . $redirect_uri . "&state=" . $csrf_token;
            header("Location: $google_url");
            exit(0);
            break;
        case 'oauth2callback': // Called by google after the use is authenticated
            // Start session
            UI::start_session();

            // Retrieve config
            $client_id = GoogleConfigBroker::getClientId();
            $client_secret = GoogleConfigBroker::getClientSecret();
            $redirect_uri = GoogleConfigBroker::getRedirectUri();

            // Sanity check
            if (isset($_GET['code']) && ($client_id != null) && ($client_secret != null) && ($redirect_uri != null)) {
                // Retrieve CSRF token from session
                $csrf_token = $_SESSION['CSRF_TOKEN'];

                // Retrieve request parameters
                $state = $_REQUEST['state'];
                $code = $_REQUEST['code'];
                $authuser = $_REQUEST['authuser'];
                $prompt = $_REQUEST['prompt'];
                $session_state = $_REQUEST['session_state'];

                // Protect ourselves against CSRF
                if ($state != $csrf_token) {
                    header('HTTP/1.1 401 Unauthorized', true, 401);
                    exit(0);
                }

                // We're good. Let's get our tokens from Google
                $url = GoogleConfigBroker::getTokenEndpoint();
                $params = array(
                    "code" => $code,
                    "client_id" => $client_id,
                    "client_secret" => $client_secret,
                    "redirect_uri" => $redirect_uri,
                    "grant_type" => "authorization_code"
                );
                $curl = CURL::init($url);
                $curl->setContentTypeFormUrlEncoded();
                $json_response = $curl->post($params);
                $authObj = json_decode($json_response);

                // Now, get the user info
                $accessToken = $authObj->access_token;
                $idToken = $authObj->id_token;
                $url = GoogleConfigBroker::getUserinfoEndpoint() . "?access_token=" . $accessToken;
                $curl = CURL::init($url);
                $json_response = $curl->get();
                $userInfoObject = json_decode($json_response);
                $email = $userInfoObject->email;

                // Store OpenID auth info in the session
                if (isset($_SESSION['OPENID_AUTH'])) {
                    unset($_SESSION['OPENID_AUTH']);
                }
                $_SESSION['OPENID_AUTH'] = array(
                    'email' => $email
                );
                $adminUrl = $arrUri['basePath'] . 'admin';
                header("Location: $adminUrl");
            }
            break;
        default:
            $content = new HTML();
        }
    } else {
        $content = new HTML();
    }
} catch(Exception $e) {
    error_log($e, 3, dirname(__FILE__) . '/../php.log');
    die("An error occurred - we are looking into it.");
}
