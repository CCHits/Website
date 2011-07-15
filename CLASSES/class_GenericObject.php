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
 * This class deals with the generic base of any of the other objects
 *
 * @category Default
 * @package  Objects
 * @author   Jon Spriggs <jon@sprig.gs>
 * @license  http://www.gnu.org/licenses/agpl.html AGPLv3
 * @link     http://cchits.net Actual web service
 * @link     http://code.cchits.net Developers Web Site
 * @link     http://gitorious.net/cchits-net Version Control Service
 */

class GenericObject
{
    protected $arrDBItems = array();
    protected $strDBTable = "";
    protected $strDBKeyCol = "";
    protected $arrChanges = array();
    
    /**
     * Commit any changes to the database
     *
     * @return void
     */
    function write()
    {
        if (count($this->arrChanges) > 0) {
            $sql = '';
            $values = array('intUserID'=>$this->intUserID);
            foreach ($this->arrChanges as $change) {
                if ($sql != '') {
                    $sql .= ", ";
                }
                if (isset($this->arrDBItems[$change])) {
                    $sql .= "$change = :$change";
                    $values[$change] = $this->$change;
                }
            }
            $full_sql = "UPDATE {$this->strDBTable} SET $sql WHERE {$this->strDBKeyCol} = :{$this->strDBKeyCol}";
            try {
                $db = CF::getFactory()->getConnection();
                $query = $db->prepare($full_sql);
                $query->execute($values);
                return true;
            } catch(Exception $e) {
                return false;
            }
        }
    }
    
    /**
     * Create the object
     *
     * @return boolean status of the create operation
     */
    protected function create()
    {
        $keys = '';
        $key_place = '';
        foreach ($this->arrDBItems as $field_name=>$dummy) {
            if ($keys != '') {
                $keys .= ', ';
                $key_place .= ', ';
            }
            $keys .= $field_name;
            $key_place .= ":$field_name";
            $values[$field_name] = $this->$field_name;
        }
        $full_sql = "INSERT INTO {$this->strDBTable} ($keys) VALUES ($key_place)";
        try {
            $db = CF::getFactory()->getConnection();
            $query = $db->prepare($full_sql);
            $query->execute($values);
            if ($this->strDBKeyCol != '') {
                $key = $this->strDBKeyCol;
                $this->$key = $query->lastInsertId();
            }
            return true;
        } catch(Exception $e) {
            return false;
        }
    }
    
    /**
     * Return an array of the collected or created data.
     *
     * @return array A mixed array of these items
     */
    function getSelf()
    {
        if ($this->strDBKeyCol != '') {
            $key = $this->strDBKeyCol;
            $return[$key] = $this->$key;
        }
        foreach ($this->arrDBItems as $key=>$dummy) {
            $return[$key] = $this->$key;
        }
        return $return;
    }
    
    /**
     * Return boolean true for 1 and boolean false for 0
     * 
     * @param integer $check Value to check
     * 
     * @return boolean Result
     */
    function asBoolean($check)
    {
        switch($check) {
        case '1':
        case 'true':
        case 'yes':
            return true;
        default:
            return false;    
        }
    }
}
