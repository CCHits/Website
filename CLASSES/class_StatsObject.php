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
 * This class provides all the functions for the stats
 *
 * @category Default
 * @package  Objects
 * @author   Yannick Mauray <yannick.mauray@gmail.com>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */

class StatsObject extends GenericObject
{
    private $_numberOfTracks = 0;
    private $_numberOfArtists = 0;
    private $_averageNumberOfTracksPerArtist = 0.0;
    private $_numberOfTracksPerLicense = [];
    private $_numberOfTracksPerLicenseCriteria = [];
    private $_top10LongestRunningTracksAtNumberOnePosition = [];

    /**
     * Add the collected generated data to the getSelf function
     *
     * @return array The amassed data from this function
     */
    function getSelf()
    {
        $return = parent::getSelf();

        $return['numberOfTracks'] = $this->_numberOfTracks;
        $return['numberOfArtists'] = $this->_numberOfArtists;
        $return['averageNumberOfTracksPerArtist'] = $this->_averageNumberOfTracksPerArtist;
        $return['numberOfTracksPerLicense'] = $this->_numberOfTracksPerLicense;
        $return['numberOfTracksPerLicenseCriteria'] = $this->_numberOfTracksPerLicenseCriteria;
        $return['top10LongestRunningTracksAtNumberOnePosition'] = $this->_top10LongestRunningTracksAtNumberOnePosition;

        return $return;
    }
    /**
     * Gets the number of tracks
     * 
     * @return void
     */
    function getNumberOfTracks()
    {
        return $this->_numberOfTracks;
    }

    /**
     * Sets the number of tracks
     * 
     * @param int $numberOfTracks number of tracks
     * 
     * @return void
     */
    function setNumberOfTracks($numberOfTracks)
    {
        $this->_numberOfTracks = $numberOfTracks;
    }

    /**
     * Gets the number of artists
     * 
     * @return void
     */
    function getNumberOfArtists()
    {
        return $this->_numberOfArtists;
    }

    /**
     * Sets the number of artists
     * 
     * @param int $numberOfArtists number of artists
     * 
     * @return void
     */
    function setNumberOfArtists($numberOfArtists)
    {
        $this->_numberOfArtists = $numberOfArtists;
    }

    /**
     * Gets the average number of tracks per artist
     * 
     * @return void
     */
    function getAverageNumberOfTracksPerArtist()
    {
        return $this->_averageNumberOfTracksPerArtist;
    }

    /**
     * Sets the average number of tracks per artist
     * 
     * @param number $averageNumberOfTracksPerArtist average number or tracks per artist
     * 
     * @return void
     */
    function setAverageNumberOfTracksPerArtist($averageNumberOfTracksPerArtist)
    {
        $this->_averageNumberOfTracksPerArtist = $averageNumberOfTracksPerArtist;
    }

    /**
     * Gets the number of tracks per license
     * 
     * @return void
     */
    function getNumberOfTracksPerLicense()
    {
        return $this->_numberOfTracksPerLicense;
    }

    /**
     * Sets the number of tracks per license
     * 
     * @param number $numberOfTracksPerLicense number or tracks per license
     * 
     * @return void
     */
    function setNumberOfTracksPerLicense($numberOfTracksPerLicense)
    {
        $this->_numberOfTracksPerLicense = $numberOfTracksPerLicense;
    }
    
    /**
     * Getter
     * 
     * @return void
     */
    function getNumberOfTracksPerLicenseCriteria()
    {
        return $this->_numberOfTracksPerLicenseCriteria;
    }

    /**
     * Setter
     * 
     * @param number $numberOfTracksPerLicenseCriteria Number of tracks per license criteria
     * 
     * @return void
     */
    function setNumberOfTracksPerLicenseCriteria($numberOfTracksPerLicenseCriteria)
    {
        $this->_numberOfTracksPerLicenseCriteria = $numberOfTracksPerLicenseCriteria;
    }

    /**
     * Getter
     * 
     * @return void
     */
    function getTop10LongestRunningTracksAtNumberOnePosition()
    {
        return $this->_top10LongestRunningTracksAtNumberOnePosition;
    }

    /**
     * Setter
     * 
     * @param number $top10LongestRunningTracksAtNumberOnePosition top 10 longest running tracks at number one position.
     * 
     * @return void
     */
    function setTop10LongestRunningTracksAtNumberOnePosition($top10LongestRunningTracksAtNumberOnePosition)
    {
        $this->_top10LongestRunningTracksAtNumberOnePosition = $top10LongestRunningTracksAtNumberOnePosition;
    }
}