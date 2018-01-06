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
 * This class knows how to do everything with Developer Objects
 *
 * @category Default
 * @package  Brokers
 * @author   Yannick Mauray <yannick.mauray@gmail.com>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     https://github.com/CCHits/Website/wiki Developers Web Site
 * @link     https://github.com/CCHits/Website Version Control Service
 */
class DeveloperBroker
{
    /**
     * This function gets a developer from the database, given their email and password.
     * 
     * @param string $email    the developer's email
     * @param string $password the developer's password
     *
     * @return DeveloperObject|false DeveloperObject or false if the developer was not found.
     */
    public static function getDeveloperByCredentials($email, $password)
    {
        $db = Database::getConnection();
        $sql = "SELECT * FROM developers WHERE strEmail = ? AND strPassword = PASSWORD(?) LIMIT 1";
        $query = $db->prepare($sql);
        $values = [$email, $password];
        $query->execute($values);
        // This section of code, thanks to code example here:
        // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
        if ($query->errorCode() != 0) {
            throw new Exception(
                "SQL Error: " . print_r(array('sql'=>$sql, 'values'=>$values, 'error'=>$query->errorInfo()), true), 1
            );
        }
        $result = $query->fetchObject('DeveloperObject');
        return $result;
    }

    /**
     * This function returns a developer from the database, given their intDeveloperID
     * 
     * @param integer $intDeveloperID developer ID
     * 
     * @return DeveloperObject|false DeveloperObject or false if the developer was not found.
     */
    public static function getDeveloperByID($intDeveloperID)
    {
        $db = Database::getConnection();
        $sql = "SELECT * FROM developers WHERE intDeveloperID = ?";
        $query = $db->prepare($sql);
        $values = [$intDeveloperID];
        $query->execute($values);
        // This section of code, thanks to code example here:
        // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
        if ($query->errorCode() != 0) {
            throw new Exception(
                "SQL Error: " . print_r(array('sql'=>$sql, 'values'=>$values, 'error'=>$query->errorInfo()), true), 1
            );
        }
        $result = $query->fetchObject('DeveloperObject');
        return $result;
    }

    /**
     * This function updates a developer's password.
     * 
     * @param integer $intDeveloperID Developer's ID
     * @param string  $strPassword    the new password.
     * 
     * @return void
     */
    public function updatePassword($intDeveloperID, $strPassword)
    {
        $db = Database::getConnection();
        $sql = "UPDATE developers SET strPassword = PASSWORD(?) WHERE intDeveloperID = ?";
        $query = $db->prepare($sql);
        $values = [$strPassword, $intDeveloperID];
        $query->execute($values);
        // This section of code, thanks to code example here:
        // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
        if ($query->errorCode() != 0) {
            throw new Exception(
                "SQL Error: " . print_r(array('sql'=>$sql, 'values'=>$values, 'error'=>$query->errorInfo()), true), 1
            );
        }
        return;
    }

    /**
     * This function checks if the given email is associated with a developer.
     * 
     * @param string $strEmail the email to check against.
     * 
     * @return bool true if the email is present, false if not.
     */
    public static function checkEmail($strEmail)
    {
        $db = Database::getConnection();
        $sql = "SELECT strEmail FROM developers WHERE strEmail = ?";
        $query = $db->prepare($sql);
        $values = [$strEmail];
        $query->execute($values);
        // This section of code, thanks to code example here:
        // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
        if ($query->errorCode() != 0) {
            throw new Exception(
                "SQL Error: " . print_r(array('sql'=>$sql, 'values'=>$values, 'error'=>$query->errorInfo()), true), 1
            );
        }
        $result = $query->fetchObject('DeveloperObject');
        return !($result === false);        
    }

    /**
     * This function creates a new developer in the database.
     * 
     * @param string $strEmail    the developer's email.
     * @param string $strPassword the developer's password.
     * 
     * @return void
     */
    public static function createDeveloper($strEmail, $strPassword)
    {
        $db = Database::getConnection();
        $sql = "INSERT INTO developers(strEmail, strPassword) VALUES (?, PASSWORD(?))";
        $query = $db->prepare($sql);
        $values = [$strEmail, $strPassword];
        $query->execute($values);
        // This section of code, thanks to code example here:
        // http://www.lornajane.net/posts/2011/handling-sql-errors-in-pdo
        if ($query->errorCode() != 0) {
            throw new Exception(
                "SQL Error: " . print_r(array('sql'=>$sql, 'values'=>$values, 'error'=>$query->errorInfo()), true), 1
            );
        }
    }
}
