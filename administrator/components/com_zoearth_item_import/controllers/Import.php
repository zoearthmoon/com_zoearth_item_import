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
        $this->setupParams(); //20140425 zoearth 搜尋欄位
        $option = array();
        
        //20140815 zoearth 驗證與上傳
        if ($this->isPost())
        {
            $imgUploadPath = JRequest::getVar('imgUploadPath');
            $imgPrefix     = JRequest::getVar('imgPrefix');
            $evernoteFile  = JRequest::getVar('evernoteFile','','files');
            
            if (!($evernoteFile['size'] > 0 && $imgPrefix && $evernoteFile ))
            {
                JError::raiseError(500,'Input ERROR 001');return FALSE;
            }
            if (!(substr($imgUploadPath,0,6) == 'images' && is_dir(JPATH_ROOT.DS.$imgUploadPath)))
            {
                JError::raiseError(500,'Input ERROR 002');return FALSE;
            }
            if (!($imgPrefix != '' && preg_match('/(^[a-zA-Z0-9\_\-]*)$/',$imgPrefix)))
            {
                JError::raiseError(500,'Input ERROR 003');return FALSE;
            }
            if (JFile::exists(JPATH_ROOT.DS.$imgUploadPath.DS.$imgPrefix.'_01.jpg'))
            {
                JError::raiseError(500,'Input ERROR 004');return FALSE;
            }
            
            //20140815 zoearth 開始上傳到cache
            $tmpZipDir = time();
            $tmpZipName = $tmpZipDir.'.zip';
            $tmpDir     = JPATH_ROOT.DS.'cache'.DS.'com_zoearth_item_import';
            @mkdir(JPATH_ROOT.DS.'cache'.DS.'com_zoearth_item_import');
            @copy($evernoteFile['tmp_name'],$tmpDir.DS.$tmpZipName);
            $goZipFile = $tmpDir.DS.$tmpZipName;

            $zip = new ZipArchive;
            $res = $zip->open($goZipFile);
            if (!($res === TRUE))
            {
                JError::raiseError(500,'Input ERROR 005 : Zip File Error ');return FALSE;
            }
            
            @mkdir($tmpDir.DS.$tmpZipDir);
            $zip->extractTo($tmpDir.DS.$tmpZipDir);
            $zip->close();
            //讀取資料夾
            if (!(is_dir($tmpDir.DS.$tmpZipDir)))
            {
                JError::raiseError(500,'Input ERROR 006');return FALSE;
            }
            
            if (!($dh = opendir($tmpDir.DS.$tmpZipDir)))
            {
                JError::raiseError(500,'Input ERROR 007');return FALSE;
            }
            
            $goHtml = '';
            $goImgDir = '';
            while (($file = readdir($dh)) !== false)
            {
                $fType = filetype($tmpDir.DS.$tmpZipDir.DS.$file);
                if ($fType == 'dir' && !in_array($file,array('.','..')))
                {
                    $goImgDir = $tmpDir.DS.$tmpZipDir.DS.$file;
                }
                if ($fType == 'file' && substr($file,-4,4) == 'html' )
                {
                    $goHtml = $tmpDir.DS.$tmpZipDir.DS.$file;
                }
            }
            closedir($dh);
            
            if (!($goHtml != ''))
            {
                JError::raiseError(500,'Input ERROR 008');return FALSE;
            }
            
            //20140815 zoearth 開始分析文字內容
            $fileContent = file_get_contents($goHtml);
            $fileContent = str_replace("\r",'',$fileContent);
            $fileContent = str_replace("\n",'',$fileContent);
            
            //echo $fileContent;
            //取出boby內容
            preg_match("/<body([^>]*)>(.*)<\/body>/",$fileContent,$matches);
            //去掉<a name="9303"/>
            $fileContent = preg_replace('/<a([^>]*)name="([0-9]*)"([^>]*)\/>/','', $matches[2]);
            
            //if ($goImgDir)
            
            
            
            
            
            
            
            
            exit();
        }
        
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
            if (substr($imgUploadPath,0,6) == 'images' && is_dir(JPATH_ROOT.DS.$imgUploadPath))
            {
                if ($imgPrefix != '' && preg_match('/(^[a-zA-Z0-9\_\-]*)$/',$imgPrefix))
                {
                    if (!JFile::exists(JPATH_ROOT.DS.$imgUploadPath.DS.$imgPrefix.'_01.jpg'))
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
                    echo json_encode(array('result'=>JText::_('COM_ZOEARTH_ITEM_IMPORT_IMG_PREFIX_ERROR_PREG')." ".$imgPrefix));exit();
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