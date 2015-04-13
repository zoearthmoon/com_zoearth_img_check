<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');

define('CONTROLLER','Check');
define('CONTROLLER_NAME','文字增修');
define('CONTROLLER_BASE_URL',Juri::base().'index.php?option='.COM_NAME.'&view=check');

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
    
    //20140424 zoearth 取得編輯介面會需要用到的選單
    function getOptions()
    {
        
    }
}