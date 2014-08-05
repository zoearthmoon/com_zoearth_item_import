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
        $con['Import']['title'] = "匯入管理";
        $con['Import']['action']['index']  = "匯入管理";
        $con['Import']['action']['add'] = "匯入管理";
        $con['Import']['action']['modify'] = "匯入管理";
        $con['Import']['action']['delete'] = "匯入管理";

        return $con;
    }
    
    public static function showMenuArray()
    {
        $menu = array();
        $i = 0;
        
        
        $menu[$i]['name'] = "匯入功能";
        $menu[$i]['icon'] = "icon-map-marker";
        $menu[$i]['controllers'] = array(
                'Import',
                );
        
        $menu[$i]['controllerNames'] = array(
                '匯入功能',
                );
        
        $menu[$i]['modelNames'] = array(
                'com_zoearth_item_import',
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