<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class ZoearthImgCheckModelCheck extends ZoeModel
{
    static $haveZ2;
    //有哪些文章table
    public function haveZ2Tables()
    {
        if (!self::$haveZ2)
        {
            $session = JFactory::getSession();
            $haveZ2 = $session->get('haveZ2',0);
            if (!($haveZ2 > 0 ))
            {
                $tables = $this->DB->getTableList();
                $app = JFactory::getApplication();
                $prefix = $app->getCfg('dbprefix');
                if (array_search($prefix.'_z2_items', $tables))
                {
                    $session->set('haveZ2',1);
                    self::$haveZ2 = 1;
                }
                else
                {
                    $session->set('haveZ2',2);
                    self::$haveZ2 = 2;
                }
            }
            else
            {
                self::$haveZ2 = $haveZ2;
            }
        }
        return self::$haveZ2 == 1 ? TRUE:FALSE;
    }
    
    //取得內文的SRC
    public function getContentImgSrc($input='')
    {
        $images = array();
        preg_match_all('/(images[\\/][^?\'"]*)/', $input,$matches);
        if (is_array($matches[1]) && count($matches[1]) > 0 )
        {
            foreach ($matches[1] as $src)
            {
                $images[] = str_replace('\/','/',$src);
            }
        }
        return $images;
    }
    
    //取得所有資料內文的檔案
    public function getAllImgSrc()
    {
        $session   = JFactory::getSession();
        $allImgSrc = $session->get('allImgSrc');
        
        if (!$allImgSrc)
        {
            //是否有Z2表單
            $haveZ2 = $this->haveZ2Tables();
            
            //搜尋原生資料
            if ($haveZ2)
            {
                $Query = $this->DB->getQuery(true);
                $Query = $Query->select('id,CONCAT(i.introtext,i.fulltext) AS content')
                    ->from('#__content AS i')
                    ->where('CONCAT(i.introtext,i.fulltext) REGEXP \'images[\\/][^?\\\'"]*\' ');
                $this->DB->setQuery($Query);
                $rows = $this->DB->loadObjectList();
                foreach ($rows as $row)
                {
                    $images = $this->getContentImgSrc($row->content);
                    foreach ($images as $imgsrc)
                    {
                        $imgsrc = trim($imgsrc);
                        $allImgSrc[$imgsrc]['J_'.$row->id] = $row->id;
                    }
                }
            }
            else 
            {
                $Query = $this->DB->getQuery(true);
                $Query = $Query->select('id,CONCAT(i.introtext,i.fulltext) AS content')
                    ->from('#__content AS i')
                    ->where('CONCAT(i.introtext,i.fulltext) REGEXP \'images[\\/][^?\\\'"]*\' ');
                $this->DB->setQuery($Query);
                $rows = $this->DB->loadObjectList();
                foreach ($rows as $row)
                {
                    $images = $this->getContentImgSrc($row->content);
                    foreach ($images as $imgsrc)
                    {
                        $imgsrc = trim($imgsrc);
                        $allImgSrc[$imgsrc]['J_'.$row->id] = $row->id;
                    }
                }
            }
            //暫存
            $session->set('allImgSrc',$allImgSrc);
        }
        return $allImgSrc;
    }
    
    //20140414 zoearth 搜尋資料
    public function searchNoImgSrc()
    {
        //取得資料
        $allImgSrc = $this->getAllImgSrc();
        if (!(is_array($allImgSrc) && count($allImgSrc) > 0 ))
        {
            return FALSE;
        }
        
        foreach ($allImgSrc as $imgSrc=>$dataArray)
        {
            echo $imgSrc."\r\n";
        }
        
        
        
        exit();
        
        
        
        
        
        
        
        
        return $result;
    }
}