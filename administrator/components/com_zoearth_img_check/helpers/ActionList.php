<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');

class ActionList
{
    public static function showArray()
    {
        $con = array();       
        //20140425 zoearth
        
        return $con;
    }
    
    public static function showMenuArray()
    {
        $menu = array();
        $i = 0;
        
        
        $menu[$i]['name'] = "圖片整理元件";
        $menu[$i]['icon'] = "icon-map-marker";
        $menu[$i]['controllers'] = array(
                'check',
                );
        
        $menu[$i]['controllerNames'] = array(
                '圖片整理元件',
                );
        
        $menu[$i]['modelNames'] = array(
                'com_zoearth_img_check',
                );
        
        $menu[$i]['auth'] = array(
                "core.admin",
                );
        
        $i++;
        
        return $menu;
    }
    
    public static function showDSArray()
    {
        $ds = array();
        
        //有效
        $ds['active']['1']['name'] = '有效';
        $ds['active']['1']['color'] = '0000FF';
        $ds['active']['0']['name'] = '無效';
        $ds['active']['0']['color'] = 'FF0000';
        
        //有無
        $ds['have']['1']['name'] = '有';
        $ds['have']['1']['color'] = '0000FF';
        $ds['have']['2']['name'] = '無';
        $ds['have']['2']['color'] = 'FF0000';
        
        return $ds;
    }
}