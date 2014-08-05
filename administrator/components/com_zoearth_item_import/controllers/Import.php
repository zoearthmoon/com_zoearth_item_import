<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');

define('CONTROLLER','Import');
define('CONTROLLER_NAME','匯入資料');
define('CONTROLLER_BASE_URL',Juri::base().'index.php?option='.COM_NAME.'&view=Import');

class ZoearthItemImportControllerImport extends ZoeController
{
    function display($cachable = false, $urlparams = false)
    {
        $this->index();
    }
    
    function index()
    {
        //20140425 zoearth Joomla 必須先設定模板
        //20140424 zoearth 設定模板
        $view = $this->getDisplay(CONTROLLER.'/import');
        $this->getOptions(); //20130729 zoearth 選單資料
        
        $option = array();

        $view->assignRef('data', $this->viewData);
        $view->display();
    }

    //20140424 zoearth 取得編輯介面會需要用到的選單
    function getOptions()
    {
        
    }
}