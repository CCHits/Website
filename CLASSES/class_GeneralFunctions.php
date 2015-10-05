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
 * This class performs non-class specific actions, such as checks on files to ensure they are valid,
 * or converts them from one format to another, or perform lookups against items to ensure they contain values.
 *
 * @category Default
 * @package  CCHitsClass
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
class GeneralFunctions
{
    /**
     * This function replaces the standard error logging, as it's not working on this dreamhost instance!
     */
    function error_log($message = null)
    {
        error_log($message, 3, dirname(__FILE__) . '/../../php.log');
    }

    /**
     * This function looks for a value within an array or an object, and returns it if it's there. If it isn't it
     * returns the default value.
     *
     * @param mixed   $haystack     The object or array to check within
     * @param string  $needle       The key or property to look for
     * @param mixed   $default      The value to return if the key or property doesn't exist
     * @param boolean $emptyisfalse If true, and the result of the check returns an empty string, return the default value
     *
     * @return mixed The value found, or the default if not.
     */
    public static function getValue($haystack = null, $needle = null, $default = false, $emptyisfalse = false)
    {
        if ($haystack != null && $needle !== null) {
            if (is_array($haystack) && count($haystack) > 0 && isset($haystack[$needle])) {
                if ($emptyisfalse == true && (string) $haystack[$needle] == '') {
                    return $default;
                } else {
                    return $haystack[$needle];
                }
            } elseif (is_object($haystack) && isset($haystack->$needle)) {
                if ($emptyisfalse == true && (string) $haystack->$needle == '') {
                    return $default;
                } else {
                    return $haystack->$needle;
                }
            } else {
                return $default;
            }
        } else {
            return $default;
        }
    }

    /**
     * Create an file suitable for temporary use. Create the directory to place the file in if it doesn't already exist.
     *
     * @param string $dirname The directory in which to put the file
     *
     * @return string The full pathname to the file to use.
     */
    public static function getTempFileName($dirname = '')
    {
        $here = dirname(__FILE__);
        if ($dirname == '') {
            $dirname = sys_get_temp_dir();
        }
        if (substr($dirname, -1) == '/') {
            $dirname = substr($dirname, 0, -1);
        }
        $state = file_exists($dirname);
        if (! $state) {
            $state = mkdir($dirname, umask(), true);
            if (! $state) {
                error_log("Unable to make directory $dirname");
                die("Error handling temporary files. Please contact an administrator.");
            }
        }
        if ( ! is_writable($dirname)) {
            error_log("Unable to write to $dirname");
            die("Error handling temporary files. Please contact an administrator.");
        }
        // This, apparently, may help to prevent race conditions: http://www.php.net/manual/en/function.tempnam.php#98232
        do {
            $file = $dirname . '/' . mt_rand();
            $fp = @fopen($file, 'x');
        } while (!$fp);
        fclose($fp);
        return realpath($file);
    }

    /**
     * Check the incoming files to ensure they are valid file formats that we can support
     *
     * @param string $filename The full path to the file to be checked
     *
     * @return string File format.
     */
    public static function getFileFormat($filename = '')
    {
        return GeneralFunctions::getValue(GeneralFunctions::getMediaAttributes($filename), 'fileformat', '');
    }
    
    /**
     * This function returns the length of a file in seconds, where possible.
     * 
     * @param string $filename The path of the file to process
     * 
     * @return long The length in seconds
     */
    public static function getFileLengthInSeconds($filename = '')
    {
        return GeneralFunctions::getValue(GeneralFunctions::getMediaAttributes($filename), 'playtime_seconds', '0.000');
    }

    /**
     * Return the length of a file in Hours:Minutes:Seconds, where possible.
     * 
     * @param string $filename The path of the file to process
     *
     * @return time The time in HH:MM:SS
     */
    public static function getFileLengthString($filename = '')
    {
        $time = GeneralFunctions::getValue(GeneralFunctions::getMediaAttributes($filename), 'playtime_string', '00:00:00');
        preg_match('/(\d\d):(\d\d):(\d\d)/', $time, $matches);
        if ($matches[1] > 0 && $matches[3] == 0) {
            $time = '00:' . $matches[1] . ':' . $matches[2];
        } else {
            $time = $matches[1] . ':' . $matches[2] . ':' . $matches[3];
        }
        return $time;
    }

    /**
     * One single function to load the id3lib library.
     * 
     * @param string $filename The filename to parse
     * 
     * @return array The media attributes parsed by the id3lib library.
     */
    public static function getMediaAttributes($filename)
    {
        $arrLibs = new ExternalLibraryLoader();
        $GETID3 = $arrLibs->getVersion("GETID3");
        if ($GETID3 == false) {
            error_log("Failed to load Media Handler - No library exists");
            die("There was an error - please contact an administrator.");
        }
        $getid3lib = dirname(__FILE__) . '/../EXTERNALS/GETID3/' . $GETID3 . '/getid3/getid3.php';
        if (file_exists($getid3lib)) {
            include_once $getid3lib;
        } else {
            error_log("Failed to load Media Handler - include file doesn't exist (looking for $getid3lib)");
            die("There was an error - please contact an administrator.");
        }
        $getID3 = new getID3;
        return $getID3->analyze($filename);
    }

