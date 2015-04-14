<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');

define('CONTROLLER','Check');
define('CONTROLLER_NAME','文字增修');
define('CONTROLLER_BASE_URL',Juri::base().'index.php?option='.COM_NAME.'&view=check');

jimport('joomla.application.component.helper');

class ZoearthImgCheckControllerCheck extends ZoeController
{
    function display($cachable = false, $urlparams = false)
    {
        $this->index();
    }
    
    function index()
    {
        //20140425 zoearth Joomla 必須先設定模板
        $view = $this->getDisplay(CONTROLLER.'/list');
        $this->getOptions(); //20130729 zoearth 選單資料
        $this->setupParams(array('s_active','s_name')); //20140425 zoearth 搜尋欄位
        
        //$LoanBank_DB = $this->getModel('LoanBank');
        $option = array();

        $view->assignRef('data', $this->viewData);
        //$pagination = new JPagination($this->viewData['rowsCount'],$this->viewData['limitstart'],$this->viewData['limit']);
        //$view->assignRef('pagesLinks', $pagination->getPagesLinks());
        $view->display();
    }
    
    //20150413 zoearth 搜尋檔案
    function searchFiles()
    {        
        $res = array();
        //$res['data'] = array();
        
        //取得設定
        $limit = JComponentHelper::getParams(COM_NAME)->get('limit',50);
        
        //執行查詢
        $actionName = JRequest::getVar('actionName');
        if (!in_array($actionName,array('search_no_img_src','search_no_used_img','search_params_img')))
        {
            echo json_encode(array('result'=>0,'message'=>'ERROR 0045 actionName'));exit();
        }
        
        //搜尋內文中的 images/ 資料
        if ($actionName == 'search_no_img_src')
        {
            $Check_DB = $this->getModel('Check');
            $items = $Check_DB->getAllImgSrc();
            
            foreach ($items as $imgSrc=>$imgDatas)
            {
                if (!(is_file(JPATH_ROOT.DS.$imgSrc)))
                {
                    $res[] = array(
                            '<input type="checkbox" name="imgItems[]" value="'.$imgSrc.'" class="itemCheckBox" >',
                            '-無圖片-',
                            $imgSrc,
                            '--',
                            '--',
                            implode(',',$imgDatas),
                    );                    
                }
            }
        }
        else if ($actionName == 'search_no_used_img')
        {
            
        }
        else if ($actionName == 'search_params_img')
        {
        
        }
        else
        {
            echo json_encode(array('result'=>0,'message'=>'ERROR 0083 actionName'));exit();
        }
        
        //存入session
        $session   = JFactory::getSession();
        $session->set('tmpRes',$res);
        if (!(is_array($res) && count($res) > 0 ))
        {
            echo json_encode(array('result'=>0,'message'=>'找不到資料!'));
        }
        else
        {
            echo json_encode(array('result'=>1,'message'=>''));
        }
        exit();
    }
    
    //20150414 zoearth 取得資料
    function ajax()
    {
        //存入session
        $session   = JFactory::getSession();
        $res = $session->set('tmpRes');
        echo json_encode(array('data'=>$res));
        exit();
    }
    
    //20140424 zoearth 取得編輯介面會需要用到的選單
    function getOptions()
    {
        
    }
}