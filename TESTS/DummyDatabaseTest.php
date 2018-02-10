<?php
/**
 * CCHits.net is a website designed to promote Creative Commons Music,
 * the artists who produce it and anyone or anywhere that plays it.
 * These files are used to generate the site.
 *
 * PHP version 5
 *
 * @category Default
 * @package  Tests
 * @author   Yannick Mauray <yannick.mauray@gmail.com>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */

require 'GenericDatabaseTestCase.php';

/**
 * This class is a dummy unit test to check if the system works.
 *
 * @category Default
 * @package  Tests
 * @author   Yannick Mauray <yannick.mauray@gmail.com>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */

final class DummyDatabaseTest extends GenericDatabaseTestCase
{
    /**
     * Returns the dataset needed for this test.
     * 
     * @return PHPUnit_Extensions_Database_DataSet_IDataSet
     */
    public function getDataSet()
    {
        return $this->createMySQLXMLDataSet(dirname(__FILE__) . '/_files/dummy-seed.xml');
    }

    /**
     * Dummy test.
     * 
     * @return void
     */
    public function testDummy()
    {
        $db = Database::getConnection();
        $this->assertEquals(3, $this->getConnection()->getRowCount('artists'));
    }
}