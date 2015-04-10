<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');

//共用繼承
class ZoeModel extends JModelLegacy
{
    public $DB = NULL;
    public $total = 0;
    
    function __construct()
    {
        $this->DB = JFactory::getDbo();
        parent::__construct();
    }
    
    //20140424 zoearth 這邊會直接執行查詢 與 取得總數
    function getAllObjectList($Query,$start=0,$limit=0)
    {
        $Query = str_replace('SELECT ', 'SELECT SQL_CALC_FOUND_ROWS ', $Query);
        if ($limit > 0 )
        {
            $this->DB->setQuery($Query,(int)$start,(int)$limit);
        }
        else
        {
            $this->DB->setQuery($Query);
        }
        
        $row = $this->DB->loadObjectList();
        
        $Query = $this->DB->getQuery(true);
        
        if ($limit > 0 )
        {
            $this->DB->setQuery('SELECT FOUND_ROWS() as total;');
            $result = $this->DB->loadObject();
            $this->total = $result->total;
        }
        else
        {
            $this->total = count($row);
        }

        $rowArray = array();
        foreach ($row as $key=>$rowData)
        {
            $rowArray[$key] = (array)$rowData;
        }
        return $rowArray;
    }
    
    //20140424 zoearth 直接回傳總數
    function getAllCount()
    {
        return $this->total;
    }
    
}