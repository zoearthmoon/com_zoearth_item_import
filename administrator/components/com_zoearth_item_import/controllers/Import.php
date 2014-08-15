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

    //驗證路徑
    function ckeckDirExist()
    {
        $imgUploadPath = JRequest::getVar('imgUploadPath');
        if ($this->isPost() && $imgUploadPath)
        {
            if (substr($imgUploadPath,0,6) == 'images' && is_dir(JPATH_ROOT.DS.$imgUploadPath))
            {
                echo json_encode(array('result'=>1));exit();
            }
            echo json_encode(array('result'=>JText::_('COM_ZOEARTH_ITEM_IMPORT_IMG_DIR_NOT_EXIST').' '.JPATH_ROOT.DS.$imgUploadPath));exit();
        }
        echo json_encode(array('result'=>JText::_('COM_ZOEARTH_ITEM_IMPORT_IMG_DIR_ERROR')));exit();
    }
    
    //驗證路徑中的
    function ckeckPreFixExist()
    {
        $imgUploadPath = JRequest::getVar('imgUploadPath');
        $imgPrefix     = JRequest::getVar('imgPrefix');
        if ($this->isPost() && $imgUploadPath)
        {
            //資料夾錯誤
            if (substr($imgUploadPath,0,6) == 'images' && is_dir(JPATH_ROOT.$imgUploadPath))
            {
                if ($imgPrefix != '' && !preg_match("/^[a-zA-Z0-9\_\-]+$/",$imgPrefix))
                {
                    if (!JFile::exists(JPATH_ROOT.$imgUploadPath.DS.$imgPrefix.'_01.jpg'))
                    {
                        //通過驗證
                        echo json_encode(array('result'=>1));exit();
                    }
                    else
                    {
                        echo json_encode(array('result'=>JText::_('COM_ZOEARTH_ITEM_IMPORT_IMG_PREFIX_ERROR_EXIST')));exit();
                    }
                }
                else
                {
                    echo json_encode(array('result'=>JText::_('COM_ZOEARTH_ITEM_IMPORT_IMG_PREFIX_ERROR_PREG')));exit();
                }
            }
            else
            {
                echo json_encode(array('result'=>JText::_('COM_ZOEARTH_ITEM_IMPORT_IMG_DIR_ERROR')));exit();
            }
        }
        echo json_encode(array('result'=>JText::_('COM_ZOEARTH_ITEM_IMPORT_IMG_PREFIX_ERROR')));exit();
    }
    
    //20140424 zoearth 取得編輯介面會需要用到的選單
    function getOptions()
    {
        
    }
}