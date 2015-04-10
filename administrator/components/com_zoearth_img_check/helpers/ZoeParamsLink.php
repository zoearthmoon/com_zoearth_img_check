<?php
/**
 * @author      Zoearth
 */
 
class ZoeParamsLink
{
    static public function _($key='',$name='')
    {
        static $mainframe;
        static $comName;
        static $viewName;
        
        if (!$mainframe)
        {
            $mainframe = JFactory::getApplication();
        }
        if (!$comName)
        {
            $comName = JRequest::getVar('option');
        }
        if (!$viewName)
        {
            $viewName = JRequest::getVar('view');
        }
        $order = $mainframe->getUserStateFromRequest($comName.($viewName ? '.'.$viewName:'').".order",'order');
        $sort  = $mainframe->getUserStateFromRequest($comName.($viewName ? '.'.$viewName:'').".sort",'sort');

        $goSort = 'ASC';
        $class = "icon-arrow-up";
        if ($order == $key && $sort == 'ASC')
        {
            $goSort = 'DESC';
            $class = "icon-arrow-down";
        }
        $onclick = ZoeSubmit::_(array('task'=>'index','order'=>$key,'sort'=>$goSort));
        return '<a href="javascript:void(0);" onclick="'.$onclick.'" ><i class="'.$class.'" ></i>'.$name.'</a>';
    }
}