<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');

define('CONTROLLER','AddWord');
define('CONTROLLER_NAME','文字增修');
define('CONTROLLER_BASE_URL',Juri::base().'index.php?option='.COM_NAME.'&view=LoanBank');

class ZoearthTwcnChangeControllerAddWord extends ZoeController
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

    function add()
    {
        $view = $this->getDisplay(CONTROLLER.'/modify');
        $this->getOptions(); //20130729 zoearth 選單資料
        
        $LoanBank_DB = $this->getModel('LoanBank');
        
        if ($this->isPost())
        {
            $this->addViewData($_POST);
            
            //20140425 zoearth 使用GUMP 驗證
            $gump = new GUMP();
            $isValidArray = array(
                    'name'     => 'trim|required',//銀行名稱
                    'code'     => 'trim|alpha_numeric',//銀行代號
                    'note'     => 'trim|',//內容
            );
            $gump->validation_rules($isValidArray);
            $gump->filter_rules($isValidArray);
            $input = $gump->run($_POST);
            if ($input === false)
            {
                JError::raiseError(500,$gump->get_readable_errors(true));
            }
            else if ($LoanBank_DB->hasName($input['name']))
            {
                JError::raiseError(500,'名稱重複!');
            }
            //20140519 zoearth 驗證程序
            else
            {
                $data = array(
                    'name'   => $input['name'],//銀行名稱
                    'code'   => $input['code'],//銀行代號
                    'note'   => $input['note'],//內容
                    'active' => 1,//有效
                    'iuser'  => $this->user()->id,
                    'idate'  => DTIME,
                    'uuser'  => $this->user()->id,
                    'udate'  => DTIME,        
                );
                $lastId = $LoanBank_DB->create($data);

                $view = $this->getDisplay('index/success');
                $this->viewData['title']   = CONTROLLER_NAME;
                $this->viewData['message'] = '新增成功。<br>';
                $this->viewData['message'] .= '  (三秒後自動返回)';
                $this->setHeader(CONTROLLER_BASE_URL);
            }
        }
        $this->setupParams(); //20140425 zoearth 搜尋欄位
        $view->assignRef('data', $this->viewData);
        $view->display();
    }
    
    function modify()
    {
        $view = $this->getDisplay(CONTROLLER.'/modify');
        $this->getOptions(); //20130729 zoearth 選單資料
        
        $LoanBank_DB = $this->getModel('LoanBank');
        
        $guid = JRequest::getVar('guid');
        
        if (!$this->isPost()) //顯示資料
        {
            if (!$LoanBank_DB->hasId($guid))
            {
                JError::raiseError(500,'錯誤，此資料已經不存在，請依據正常程序執行。');return FALSE;
            }
            $this->addViewData($LoanBank_DB->get($guid));
        }
        else //進行更新
        {
            $this->addViewData($_POST);
            
            //20140425 zoearth 使用GUMP 驗證
            $gump = new GUMP();
            $isValidArray = array(
                    'name'     => 'trim|required',//銀行名稱
                    'code'     => 'trim|alpha_numeric',//銀行代號
                    'note'     => 'trim|',//內容
            );
        
            $gump->validation_rules($isValidArray);
            $gump->filter_rules($isValidArray);
            $input = $gump->run($_POST);
            if ($input === false)
            {
                JError::raiseError( 500,$gump->get_readable_errors(true));
            }
            //20140519 zoearth 驗證程序
            else if ($LoanBank_DB->hasName($input['name'],array('modifyGuid'=>$guid)))
            {
                JError::raiseError(500,'名稱重複!');
            }
            else
            {
                $data = array(
                    'name'   => $input['name'],//銀行名稱
                    'code'   => $input['code'],//銀行代號
                    'note'   => $input['note'],//內容
                    'iuser'  => $this->user()->id,
                    'idate'  => DTIME,
                    'uuser'  => $this->user()->id,
                    'udate'  => DTIME,        
                );
                $LoanBank_DB->update($guid,$data);
                
                $view = $this->getDisplay('index/success');
                $this->viewData['title']   = CONTROLLER_NAME;
                $this->viewData['message'] = '修改成功。<br>';
                $this->viewData['message'] .= '  (三秒後自動返回)';
                $this->setHeader(CONTROLLER_BASE_URL);
            }
        }
        $this->setupParams(); //20140425 zoearth 搜尋欄位
        $view->assignRef('data', $this->viewData);
        $view->display();
    }

    function delete()
    {
        $LoanBank_DB = $this->getModel('LoanBank');
        $guid = JRequest::getVar('guid');
        if (!$LoanBank_DB->hasId($guid))
        {
            JError::raiseError(500,'錯誤，此資料已經不存在，請依據正常程序執行。');
        }
        
        $LoanBank_DB->delete($guid);
        $view = $this->getDisplay('index/success');
        $this->viewData['title']   = CONTROLLER_NAME;
        $this->viewData['message'] = '刪除成功。<br>';
        $this->viewData['message'] .= '  (三秒後自動返回)';
        $this->setHeader(CONTROLLER_BASE_URL);
        
        $this->setupParams(); //20140425 zoearth 搜尋欄位
        $view->assignRef('data', $this->viewData);
        $view->display();
    }
    
    function active()
    {
        $LoanBank_DB = $this->getModel('LoanBank');
        $guid = JRequest::getVar('guid');
        $LoanBank_DB->active($guid);
        $view = $this->getDisplay('index/success');
        $this->viewData['title']   = CONTROLLER_NAME;
        $this->viewData['message'] = '發佈成功。<br>';
        $this->viewData['message'] .= '  (三秒後自動返回)';
        $this->setHeader(CONTROLLER_BASE_URL);
    
        $this->setupParams(); //20140425 zoearth 搜尋欄位
        $view->assignRef('data', $this->viewData);
        $view->display();
    }
    
    //20140424 zoearth 取得編輯介面會需要用到的選單
    function getOptions()
    {
        
    }
}