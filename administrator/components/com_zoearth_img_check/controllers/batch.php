<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');
exit();
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
			/*
            ->where('
                    `introtext` REGEXP "images\/blog\/([0-9]{6})([^\"\'\/]{1,}\.[^\"\'\/]{1,})" OR
                    `fulltext`  REGEXP "images\/blog\/([0-9]{6})([^\"\'\/]{1,}\.[^\"\'\/]{1,})" OR
                    `image`     REGEXP "images\/blog\/([0-9]{6})([^\"\'\/]{1,}\.[^\"\'\/]{1,})"
                   ')
				   */
            ->where('
                    `addPic`     REGEXP "images"
                   ')
			->where('itemId >= 600 ')
			->where('itemId <= 1900 ')
            ->order('itemId');
        $db->setQuery($query,0,200);
        $rows = $db->loadObjectList();
        echo $query.'  query ';
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
            
			/*
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
            */
            $jaddPic = json_decode($row->addPic,TRUE);
			if (is_array($jaddPic) && count($jaddPic) > 0 )
			{
				foreach ($jaddPic as $key=>$addPic)
				{
					$new_pic = $this->newContent($addPic['pic']);
					if (trim($addPic['pic']) != $new_pic)
					{
						$addPic['pic'] = $new_pic;
						$jaddPic[$key] = $addPic;
						$needUpdate = TRUE;
					}
				}
				if ($needUpdate)
				{
					$query->set('addPic = '.$db->quote(json_encode($jaddPic)));
				}
			}

			
            if ($needUpdate)
            {
                $query->where('itemId   = '. $db->quote($row->itemId));
                $query->where('language = '. $db->quote($row->language));
                
                //echo $query;
                echo $row->itemId.' ---DONE<br>';
                //echo $query;
				
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
        $input = trim($input);
        if (!preg_match_all('/images\/blog\/([0-9]{6})([^\"\'\/]{1,}\.[^\"\'\/]{1,})/','"'.$input.'"',$match))
        {
            return $input;
        }
        
        //print_r($match);
        //20160128 zoearth 新增資料夾
        foreach ($match[1] as $year)
        {
            if (!is_dir(JPATH_ROOT.DS.'images'.DS.'blog'.DS.$year))
            {
                @mkdir(JPATH_ROOT.DS.'images'.DS.'blog'.DS.$year);
            }
        }
        
        foreach ($match[0] as $key=>$img)
        {
            $oldImg = JPATH_ROOT.DS.$img;
            $oldStr = $img;
            if (!is_file($oldImg))
            {
                echo '找不到圖片!:'.$img.'<br>';
                continue;
            }
            
            $newImg = JPATH_ROOT.DS.'images'.DS.'blog'.DS.$match[1][$key].DS.$match[1][$key].$match[2][$key];
            $newStr = 'images/blog/'.$match[1][$key].'/'.$match[1][$key].$match[2][$key];
            if (!is_file($newImg))
            {
                @copy($oldImg,$newImg);
            }
            
            //取代
            //echo 'IMG:'.$oldStr.'<br>'.$newStr.'<br>';
            $input = str_replace($oldStr,$newStr,$input);
        }
        
        return $input;
    }
}