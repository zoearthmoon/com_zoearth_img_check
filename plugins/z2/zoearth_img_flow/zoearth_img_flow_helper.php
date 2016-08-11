<?php
defined('_JEXEC') or die ('Restricted access');

//20160811 zoearth 這邊是主要功能
class ZoearthImgFlowHelper 
{
    public static function render($response,&$setBody=FALSE)
    {
        $files = array();
        
        //取得所有圖片與JS與CSS
        if(!preg_match_all('/\"([^" ;]{1,}\.(jpg|png|gif|js|css))/i', $response,$match))
        {
            return FALSE;
        }
        
        if (!(is_array($match[1]) && count($match[1]) > 0 ))
        {
            return FALSE;
        }
        
        $sqlS = '';
        foreach ($match[1] as $file)
        {
            //整理絕對路徑
            $file = str_replace('http://'.$_SERVER['HTTP_HOST'].'','',$file);
            $file = str_replace('//','/',$file);
            $file = substr($file,0,1) != '/' ? '/'.$file:$file;
            
            //檔案是否存在
            if (!file_exists(JPATH_ROOT.$file))
            {
                continue;
            }
            
            //檔案時間(圖片皆為相同時間)
            $fileTime = '1970-01-01 00:00:00';
            //如果是 JS與CSS 則抓檔案時間
            //如果是JS與CSS則需要紀錄時間，其他都用 1970-01-01 00:00:00
            if (preg_match('/\.(js|css)$/',$file))
            {
                $fileTime = date('Y-m-d H:i:s',filemtime(JPATH_ROOT.$file));
            }
            $files[$file] = $fileTime;
            $sqlS .= $file.'","';
        }
        
        //搜尋資料庫
        $db = Z2HelperQueryData::getDB();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__zoearth_img_flow_files')
            ->where('fileName IN ("'.$sqlS.'")');
        $db->setQuery($query,0,999);
        $rows = $db->loadObjectList();
        //有則取代，無則寫入
        $reS = array();
        if ($rows && is_array($rows) && count($rows) > 0 )
        {
            foreach ($rows as $row)
            {
                //$row->fileName
                //$row->fileUrl
            }
        }
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        return $response;
    }
}