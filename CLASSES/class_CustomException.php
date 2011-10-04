<?php
/**
 * CCHits.net is a website designed to promote Creative Commons Music,
 * the artists who produce it and anyone or anywhere that plays it.
 * These files are used to generate the site.
 *
 * PHP version 5
 *
 * @category Default
 * @package  Exceptions
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */
/**
 * This interface abstracts the base functionality for all custom exceptions
 *
 * @category Default
 * @package  Exceptions
 * @author   Robert Dunham (http://www.nilpo.com) <ask@nilpo.com>
 * @license  http://creativecommons.org/publicdomain/zero/1.0/ Creative Commons Zero (Waiver)
 * @link     http://www.php.net/manual/en/language.exceptions.php#91159
 */
interface IException
{
    /**
     * Mapping for Interface
     *
     * @return string Message
     */
    public function getMessage();

    /**
     * Mapping for Interface
     *
     * @return integer Exception code
     */
    public function getCode();
    /**
     * Mapping for Interface
     *
     * @return string Filename in which the exception occurred
     */
    public function getFile();

    /**
     * Mapping for Interface
     *
     * @return integer Line number where the error occurred
     */
    public function getLine();

    /**
     * Mapping for Interface
     *
     * @return object Trace?
     */
    public function getTrace();

    /**
     * Mapping for Interface
     *
     * @return string Trace
     */
    public function getTraceAsString();

    /**
     * Mapping for Interface
     *
     * @return string Nicely formatted output
     */
    public function __toString();

    /**
     * Mapping for Interface
     *
     * @param string  $message Message to be thrown
     * @param integer $code    Exception code to be thrown
     *
     * @return object Exception
     */
    public function __construct($message = null, $code = 0);
}

/**
 * This class provides the base functionality for all custom exceptions
 *
 * @category Default
 * @package  Exceptions
 * @author   Robert Dunham (http://www.nilpo.com) <ask@nilpo.com>
 * @license  http://creativecommons.org/publicdomain/zero/1.0/ Creative Commons Zero (Waiver)
 * @link     http://www.php.net/manual/en/language.exceptions.php#91159
 */
abstract class CustomException extends Exception implements IException
{
    protected $message = 'Unknown exception';     // Exception message
    private   $string;                            // Unknown
    protected $code    = 0;                       // User-defined exception code
    protected $file;                              // Source filename of exception
    protected $line;                              // Source line of exception
    private   $trace;                             // Unknown

    /**
     * Exception constructor
     *
     * @param string  $message Message to be thrown
     * @param integer $code    Exception code to be thrown
     *
     * @return object Exception
     */
    public function __construct($message = null, $code = 0)
    {
        if (!$message) {
            if (!isset($this->message) or !$this->message or $this->message == '') {
                throw new $this('Unknown '. get_class($this));
            } else {
                $message = $this->message;
            }
        }
        if ($code == 0 and isset($this->code)) {
            $code = $this->code;
        }
        parent::__construct($message, $code);
    }

    /**
     * Returns a nicely formatted output
     *
     * @return string Nicely formatted output
     */
    public function __toString()
    {
        return get_class($this) . " '{$this->message}' in {$this->file}({$this->line})\n"
                                . "{$this->getTraceAsString()}";
    }
}
