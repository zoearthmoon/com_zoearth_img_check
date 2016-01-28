<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');

class ZoearthImgCheckControllerBatch extends JControllerLegacy
{
    function display($cachable = false, $urlparams = false)
    {
        /*
        * 20160128 zoearth 批次修改
        * 修改內容 blog/2016101001.jpg 改成 blog/201610/2016101001.jpg
        * 新增 資料夾 移動檔案
        * DB: 
        * #__z2_categories_lang(none)
        * #__z2_items_lang
        *    introtext
        *    fulltext
        *    image
        * 
        */
        
        //搜尋
        $db =& JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__z2_items_lang')
            ->where('
                    `introtext` LIKE "%images/blog/%" OR
                    `fulltext`  LIKE "%images/blog/%" OR
                    `image`     LIKE "%images/blog/%"
                   ')
            ->order('itemId');
        $db->setQuery($query,0,10);
        $rows = $db->loadObjectList();
        
        if (!$rows)
        {
            echo 'ERROR 0040 沒有找到資料';
            return FALSE;
        }
        
        //取值
        foreach ($rows as $row)
        {
            $query = $db->getQuery(true);
            $query->update("#__z2_items_lang");
            
            $upData = array();
            $needUpdate = FALSE;
            
            $new_introtext = $this->newContent($row->introtext);
            if (trim($row->introtext) != $new_introtext)
            {
                $query->set('introtext = '.$db->quote($new_introtext));
                $needUpdate = TRUE;
            }
            $new_fulltext = $this->newContent($row->fulltext);
            if (trim($row->fulltext) != $new_fulltext)
            {
                $query->set('fulltext = '.$db->quote($new_fulltext));
                $needUpdate = TRUE;
            }
            $new_image = $this->newContent($row->image);
            if (trim($row->image) != $new_image)
            {
                $query->set('image = '.$db->quote($new_image));
                $needUpdate = TRUE;
            }
            
            if ($needUpdate)
            {
                $query->where('itemId   = '. $db->quote($row->itemId));
                $query->where('language = '. $db->quote($row->language));
                $db->setQuery($query);
                if (!$db->execute())
                {
                    echo 'ERROR 0073 更新失敗';
                    return FALSE;
                }
            }
        }
        
        echo 'Done!!';
        return FALSE;
    }
    
    //複製新檔案 新增資料夾 但是不刪除舊檔案(全部都確定後再刪除)
    function newContent($input='')
    {
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
    }
}