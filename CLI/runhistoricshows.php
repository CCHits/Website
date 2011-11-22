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

$db = Database::getConnection(true);
$query = $db->prepare("SELECT s.intShowUrl FROM shows as s, showtracks as st WHERE s.intShowID=st.intShowID and s.enumShowType = ?");
$query->execute(array('daily'));
$arrDailyShows = $query->fetchAll(PDO::FETCH_COLUMN);

foreach ($arrDailyShows as $showdate) {
    if ($showdate >= 20110101) {
        echo "Running show $showdate\r\n";
        $start = microtime(true);
        exec('php CLI/showmaker.php historic ' . $showdate);
        echo "Completed in " . round(microtime(true) - $start, 3) . " seconds\r\n";
    }
}
echo "All done in " . round(microtime(true) - $generator, 3) . " seconds\r\n";
