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
 * This class helps to load external libraries
 *
 * @category Default
 * @package  ExternalLibraryLoader
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class ExternalLibraryLoader
{
    protected $libs = array();
    protected $externalsDir = null;

    /**
     * Construct the array of libs
     *
     * @return void
     */
    function __construct()
    {
        $this->externalsDir = dirname(__FILE__) . '/../EXTERNALS';
        $result = array();
        if (file_exists("{$this->externalsDir}/libraries.json")) {
            $this->libs = (array) json_decode(file_get_contents("{$this->externalsDir}/libraries.json"));
        } else {
            $arrTree = self::recurse_dir($this->externalsDir, 0, 2);
            foreach ($arrTree as $path) {
                $newPath = substr($path, strlen($this->externalsDir) + 1);
                $arrPath = explode('/', $newPath);
                if (count($arrPath) > 1) {
                    $result[$arrPath[0]] = $arrPath[1];
                }
            }
            $handle = fopen("{$this->externalsDir}/libraries.json", 'w');
            if ($handle != false) {
                fwrite($handle, json_encode($result));
                fclose($handle);
            }
            $this->libs = $result;
        }
    }

    /**
     * Find the library you're searching for, and return the highest version number.
     *
     * @param string $library The library name to search for
     *
     * @return string Library version to load
     */
    function getVersion($library = '')
    {
        if (isset($this->libs[$library]) and file_exists($this->externalsDir . '/' . $library . '/' . $this->libs[$library])) {
            return $this->libs[$library];
        } else {
            if (file_exists($this->externalsDir . '/libraries.json')) {
                unlink($this->externalsDir . '/libraries.json');
            }
            $this->libs = array();
            $this->__construct();
            if (isset($this->libs[$library]) and file_exists($this->externalsDir . '/' . $library . '/' . $this->libs[$library])) {
                return $this->libs[$library];
            } else {
                return false;
            }
        }
    }

    /**
     * Parse the directories and then return an array of the directories
     *
     * @param string  $dirname  Starting path
     * @param integer $level    The current depth of the search
     * @param integer $maxdepth The maximum depth to search
     *
     * @return array Directories under this starting path
     */
    protected function recurse_dir($dirname = '.', $level = 0, $maxdepth = 0)
    {
        if ($maxdepth > 0 and $level >= $maxdepth) {
            return array();
        }
        $files = array();
        $dir = opendir($dirname . '/.');
        while ($dir && ($file = readdir($dir)) !== false) {
            $path = $dirname . '/' . $file;
            if (is_dir($path) and $file != '.' and $file != '..') {
                $files[$path] = $path;
                $files = array_merge($files, $this->recurse_dir($path, $level + 1, $maxdepth));
            } else {
                // Do nothing
            }
        }
        ksort($files);
        return $files;
    }
}
