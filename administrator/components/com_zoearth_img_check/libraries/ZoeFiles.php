<?php
/**
 * @author      Zoearth
 */

class ZoeFiles
{
    public function get($guid=0) //20130801 zoearth 取得檔案列表
    {
        if (!($guid > 0))
        {
            JError::raiseError(500,"缺少檔案的 guid 參數");
        }
        
        $DB = JFactory::getDBO();
        $query = $DB->getQuery(true);
        $query->select('*')
            ->from("#__zoe_file")
            ->where("active = 1 ")
            ->where('guid = '.(int)$guid);
        $DB->setQuery($query);
        return (array)$DB->loadObject();
    }
    
    public function getAll($type='',$keyGuid=0,$controller='')//20130801 zoearth
    {
        if ($type=='' || $keyGuid == 0 || $controller == '' )
        {
            JError::raiseError(500,"缺少檔案的 type = '".$type."' ,keyGuid = '".$keyGuid."' ,controller = '".$controller."' 參數");
        }
        
        $DB = JFactory::getDBO();
        $query = $DB->getQuery(true);
        $query->select('*')
            ->from("#__zoe_file")
            ->where("type = ".$DB->quote($type))
            ->where('keyGuid = '.(int)$keyGuid)
            ->where('`option` = '.$DB->quote($controller))
            ->where("active = 1 ");
        $DB->setQuery($query);
        $rows = $DB->loadObjectList();
        $rowArray = array();
        foreach ($rows as $key=>$rowData)
        {
            $rowArray[$key] = (array)$rowData;
        }
        return $rowArray;       
    }
    
    public function create($type='',$keyGuid=0,$controller='',$iuser=0,$option=array())//20130801 zoearth 新增檔案
    {
        if (!(isset($_FILES[$type]['name'])))
        {
            return FALSE;
        }
        
        if ($type=='' || $keyGuid == 0 || $controller == '' || $iuser == 0 )
        {
            JError::raiseError(500,"缺少檔案的 type = '".$type."' ,keyGuid = '".$keyGuid."' ,controller = '".$controller."',iuser = '".$iuser."' 參數");
        }
        
        $inputArray = array();
        $haveAllows = 0;
        if (isset($option['allow']) && $option['allow'] != '')
        {
            $haveAllows = 1;
            $allows = explode('|',$option['allow']);
        }
        foreach ($_FILES[$type]['name'] as $k => $name)
        {
            if (!($_FILES[$type]['size'][$k] > 0 ))
            {continue;}
            if ($haveAllows)
            {
                if (!in_array(strtolower(substr($_FILES[$type]['name'][$k],-3,3)),$allows))
                {
                    continue;
                }
            }
            $inputArray[$k]['keyGuid'] = $keyGuid; //主要的guid
            $inputArray[$k]['option'] = $controller; //功能名稱(權限相關)
            //$inputArray[$k]['companyGuid'] = $this->_companyGuid; //20130823 zoearth 公司ID
            $inputArray[$k]['type'] = $type; //檔案類別(同一功能中不同的檔案上傳群組)
            $inputArray[$k]['filePath'] = $controller.'_'.md5(time().$_FILES[$type]['tmp_name'][$k]).'.dat'; //檔案存取路徑
            $inputArray[$k]['fileName'] = $_FILES[$type]['name'][$k]; //檔案名稱
            $inputArray[$k]['fileType'] = $_FILES[$type]['type'][$k]; //檔案類型
            $inputArray[$k]['fileSize'] = $_FILES[$type]['size'][$k]; //檔案大小
            $inputArray[$k]['note'] = $_POST[$type.'Note'][$k]; //備註
            $inputArray[$k]['active'] = 1;
            $inputArray[$k]['iuser'] = JFactory::getUser()->id; //主要的guid
            $inputArray[$k]['idate'] = DTIME; //主要的guid
        }
        
        $DB = JFactory::getDBO();
        $DB->transactionStart();
        try
        {
            foreach ($inputArray as $data)
            {
                $data = (object)$data;
                $DB->insertObject('#__zoe_file',$data);
                $lastId = $DB->insertid();
                $DB->transactionCommit();
            }
        }
        catch (Exception $e)
        {
            $DB->transactionRollback();
            JError::raiseError( 500,$e);
        }
        
        //20140430 zoearth 檢查資料夾是否存在
        if (!is_dir(JPATH_SITE.DS.'images'.DS.COM_NAME))
        {
            mkdir(JPATH_SITE.DS.'images'.DS.COM_NAME,0755);
        }
        
        //20130801 zoearth 新增DB資料完後 將檔案COPY過來
        foreach ($inputArray as $k => $data)
        {
            JFile::copy($_FILES[$type]['tmp_name'][$k],JPATH_SITE.DS.'images'.DS.COM_NAME.DS.$data['filePath']);
        }
        //20131007 zoearth 如果為單一檔案 則刪除舊的
        if (isset($option['onlyOne']) && $option['onlyOne'] == 1 && @$lastId > 0 )
        {
            $query = $DB->getQuery(true);
            $query->select('*')
                ->from("#__zoe_file")
                ->where("type = ".$DB->quote($type))
                ->where('keyGuid = '.(int)$keyGuid)
                ->where('`option` = '.$DB->quote($controller))
                ->where("active = 1 ")
                ->where('guid != '.(int)$lastId);
            $DB->setQuery($query);
            $deleteFiles = $DB->loadObjectList();
            foreach ($deleteFiles as $filesData)
            {
                if (JFile::delete(JPATH_SITE.DS.'images'.DS.COM_NAME.DS.$filesData->filePath))
                {
                    $data = (object)array('active' => 0,'guid'=> $filesData->guid );
                    $this->DB->updateObject('#__zoe_file',$data,'guid');
                }
            }
        }
        return TRUE;
    }
    
    public function delete($guid)
    {
        $DB = JFactory::getDBO();
        $query = $DB->getQuery(true);
        $query->select('*')
            ->from("#__zoe_file")
            ->where('guid = '.(int)$guid);
        $DB->setQuery($query);
        $fileData = (array)$DB->loadObject();
        if (!$fileData || !file_exists(JPATH_SITE.DS.'images'.DS.COM_NAME.DS.$fileData['filePath']))
        {
            //throw new exception( "找不到資料或檔案");
            //20131125 zoearth 不論檔案是否存在 都回傳成功
            //return FALSE;  
        }
        
        $DB->transactionStart();
        try
        {
            foreach ($inputArray as $data)
            {
                $data = (object)array('active' => 0,'guid'=> $fileData['guid']);
                $this->DB->updateObject('#__zoe_file',$data,'guid');
                $DB->transactionCommit();
            }
        }
        catch (Exception $e)
        {
            $DB->transactionRollback();
            JError::raiseError( 500,$e);
        }
        
        //20130801 zoearth 刪除DB資料完後 將檔案刪除過來
        //20131125 zoearth 不論檔案是否存在 都回傳成功
        JFile::delete(JPATH_SITE.DS.'images'.DS.COM_NAME.DS.$filesData['filePath']);
        return TRUE;
    }
}