    /**
     * This function converts an AAC file format into a WAV file.
     *
     * @param string  $in       Input filename
     * @param string  $out      Output filename
     * @param boolean $preserve Keep the input file after conversion
     *
     * @return boolean Operation success?
     */
    function ConvertAACtoWAV($in = null, $out = null, $preserve = false)
    {
        $faad = ConfigBroker::getAppConfig('faad', '/usr/bin/faad');
        $exec_command = "$faad -o \"$out\" \"$in\"";
        $exec_data = exec($exec_command, $exec_output, $return);
        if ($return == 0 and $preserve == false) {
            if (unlink($in)) {
                return true;
            } else {
                return false;
            }
        } elseif ($return == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * This helper function converts an M4A (MP4 audio) file format into a WAV file.
     *
     * @param string  $in       Input filename
     * @param string  $out      Output filename
     * @param boolean $preserve Keep the input file after conversion
     *
     * @return boolean Operation success?
     */
    function ConvertM4AtoWAV($in = null, $out = null, $preserve = false)
    {
        return self::ConvertAACtoWAV($in, $out, $preserve);
    }

    /**
     * This function converts an MP3 file format into a WAV file.
     *
     * @param string  $in       Input filename
     * @param string  $out      Output filename
     * @param boolean $preserve Keep the input file after conversion
     *
     * @return boolean Operation success?
     */
    function ConvertMP3toWAV($in = null, $out = null, $preserve = false)
    {
        $sox = ConfigBroker::getAppConfig('sox', '/usr/bin/sox');
        $exec_command = "$soxi \"$in\" \"$out\"";
        $exec_data = exec($exec_command, $exec_output, $return);
        if ($return == 0 and $preserve == false) {
            if (unlink($in)) {
                return true;
            } else {
                return false;
            }
        } elseif ($return == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * This helper function converts an OGA (OGG Audio) file format into a WAV file.
     *
     * @param string  $in       Input filename
     * @param string  $out      Output filename
     * @param boolean $preserve Keep the input file after conversion
     *
     * @return boolean Operation success?
     */
    function ConvertOGAtoWAV($in = null, $out = null, $preserve = false)
    {
        return self::ConvertMP3toWAV($in, $out, $preserve);
    }

    /**
     * This function converts a WAV file format into an AAC file.
     *
     * @param string  $in       Input filename
     * @param string  $out      Output filename
     * @param boolean $preserve Keep the input file after conversion
     *
     * @return boolean Operation success?
     */
    function ConvertWAVtoAAC($in = null, $out = null, $preserve = false)
    {
        $faac = ConfigBroker::getAppConfig('faac', '/usr/bin/faac');
        $exec_command = "$faac \"$in\" -o \"$out\"";
        $exec_data = exec($exec_command, $exec_output, $return);
        if ($return == 0 and $preserve == false) {
            if (unlink($in)) {
                return true;
            } else {
                return false;
            }
        } elseif ($return == 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * This helper function converts a WAV file format into an M4A (MP4 Audio) file.
     *
     * @param string  $in       Input filename
     * @param string  $out      Output filename
     * @param boolean $preserve Keep the input file after conversion
     *
     * @return boolean Operation success?
     */
    function ConvertWAVtoM4A($in = null, $out = null, $preserve = false)
    {
        return self::ConvertWAVtoAAC($in, $out, $preserve);
    }

    /**
     * This function converts a WAV file format into an MP3 file.
     *
     * @param string  $in       Input filename
     * @param string  $out      Output filename
     * @param boolean $preserve Keep the input file after conversion
     *
     * @return boolean Operation success?
     */
    function ConvertWAVtoMP3($in = null, $out = null, $preserve = false)
    {
        return self::ConvertMP3toWAV($in, $out, $preserve);
    }

    /**
     * This function converts a WAV file format into a OGA (OGG Audio) file.
     *
     * @param string  $in       Input filename
     * @param string  $out      Output filename
     * @param boolean $preserve Keep the input file after conversion
     *
     * @return boolean Operation success?
     */
    function ConvertWAVtoOGA($in = null, $out = null, $preserve = false)
    {
        return self::ConvertMP3toWAV($in, $out, $preserve);
    }

    /**
     * This function converts an AAC file format into an MP3 file via a WAV file.
     *
     * @param string  $in       Input filename
     * @param string  $out      Output filename
     * @param boolean $preserve Keep the input file after conversion
     *
     * @return boolean Operation success?
     */
    function ConvertAACtoMP3($in = null, $out = null, $preserve = false)
    {
        $temp = self::getTempFileName();
        if (self::ConvertAACtoWAV($in, $temp, true)) {
            if (self::ConvertWAVtoMP3($temp, $out, false)) {
                $state = true;
            } else {
                $state = false;
            }
        } else {
            $state = false;
        }
        if (file_exists($temp)) {
            unlink($temp);
        }
        if ($preserve == false and $state == true) {
            unlink($in);
        }
        return $state;
    }

    /**
     * This function converts an AAC file format into an M4A file via a WAV file.
     *
     * @param string  $in       Input filename
     * @param string  $out      Output filename
     * @param boolean $preserve Keep the input file after conversion
     *
     * @return boolean Operation success?
     */
    function ConvertAACtoM4A($in = null, $out = null, $preserve = false)
    {
        $temp = self::getTempFileName();
        if (self::ConvertAACtoWAV($in, $temp, true)) {
            if (self::ConvertWAVtoM4A($temp, $out, false)) {
                $state = true;
            } else {
                $state = false;
            }
        } else {
            $state = false;
        }
        if (file_exists($temp)) {
            unlink($temp);
        }
        if ($preserve == false and $state == true) {
            unlink($in);
        }
        return $state;
    }

    /**
     * This function converts an AAC file format into an OGA (OGG Audio) file via a WAV file.
     *
     * @param string  $in       Input filename
     * @param string  $out      Output filename
     * @param boolean $preserve Keep the input file after conversion
     *
     * @return boolean Operation success?
     */
    function ConvertAACtoOGA($in = null, $out = null, $preserve = false)
    {
        $temp = self::getTempFileName();
        if (self::ConvertAACtoWAV($in, $temp, true)) {
            if (self::ConvertWAVtoOGA($temp, $out, false)) {
                $state = true;
            } else {
                $state = false;
            }
        } else {
            $state = false;
        }
        if (file_exists($temp)) {
            unlink($temp);
        }
        if ($preserve == false and $state == true) {
            unlink($in);
        }
        return $state;
    }

    /**
     * This function converts an M4A file format into an MP3 file via a WAV file.
     *
     * @param string  $in       Input filename
     * @param string  $out      Output filename
     * @param boolean $preserve Keep the input file after conversion
     *
     * @return boolean Operation success?
     */
    function ConvertM4AtoMP3($in = null, $out = null, $preserve = false)
    {
        $temp = self::getTempFileName();
        if (self::ConvertM4AtoWAV($in, $temp, true)) {
            if (self::ConvertWAVtoMP3($temp, $out, false)) {
                $state = true;
            } else {
                $state = false;
            }
        } else {
            $state = false;
        }
        if (file_exists($temp)) {
            unlink($temp);
        }
        if ($preserve == false and $state == true) {
            unlink($in);
        }
        return $state;
    }

    /**
     * This function converts an M4A file format into an AAC file via a WAV file.
     *
     * @param string  $in       Input filename
     * @param string  $out      Output filename
     * @param boolean $preserve Keep the input file after conversion
     *
     * @return boolean Operation success?
     */
    function ConvertM4AtoAAC($in = null, $out = null, $preserve = false)
    {
        $temp = self::getTempFileName();
        if (self::ConvertM4AtoWAV($in, $temp, true)) {
            if (self::ConvertWAVtoAAC($temp, $out, false)) {
                $state = true;
            } else {
                $state = false;
            }
        } else {
            $state = false;
        }
        if (file_exists($temp)) {
            unlink($temp);
        }
        if ($preserve == false and $state == true) {
            unlink($in);
        }
        return $state;
    }

    /**
     * This function converts an M4A file format into an OGA (OGG Audio) file via a WAV file.
     *
     * @param string  $in       Input filename
     * @param string  $out      Output filename
     * @param boolean $preserve Keep the input file after conversion
     *
     * @return boolean Operation success?
     */
    function ConvertM4AtoOGA($in = null, $out = null, $preserve = false)
    {
        $temp = self::getTempFileName();
        if (self::ConvertM4AtoWAV($in, $temp, true)) {
            if (self::ConvertWAVtoOGA($temp, $out, false)) {
                $state = true;
            } else {
                $state = false;
            }
        } else {
            $state = false;
        }
        if (file_exists($temp)) {
            unlink($temp);
        }
        if ($preserve == false and $state == true) {
            unlink($in);
        }
        return $state;
    }

    /**
     * This function converts an MP3 file format into an OGA (OGG Audio) file via a WAV file.
     *
     * @param string  $in       Input filename
     * @param string  $out      Output filename
     * @param boolean $preserve Keep the input file after conversion
     *
     * @return boolean Operation success?
     */
    function ConvertMP3toOGA($in = null, $out = null, $preserve = false)
    {
        $temp = self::getTempFileName();
        if (self::ConvertMP3toWAV($in, $temp, true)) {
            if (self::ConvertWAVtoOGA($temp, $out, false)) {
                $state = true;
            } else {
                $state = false;
            }
        } else {
            $state = false;
        }
        if (file_exists($temp)) {
            unlink($temp);
        }
        if ($preserve == false and $state == true) {
            unlink($in);
        }
        return $state;
    }

    /**
     * This function converts an MP3 file format into an AAC file via a WAV file.
     *
     * @param string  $in       Input filename
     * @param string  $out      Output filename
     * @param boolean $preserve Keep the input file after conversion
     *
     * @return boolean Operation success?
     */
    function ConvertMP3toAAC($in = null, $out = null, $preserve = false)
    {
        $temp = self::getTempFileName();
        if (self::ConvertMP3toWAV($in, $temp, true)) {
            if (self::ConvertWAVtoAAC($temp, $out, false)) {
                $state = true;
            } else {
                $state = false;
            }
        } else {
            $state = false;
        }
        if (file_exists($temp)) {
            unlink($temp);
        }
        if ($preserve == false and $state == true) {
            unlink($in);
        }
        return $state;
    }

    /**
     * This function converts an MP3 file format into an M4A (MP4 Audio) file via a WAV file.
     *
     * @param string  $in       Input filename
     * @param string  $out      Output filename
     * @param boolean $preserve Keep the input file after conversion
     *
     * @return boolean Operation success?
     */
    function ConvertMP3toM4A($in = null, $out = null, $preserve = false)
    {
        $temp = self::getTempFileName();
        if (self::ConvertMP3toWAV($in, $temp, true)) {
            if (self::ConvertWAVtoM4A($temp, $out, false)) {
                $state = true;
            } else {
                $state = false;
            }
        } else {
            $state = false;
        }
        if (file_exists($temp)) {
            unlink($temp);
        }
        if ($preserve == false and $state == true) {
            unlink($in);
        }
        return $state;
    }

    /**
     * This function converts an OGA (OGG Audio) file format into an AAC file via a WAV file.
     *
     * @param string  $in       Input filename
     * @param string  $out      Output filename
     * @param boolean $preserve Keep the input file after conversion
     *
     * @return boolean Operation success?
     */
    function ConvertOGAtoAAC($in = null, $out = null, $preserve = false)
    {
        $temp = self::getTempFileName();
        if (self::ConvertOGAtoWAV($in, $temp, true)) {
            if (self::ConvertWAVtoAAC($temp, $out, false)) {
                $state = true;
            } else {
                $state = false;
            }
        } else {
            $state = false;
        }
        if (file_exists($temp)) {
            unlink($temp);
        }
        if ($preserve == false and $state == true) {
            unlink($in);
        }
        return $state;
    }

    /**
     * This function converts an OGA (OGG Audio) file format into an M4A (MP4 Audio) file via a WAV file.
     *
     * @param string  $in       Input filename
     * @param string  $out      Output filename
     * @param boolean $preserve Keep the input file after conversion
     *
     * @return boolean Operation success?
     */
    function ConvertOGAtoM4A($in = null, $out = null, $preserve = false)
    {
        $temp = self::getTempFileName();
        if (self::ConvertOGAtoWAV($in, $temp, true)) {
            if (self::ConvertWAVtoM4A($temp, $out, false)) {
                $state = true;
            } else {
                $state = false;
            }
        } else {
            $state = false;
        }
        if (file_exists($temp)) {
            unlink($temp);
        }
        if ($preserve == false and $state == true) {
            unlink($in);
        }
        return $state;
    }

    /**
     * This function converts an OGA (OGG Audio) file format into an MP3 file via a WAV file.
     *
     * @param string  $in       Input filename
     * @param string  $out      Output filename
     * @param boolean $preserve Keep the input file after conversion
     *
     * @return boolean Operation success?
     */
    function ConvertOGAtoMP3($in = null, $out = null, $preserve = false)
    {
        $temp = self::getTempFileName();
        if (self::ConvertOGAtoWAV($in, $temp, true)) {
            if (self::ConvertWAVtoMP3($temp, $out, false)) {
                $state = true;
            } else {
                $state = false;
            }
        } else {
            $state = false;
        }
        if (file_exists($temp)) {
            unlink($temp);
        }
        if ($preserve == false and $state == true) {
            unlink($in);
        }
        return $state;
    }
    
    /**
     * This function converts an array of strings (typically from the exec function
     * and returns a string complete with newlines.
     * 
     * @param array $array Input values
     * 
     * @return string
     */
    function array_to_string($array)
    {
        $return = '';
        foreach ($array as $item) {
            if ($return != '') {
                $return .= "\r\n";
            }
            $return .= $item;
        }
        return $return;
    }
}
