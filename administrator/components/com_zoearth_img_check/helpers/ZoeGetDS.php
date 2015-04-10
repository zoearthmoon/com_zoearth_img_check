<?php
/**
 * @author      Zoearth
 */
 
class ZoeGetDs
{
    static public function _($key='',$val='none')
    {
        $ds = ActionList::showDSArray();
        if (isset($ds[$key]) && $val == 'none')
        {
            return $ds[$key];
        }
        elseif (isset($ds[$key][$val]))
        {
            return '<span style="color:#'.$ds[$key][$val]['color'].'" >'.$ds[$key][$val]['name'].'</span>';
        }
        return array();
    }
}