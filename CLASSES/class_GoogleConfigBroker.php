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
 * This class provides all the Google configuration functions
 *
 * @category Default
 * @package  Brokers
 * @author   Yannick Mauray <yannick.mauray@euterpia-radio.fr>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */

class GoogleConfigBroker
{
    /**
     * Get the client id.
     * 
     * @return int
     */
    public static function getClientId() 
    {
        return ConfigBroker::getConfig('googleClientId', null);
    }

    /**
     * Get the client secret.
     * 
     * @return string
     */
    public static function getClientSecret() 
    {
        return ConfigBroker::getConfig('googleClientSecret', null);
    }

    /**
     * Get the "redirect url".
     * 
     * @return string
     */
    public static function getRedirectUri() 
    {
        return ConfigBroker::getConfig('googleRedirectUri', null);
    }

    /**
     * Get the authorization endpoint
     * 
     * @return string
     */
    public static function getAuthorizationEndpoint() 
    {
        $discovery_document = self::getDiscoveryDocument();
        return $discovery_document->authorization_endpoint;
    }

    /**
     * Get the token endpoint.
     * 
     * @return string
     */
    public static function getTokenEndpoint() 
    {
        $discovery_document = self::getDiscoveryDocument();
        return $discovery_document->token_endpoint;
    }

    /**
     * Get the userinfo endpoint
     * 
     * @return string
     */
    public static function getUserinfoEndpoint() 
    {
        $discovery_document = self::getDiscoveryDocument();
        return $discovery_document->userinfo_endpoint;
    }

    /**
     * Get the discovery document.
     * 
     * @return string
     */
    private static function getDiscoveryDocument() 
    {
        $curl = CURL::init("https://accounts.google.com/.well-known/openid-configuration");
        $json_response = $curl->get();
        $discovery_doc = json_decode($json_response);
        return $discovery_doc;
    }
}
