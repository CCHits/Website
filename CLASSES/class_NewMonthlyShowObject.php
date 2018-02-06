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
 * This class extends internal show object class to create weekly shows.
 *
 * @category Default
 * @package  Objects
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */
class NewMonthlyShowObject extends NewInternalShowObject
{
    /**
     * Establish the creation of the new item by setting the values and then calling the create function.
     *
     * @param integer $intShowUrl The date of the show in YYYYMM format
     *
     * @return boolean Creation status
     */
    public function __construct($intShowUrl = 0)
    {
        $datShowUrl = date(
            "Y-m-d", strtotime(date("Y-m-d", strtotime(UI::getLongDate($intShowUrl) . '-01 + 1 month')) . ' - 1 day')
        );
        $status = parent::__construct($intShowUrl, 'monthly');
        $arrChart = ChartBroker::getChartByDate($datShowUrl, 0, 40);
        $arrChartTracks = $arrChart['position'];
        krsort($arrChartTracks);
        foreach ($arrChartTracks as $track) {
            $this->arrTracks[] = new NewShowTrackObject($track['intTrackID'], $this->intShowID);
        }
        return $this;
    }
}