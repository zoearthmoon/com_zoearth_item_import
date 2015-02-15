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
        //設定檔
        $configFile    = JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_zoearth_item_import'.DS.'media'.DS.'config.json';
        $configFileVal = @file_get_contents($configFile);
        $config = @json_decode($configFileVal,TRUE);
        
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
            //確保結尾路徑不是"/"
            if (substr($imgUploadPath,-1,1) == '/')
            {
                $imgUploadPath = substr($imgUploadPath,0,strlen($imgUploadPath)-1);
            }
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
            
            $goHtml       = '';
            $goImgDir     = '';
            $goImgDirName = '';
            while (($file = readdir($dh)) !== false)
            {
                $fType = filetype($tmpDir.DS.$tmpZipDir.DS.$file);
                if ($fType == 'dir' && !in_array($file,array('.','..')))
                {
                    rename($tmpDir.DS.$tmpZipDir.DS.$file,$tmpDir.DS.$tmpZipDir.DS.$tmpZipDir);
                    $goImgDir     = $tmpDir.DS.$tmpZipDir.DS.$tmpZipDir;
                    $goImgDirName = $tmpZipDir;
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
			
			//20140820 zoearth 移除圖片高度與寬度
			$fileContent = preg_replace('/((width|height)([ =]{1,})([0-9px"\']{1,}))/','', $fileContent);
			$fileContent = preg_replace('/((width|height)([: ]{1,})([0-9px]{1,}))/','', $fileContent);
			//20140820 zoearth 移除連結
			$fileContent = preg_replace_callback('/<a[^>]*(href="[^"]*")[^>]*>(.*)<\/a>/siU',function ($match) {
                
                //如果是小屋連結
                if (preg_match("/home.gamer.com.tw/",$match[1]))
                {
                    return '<span class="label label-success">'.$match[2].'</span>';
                }
                else
                {
                    return '<a '.$match[1].' target="_blank" >'.$match[2].'</a>';
                }
            }
            , $fileContent);
            
            //20140821 zoearth 移除多餘div
			$fileContent = preg_replace('/<div\s[^>]*>(.*)<\/div>/siU','$1', $fileContent);
            $fileContent = preg_replace('/<div\s[^>]*>(.*)<\/div>/siU','$1', $fileContent);
            
            $preViewFileContent = $fileContent;//預覽用
            $preUploadFiles = array();
            $i = 0;
            if (is_dir($goImgDir) && $goImgDirName != '')
            {
                if ($dh = opendir($goImgDir))
                {
                    while (($file = readdir($dh)) !== false)
                    {
                        if (filetype($goImgDir.DS.$file) == 'file')
                        {
                            $i++;
                            $oldFileName = $goImgDirName.'/'.$file;
                            preg_match("/.([^\.]*)$/",$file,$matches);
                            $imgCount = substr('0'.$i,-2,2);
                            $newFileName = $imgUploadPath.'/'.$imgPrefix.'_'.$imgCount.'.'.@$matches[1];
                            $preUploadFiles[] = array(
                                'tmpView' => urldecode(JUri::root().'cache/com_zoearth_item_import/'.$tmpZipDir.'/'.$tmpZipDir.'/'.$file),
                                'tmpFile' => $goImgDir.DS.$file,
                                'newFile' => $newFileName,
                                );
                            $fileForSearch = preg_replace('/([\[\]\(\)]{1,1})/','\\\$1',$file);
                            $fileContent = preg_replace('/([^"]*)_files\/'.$fileForSearch.'/',$newFileName,$fileContent);
                            $preViewFileContent = preg_replace('/([^"]*)_files\/'.$fileForSearch.'/',JUri::root().'cache/com_zoearth_item_import/'.$tmpZipDir.'/'.$tmpZipDir.'/'.$file,$preViewFileContent);
                        }
                    }
                    closedir($dh);
                }
            }
            
            $this->viewData['preViewFileContent'] = $preViewFileContent;
            $this->viewData['fileContent']        = $fileContent;
            $this->viewData['preUploadFiles']     = $preUploadFiles;
            
            $view = $this->getDisplay(CONTROLLER.'/show');
            
            //20140819 zoearth 寫入設定暫存檔
            $data  = array('imgUploadPath'=>$imgUploadPath);
            @file_put_contents($configFile, json_encode($data));
        }
        else
        {
            //20140819 zoearth 刪除暫存檔案
            $this->deleteDirectory(JPATH_ROOT.DS.'cache'.DS.'com_zoearth_item_import');
        }
        
        //讀取設定檔
        if (isset($config['imgUploadPath']))
        {
            $this->viewData['imgUploadPath'] = $config['imgUploadPath'];
        }
        
        $view->assignRef('data', $this->viewData);
        $view->display();
    }

    //刪除資料夾
    private function deleteDirectory($dirPath)
    {
        if (is_dir($dirPath))
        {
            $objects = scandir($dirPath);
            foreach ($objects as $object)
            {
                if ($object != "." && $object !="..")
                {
                    if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir")
                    {
                        $this->deleteDirectory($dirPath . DIRECTORY_SEPARATOR . $object);
                    }
                    else
                    {
                        unlink($dirPath . DIRECTORY_SEPARATOR . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dirPath);
        }
    }
    
    //20140819 zoearth 存入圖片
    function saveImgs()
    {
        $tmpFile = JRequest::getVar('tmpFile');
        $newFile = JRequest::getVar('newFile');
        if ($this->isPost() && is_array($tmpFile) && is_array($newFile))
        {
            foreach ($tmpFile as $key=>$tmpFile)
            {
                if (JFile::exists($tmpFile) && !JFile::exists(JPATH_ROOT.DS.$newFile[$key]) )
                {
                    @JFile::copy($tmpFile, JPATH_ROOT.DS.$newFile[$key]);
                }
            }
            echo json_encode(array('result'=>1));exit();
        }
        echo json_encode(array('result'=>'SAVEIMGS ERROR POST'));exit();
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