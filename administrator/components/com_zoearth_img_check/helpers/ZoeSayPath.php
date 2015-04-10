<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_COMPONENT.'/helpers/ActionList.php');

class ZoeSayPath
{
    public static function showPath()
    {
        $controller = JRequest::getVar('view');
        $action = JRequest::getVar('task');
        
        if ($controller == '')
        {
            return '';
        }
        if ($action == '')
        {
            $action = 'index';
        }
        $con = ActionList::showArray();
        $html = '';
        $html .= '<h1>';
        $html .= @$con[$controller]['title'];
        $html .= '<small>'.@$con[$controller]['action'][$action].'</small>';
        $html .= '</h1>';
        
        return $html;
    }
    
    public function outputMenu()
    {
        $nowControllerName = JRequest::getVar('view');;
        $menu = ActionList::showMenuArray();
        ?>
        <ul class="nav nav-list">
        <?php foreach ($menu as $cons): ?>
            <?php
            $haveCons = 0;
            foreach ($cons['controllers'] as $key=>$consName)
            {
                //20140502 zoearth 先取消權限驗證
                if (TRUE || JFactory::getUser()->authorise($cons['auth'][$key],$cons['modelNames'][$key]))
                {$haveCons = 1;break;}
            }
            if ($haveCons == 0 ){continue;}
            ?>
            <?php foreach ($cons['controllers'] as $key => $controllerName): ?>
                <?php if (TRUE || JFactory::getUser()->authorise($cons['auth'][$key],$cons['modelNames'][$key])):?>
                <li <?php echo ($nowControllerName == $controllerName) ? 'class="active"' : ''?> >
                    <?php if ($controllerName == 'Config'):?>
                        <a href="<?php echo Juri::base().'index.php?option=com_config&view=component&component='.COM_NAME;?>">
                    <?php else :?>
                        <a href="<?php echo Juri::base().'index.php?option='.$cons['modelNames'][$key].'&view='.$controllerName;?>">
                    <?php endif;?>
                    <i class="icon-double-angle-right"></i><?php echo $cons['controllerNames'][$key] ?></a>
                </li>
                <?php endif;?>
            <?php endforeach;?>
        <?php endforeach; ?>
        <?php
    }
}