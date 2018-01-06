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
 * @author   Yannick Mauray <yannick.mauray@gmail.com>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */
/**
 * This class handles all HTML requests for the "/dev
 *
 * @category Default
 * @package  UI
 * @author   Yannick Mauray <yannick.mauray@gmail.com>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */
class Developer
{
    protected $result = array();
    protected $response_code = 200;
    protected $format = 'html';
    protected $arrUri = array();

    /**
     * The function which handles the routing
     *
     * @return void
     */
    function __construct()
    {
        $this->extLib = new ExternalLibraryLoader();
        $this->arrUri = UI::getUri();

        UI::start_session();

        if (count($this->arrUri['path_items']) == 1) {
            if (isset($_SESSION['intDeveloperID'])) {
                UI::Redirect('developer/applications');
            } else {
                if ($this->_render()) {
                    UI::SmartyTemplate("developer.html", $this->result);
                }
            }
        } else {
            switch ($this->arrUri['path_items'][1]) {
            case 'login':
                if ($this->_render()) {
                    UI::SmartyTemplate("developer.login.html", $this->result);
                }
                break;
            case 'logout':
                unset($_SESSION['intDeveloperID']);
                UI::Redirect('');
            case 'signup':
                if (isset($this->arrUri["parameters"]) && is_array($this->arrUri["parameters"]) && (count($this->arrUri["parameters"]) > 0)) {
                    $parameters = $this->arrUri["parameters"];
                    $email = $parameters['email'];
                    $password = $parameters['password'];
                    $confirmation = $parameters['confirmation'];
                    if ($password === $confirmation) {
                        $emailExists = DeveloperBroker::checkEmail($email);
                        if (!$emailExists) {
                            DeveloperBroker::createDeveloper($email, $password);
                            $developer = DeveloperBroker::getDeveloperByCredentials($email, $password);
                            $_SESSION['intDeveloperID'] = $developer->getID();
                            UI::Redirect("developer/applications");
                            break;
                        } else {
                            $_SESSION['message'] = 'This email is already registered';
                            UI::Redirect("developer/signup");
                        }
                    } else {
                        $_SESSION['message'] = "Passwords don't match";
                        UI::Redirect('developer/signup');
                        break;
                    }
                } else {
                    if ($this->_render()) {
                        UI::SmartyTemplate("developer.signup.html", $this->result);
                        break;
                    }
                }
                break;
            case 'session':
                if (isset($this->arrUri["parameters"]) && is_array($this->arrUri["parameters"])) {
                    $parameters = $this->arrUri["parameters"];
                    $email = $password = null;
                    if (array_key_exists("email", $parameters)) {
                        $email = $parameters["email"];
                    }
                    if (array_key_exists("password", $parameters)) {
                        $password = $parameters["password"];
                    }
                }
                $developer = DeveloperBroker::getDeveloperByCredentials($email, $password);
                if ($developer === false) {
                    if ($this->_render()) {
                        $this->result['message'] = "Incorrect email or password. Please try again.";
                        UI::SmartyTemplate("developer.login.html", $this->result);
                    }
                } else {
                    $_SESSION['intDeveloperID'] = $developer->getID();
                    if ($this->_render()) {
                        UI::Redirect("developer/applications");
                    }                    
                }
                break;
            case 'applications':
                if (isset($_SESSION['intDeveloperID'])) {
                    $intDeveloperID = $_SESSION['intDeveloperID'];
                    $developper = DeveloperBroker::getDeveloperByID($intDeveloperID);
                    if ($developper === false) {
                        UI::Redirect("");
                        die();
                    }
                    $applications = ApplicationBroker::getApplicationsForDeveloper($developper->getID());
                    if ($this->_render()) {
                        $apps = [];
                        foreach ($applications as $application) {
                            $apps[] = $application->getSelf();
                        }
                        $this->result['applications'] = $apps;
                        UI::SmartyTemplate("developer.applications.html", $this->result);
                        break;
                    }
                } else {
                    UI::Redirect("");
                    break;
                }
            case 'new':
                if (isset($_SESSION['intDeveloperID'])) {
                    if (isset($this->arrUri["parameters"]) && is_array($this->arrUri["parameters"]) && (count($this->arrUri["parameters"]) > 0)) {
                        $p = $this->arrUri["parameters"];
                        if (isset($p['name']) && isset($p['description'])) {
                            ApplicationBroker::createApplication($_SESSION['intDeveloperID'], $p['name'], $p['description'], $p['url']);
                            $_SESSION['message'] = "Application created successfully";
                            UI::Redirect("developer/applications");
                            break;
                        }
                    } else {
                        $intDeveloperID = $_SESSION['intDeveloperID'];
                        $developper = DeveloperBroker::getDeveloperByID($intDeveloperID);
                        if ($developper !== false) {
                            if ($this->_render()) {
                                UI::SmartyTemplate("developer.newapp.html", $this->result);
                                break;
                            }
                        }
                    }
                }
                UI::Redirect("");
                break;
            case 'account':
                if (isset($_SESSION['intDeveloperID'])) {
                    if ($this->_render()) {
                        $developer = DeveloperBroker::getDeveloperByID($_SESSION['intDeveloperID']);
                        $this->result["developer"] = $developer->getSelf();
                        UI::SmartyTemplate("developer.account.html", $this->result);
                    }
                    break;
                } else {
                    UI::Redirect("");
                    break;
                }
            case 'details':
                if (isset($_SESSION['intDeveloperID'])) {
                    $intDeveloperID = $_SESSION['intDeveloperID'];
                    if (isset($this->arrUri["parameters"]) && is_array($this->arrUri["parameters"])) {
                        $parameters = $this->arrUri["parameters"];
                        $password = $parameters['password'];
                        $confirmation = $parameters['confirmation'];
                        if ($password === $confirmation) {
                            DeveloperBroker::updatePassword($intDeveloperID, $password);
                            $_SESSION['message'] = "Password updated successfully";
                            UI::Redirect("developer/account");
                            break;
                        } else {
                            $_SESSION['message'] = "Passwords don't match";
                            UI::Redirect("developer/account");
                            break;
                        }
                    }
                }
                UI::Redirect("");
                break;
            default:
                UI::Redirect("");
                break;
            }
        }
    }

    /**
     * This function sets the environnement for rendering the page.
     * 
     * @return boolean true|false
     */
    private function _render()
    {
        $this->result['ServiceName'] = ConfigBroker::getConfig('ServiceName', 'CCHits');
        $this->result['baseURL'] = $this->arrUri['basePath'];
        $this->result['ShowDaily'] = ConfigBroker::getConfig('Daily Show Name', 'Daily Exposure Show');
        $this->result['ShowWeekly'] = ConfigBroker::getConfig('Weekly Show Name', 'Weekly Review Show');
        $this->result['ShowMonthly'] = ConfigBroker::getConfig('Monthly Show Name', 'Monthly Chart Show');
        $this->result['bootstrap4'] = $this->extLib->getVersion('BOOTSTRAP4');
        $this->result['jquery3'] = $this->extLib->getVersion('JQUERY3');
        $this->result['popperjs'] = $this->extLib->getVersion('POPPERJS');
        $this->result['chartjs'] = $this->extLib->getVersion('CHARTJS');
        $this->result['fontawesome'] = $this->extLib->getVersion('FONTAWESOME');
        $this->result['jplayer29'] = $this->extLib->getVersion('JPLAYER29');
        if (isset($_SESSION['message'])) {
            $this->result['message'] = $_SESSION['message'];
            unset($_SESSION['message']);
        }
        return true;
    }
}
