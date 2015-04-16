<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class ZoearthImgCheckModelCheck extends ZoeModel
{
    //替換圖片
    
    //先行產生圖片
    public function preRenderImgToJpg($imgSrc)
    {
        //檢查DIR
        if (!is_dir(JPATH_ROOT.DS.'cache'))
        {
            mkdir(JPATH_ROOT.DS.'cache');
        }
        if (!is_dir(JPATH_ROOT.DS.'cache'.DS.'com_z2'))
        {
            mkdir(JPATH_ROOT.DS.'cache'.DS.'com_z2');
        }
        
        $ext = strtolower(substr($imgSrc,-3,3));
        try 
        {
            switch ($ext)
            {
                case "jpg":
                case "peg":
                    $im = imagecreatefromjpeg(JPATH_ROOT.DS.$imgSrc);
                    break;
                case "png":
                    $im = imagecreatefrompng(JPATH_ROOT.DS.$imgSrc);
                    break;
                case "gif":
                    $im = imagecreatefromgif(JPATH_ROOT.DS.$imgSrc);
                    break;
                case "bmp":
                    require_once JPATH_ADMINISTRATOR.DS.'components'.DS.'com_zoearth_img_check'.DS.'libraries'.DS.'BMP.php';
                    $im = imagecreatefrombmp(JPATH_ROOT.DS.$imgSrc);
                    break;
                default:
                    return FALSE;
                    break;
            }
            $tmpName = 'TMP_'.md5($imgSrc).'.jpg';
            @unlink(JPATH_ROOT.DS.'cache'.DS.'com_z2'.DS.$tmpName);
            @ImageJPEG($im,JPATH_ROOT.DS.'cache'.DS.'com_z2'.DS.$tmpName);
            return TRUE;
        }
        catch (Exception $e)
        {
            return FALSE;
        }
        return FALSE;
    }
    
    //替換圖片
    public function replcaeImgToJpg($imgSrc)
    {
        @unlink(JPATH_ROOT.DS.$imgSrc);
        $newImgSrc = $this->getNewJpgName($imgSrc);
        @copy(JPATH_ROOT.DS.'cache'.DS.'com_z2'.DS.'TMP_'.md5($imgSrc).'.jpg',JPATH_ROOT.DS.$newImgSrc);
    }
    
    //替換內容
    public function replaceContent($imgSrc,$imgItems=array())
    {
        if (is_array($imgItems) && count($imgItems) > 0 )
        {
            if (!$this->setContentData($imgItems,$imgSrc))
            {
                return FALSE;
            }
        }
        return TRUE;
    }
    
    //計算新的JPG名稱
    public function getNewJpgName($imgSrc)
    {
        $newImgSrc  = preg_replace('/(.*)\.([a-z]*)$/','$1',$imgSrc);
        if (strtolower(substr($imgSrc,-3,3)) != 'jpg' && is_file(JPATH_ROOT.DS.$newImgSrc.'.jpg') )
        {
            $newImgSrc = $newImgSrc.'_C';
        }
        return $newImgSrc.'.jpg';
    } 
    
    //替換功能
    public function setContentData($imgItems,$imgSrc)
    {
        $db = $this->DB;
        //原本
        $imgSrcJson = json_encode(array(0=>$imgSrc));
        $imgSrcJson = str_replace('["', '',$imgSrcJson);
        $imgSrcJson = str_replace('"]', '',$imgSrcJson);
        
        $imgSrcHtml = htmlentities($imgSrc);
        $imgSrcUrl  = urlencode($imgSrc);
        
        //新的
        $newImgSrc  = $this->getNewJpgName($imgSrc);
        
        $newImgSrcJson = json_encode(array(0=>$newImgSrc));
        $newImgSrcJson = str_replace('["', '',$newImgSrcJson);
        $newImgSrcJson = str_replace('"]', '',$newImgSrcJson);
        
        $newImgSrcHtml = htmlentities($newImgSrc);
        $newImgSrcUrl  = urlencode($newImgSrc);
        
        $replaceArray = array(
                $imgSrcJson => $newImgSrcJson,
                $imgSrcHtml => $newImgSrcHtml,
                $imgSrcUrl  => $newImgSrcUrl,
                $imgSrc     => $newImgSrc,
                );
        
        foreach ($imgItems as $keySet)
        {
            $keySet = explode('_',$keySet);
            $type   = $keySet[0];
            $dataId = $keySet[1];
            
            switch ($type)
            {
                case "J":
                    $Query = $db->getQuery(true);
                    $Query = $Query->select('i.id,i.introtext,i.fulltext')
                        ->from('#__content AS i')
                        ->where('i.id = '.(int)$dataId);
                    $db->setQuery($Query);
                    $row = $db->loadObject();
                    if (!$row)
                    {
                        return FALSE;
                    }
                    $row->introtext = strtr($row->introtext, $replaceArray);
                    $row->fulltext  = strtr($row->fulltext, $replaceArray);
            
                    $updateQuery = "UPDATE #__content SET
                        `introtext` = ".$db->quote($row->introtext).",
                        `fulltext` = ".$db->quote($row->fulltext)."
                        WHERE id = ".(int)$dataId;
                    $db->setQuery($updateQuery);
                    $db->execute();
                    break;
                case "ZI":
                    $dataIdArray = explode('|', $dataId);
                    $Query = $db->getQuery(true);
                    $Query = $Query->select('i.itemId,i.language,i.introtext,i.image,i.addPic,i.extra_fields')
                        ->from('#__z2_items_lang AS i')
                        ->where('i.itemId = '.(int)$dataIdArray[0])
                        ->where('i.language = '.$db->quote($dataIdArray[1]));
                    $db->setQuery($Query);
                    $row = $db->loadObject();
                    if (!$row)
                    {
                        return FALSE;
                    }
                    $row->introtext = strtr($row->introtext, $replaceArray);
                    $row->image = strtr($row->image, $replaceArray);
                    $row->addPic = strtr($row->addPic, $replaceArray);
                    $row->extra_fields = strtr($row->extra_fields, $replaceArray);
                
                    $updateQuery = "UPDATE #__z2_items_lang SET
                        `introtext` = ".$db->quote($row->introtext).",
                        `image` = ".$db->quote($row->image).",
                        `addPic` = ".$db->quote($row->addPic).",
                        `extra_fields` = ".$db->quote($row->extra_fields)."
                        WHERE itemId = ".(int)$dataIdArray[0]." AND language = ".$db->quote($dataIdArray[1]);
                    $db->setQuery($updateQuery);
                    $db->execute();
                    break;
                case "ZC":
                    $dataIdArray = explode('|', $dataId);
                    $Query = $db->getQuery(true);
                    $Query = $Query->select('i.catid,i.language,i.description,i.image,i.extra_fields')
                        ->from('#__z2_categories_lang AS i')
                        ->where('i.catid = '.(int)$dataIdArray[0])
                        ->where('i.language = '.$db->quote($dataIdArray[1]));
                    $db->setQuery($Query);
                    $row = $db->loadObject();
                    if (!$row)
                    {
                        return FALSE;
                    }
                    $row->description = strtr($row->description, $replaceArray);
                    $row->image = strtr($row->image, $replaceArray);
                    $row->extra_fields = strtr($row->extra_fields, $replaceArray);
                
                    $updateQuery = "UPDATE #__z2_categories_lang SET
                        `description` = ".$db->quote($row->description).",
                        `image` = ".$db->quote($row->image).",
                        `extra_fields` = ".$db->quote($row->extra_fields)."
                        WHERE catid = ".(int)$dataIdArray[0]." AND language = ".$db->quote($dataIdArray[1]);
                    $db->setQuery($updateQuery);
                    $db->execute();
                    break;
            }
        }
        return TRUE;
    }
    
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
                if (array_search($prefix.'z2_items', $tables))
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
                $src = $this->getSRC($src);
                if ($src)
                {
                    $images[] = $src;
                }
            }
        }
        return $images;
    }
    
    //取得SRC
    public function getSRC($src)
    {
        $src = trim($src);
        $src = str_replace('\/','/',$src);
        $src = str_replace(JPATH_ROOT,'',$src);
        $src = str_replace('\\','/',$src);
        
        if (substr($src,0,1) == '/')
        {
            $src = substr($src,1);
        }
        
        //是否為圖片(只針對圖片處理)
        $ext = substr($src,-3,3);
        if (in_array(strtolower($ext),array('jpg','peg','png','bmp','gif')))
        {
            return $src;
        }
        return FALSE;
    }
    
    //清除暫存
    public function cleanSession()
    {
        $session = JFactory::getSession();
        $session->clear('allImgSrc');
        $session->clear('haveZ2');
        $session->clear('allImgFile');
    }
    
    //讀取資料夾
    public function loadDir($dir,&$files)
    {
        if ($handle = opendir($dir))
        {
            while (false !== ($file = readdir($handle)))
            {
                if ($file == '.' || $file == '..' || substr($file,0,1) == '.')
                {
                    continue;
                }
                $file  = $dir.DS.$file;
                
                if (is_dir($file))
                {
                    $this->loadDir($file,$files);
                }
                else if (is_file($file))
                {
                    $src = $this->getSRC($file);
                    if ($src)
                    {
                        $files[$src] = array(
                                'time' => filemtime($file),
                                'size' => filesize($file),
                                ); 
                    }
                }
            }
        }
    }
    
    //取得所有檔案
    public function getAllImgFiles()
    {
        $session    = JFactory::getSession();
        $allImgFile = $session->get('allImgFile');
        
        if (!$allImgFile)
        {
            $dir = JPATH_ROOT.DS.'images';
            $files = array();
            $this->loadDir($dir,$files);
            
            $allImgFile = $files;
            //暫存
            $session->set('allImgFile',$allImgFile);
        }
        return $allImgFile;
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
            
            //搜尋Z2資料
            if ($haveZ2)
            {
                //ZI項目資料
                $Query = $this->DB->getQuery(true);
                $Query = $Query->select('itemId,language,CONCAT(i.introtext,\'"\',i.image,\'"\',i.addPic,\'"\',i.extra_fields) AS content')
                    ->from('#__z2_items_lang AS i')
                    ->where('i.introtext REGEXP \'images[\\/][^?\\\'"]*\' OR
                             i.image REGEXP \'images[\\/][^?\\\'"]*\' OR
                             i.addPic REGEXP \'images[\\/][^?\\\'"]*\' OR
                             i.extra_fields REGEXP \'images[\\/][^?\\\'"]*\' ');
                $this->DB->setQuery($Query);
                $rows = $this->DB->loadObjectList();
                foreach ($rows as $row)
                {
                    $images = $this->getContentImgSrc($row->content);
                    foreach ($images as $imgsrc)
                    {
                        $imgsrc = trim($imgsrc);
                        $allImgSrc[$imgsrc]['ZI_'.$row->id] = 'ZI_'.$row->itemId.'|'.$row->language;
                    }
                }
                
                //ZI分類資料
                $Query = $this->DB->getQuery(true);
                $Query = $Query->select('i.catid,i.language,CONCAT(i.description,\'"\',i.image,\'"\',i.extra_fields) AS content')
                    ->from('#__z2_categories_lang AS i')
                    ->where('i.description REGEXP \'images[\\/][^?\\\'"]*\' OR
                             i.image REGEXP \'images[\\/][^?\\\'"]*\' OR
                             i.extra_fields REGEXP \'images[\\/][^?\\\'"]*\'');
                
                $this->DB->setQuery($Query);
                $rows = $this->DB->loadObjectList();
                foreach ($rows as $row)
                {
                    $images = $this->getContentImgSrc($row->content);
                    foreach ($images as $imgsrc)
                    {
                        $imgsrc = trim($imgsrc);
                        $allImgSrc[$imgsrc]['ZC_'.$row->id] = 'ZC_'.$row->catid.'|'.$row->language;
                    }
                }
                
            }
            //搜尋原生資料
            else 
            {
                $Query = $this->DB->getQuery(true);
                $Query = $Query->select('id,CONCAT(i.introtext,\'"\',i.fulltext) AS content')
                    ->from('#__content AS i')
                    ->where('CONCAT(i.introtext,i.fulltext) REGEXP \'images[\\/][^?\\\'"]*\' ');
                $this->DB->setQuery($Query);
                $rows = $this->DB->loadObjectList();
                foreach ($rows as $row)
                {
                    $images = $this->getContentImgSrc($row->content);
                    foreach ($images as $imgsrc)
                    {
                        $allImgSrc[$imgsrc]['J_'.$row->id] = 'J_'.$row->id;
                    }
                }
            }
            //暫存
            $session->set('allImgSrc',$allImgSrc);
        }
        return $allImgSrc;
    }
}