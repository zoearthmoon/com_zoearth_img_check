<?php
/*
  @author zoearth
  :這邊的功能預設讀取 controllers的 index.php
*/
defined('_JEXEC') or die('Restricted access');

JToolBarHelper::title('文字工具元件');

JHTML::_('behavior.tooltip');
JHtml::_('behavior.framework');

//20140429 zoearth 定義一些常用到的變數
define("COM_NAME",'com_zoearth_img_check');
define("MODULE_NAME",'ZoearthImgCheck');
define('CONTROLLER_BASE',Juri::base().'index.php?option='.COM_NAME);
//20140430 zoearth 這邊直接定義time zone 直接寫入datetime
//date_default_timezone_set(JFactory::getConfig()->get('offset'));
date_default_timezone_set('Asia/Taipei');
define("DTIME",date('Y-m-d H:i:s'));

require_once(JPATH_COMPONENT.'/helpers/ActionList.php');
require_once(JPATH_COMPONENT.'/helpers/ZoeGetDS.php');
require_once(JPATH_COMPONENT.'/helpers/ZoeHtml.php');
require_once(JPATH_COMPONENT.'/helpers/ZoeParamsLink.php');
require_once(JPATH_COMPONENT.'/helpers/ZoeSayPath.php');
require_once(JPATH_COMPONENT.'/helpers/ZoeSetupJs.php');
require_once(JPATH_COMPONENT.'/helpers/ZoeSubmit.php');
require_once(JPATH_COMPONENT.'/helpers/ZoeSayFiles.php');
require_once(JPATH_COMPONENT.'/helpers/ZoeSayWidget.php');

require_once(JPATH_COMPONENT.'/libraries/ZoeController.php');
require_once(JPATH_COMPONENT.'/libraries/ZoeModel.php');
require_once(JPATH_COMPONENT.'/libraries/ZoeFiles.php');
require_once(JPATH_COMPONENT.'/libraries/gump.class.php');

if (!JFactory::getUser()->authorise('core.manage', COM_NAME))
{
    return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
if ($controller = JFactory::getApplication()->input->getWord('controller'))
{
    $go_controller = $controller;
}
else if($view = JFactory::getApplication()->input->getWord('view'))//取得view
{
    $go_controller = $view;
}
else
{
    $go_controller = 'index';
}

$path = JPATH_COMPONENT.'/controllers/'.$go_controller.'.php';

if(file_exists($path))
{
    require_once $path;
}

//20140428 zoearth 呼叫使用到的類別
JHtml::_('bootstrap.tooltip');

$document = JFactory::getDocument();

//$document->addStyleSheet('components/'.JRequest::getVar('option').'/media/css/bootstrap.css');
$document->addStyleSheet('components/'.JRequest::getVar('option').'/media/css/custom.css');
$document->addScript('components/'.JRequest::getVar('option').'/media/js/custom.js', 'text/javascript');
if (file_exists(JPATH_COMPONENT_ADMINISTRATOR.'components/'.JRequest::getVar('option').'/media/js/'.COM_NAME.'.js'))
{
    $document->addScript('components/'.JRequest::getVar('option').'/media/js/'.COM_NAME.'.js', 'text/javascript');
}

//20131213 zoearth 取消/media/system/js/html5fallback.js
$headData = $document->getHeadData();
$scripts = $headData['scripts'];

//20140428 zoearth 取得基本DIR
$baseDir = str_replace('http://'.$_SERVER['HTTP_HOST'],'',Juri::root());

unset($scripts[$baseDir.'media/system/js/html5fallback.js']);
$headData['scripts'] = $scripts;
$document->setHeadData($headData);


$classname = MODULE_NAME.'Controller'.$go_controller;
if (!class_exists($classname))
{
    JError::raiseError(404,'找不到 '.MODULE_NAME.'Controller'.$go_controller.' 類別');
}
else
{
    $controller = new $classname();
    $controller->execute(JFactory::getApplication()->input->get('task'));
    $controller->redirect();    
}