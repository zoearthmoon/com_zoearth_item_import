<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');

//共用繼承
class ZoeController extends JControllerLegacy
{
    public $mainframe = NULL;
    public $comName   = NULL;
    public $viewName  = NULL;
    public $viewData  = array(); //放置給view的值
    
    //20140519 zoearth 這邊將輸出的 adminForm 與 Joomla.submitform(); 取代
    function showReplaceForm(&$view,$target='')
    {
        ob_start();
        $view->display();
        $html = ob_get_contents();
        $html = str_replace('.adminForm.','.'.CONTROLLER.'Form.',$html);
        $html = str_replace('Joomla.submitform()','submit'.CONTROLLER.'Form()',$html);
        ob_end_clean();
        echo $html;
        exit();
    }
    
    //20140429 zoearth 把值補進去 ViewData
    function addViewData($data=array())
    {
        if (is_object($data) && count($data) > 0 )
        {
            $data = (array)$data;
        }
        if (is_array($data) && count($data) > 0 )
        {
            $this->viewData = array_merge($this->viewData,$data);
        }
    }
    
    function getParams($name='',$default='')
    {
        if (!$this->mainframe)
        {
            $this->mainframe = JFactory::getApplication();
        }

        if (!$this->comName)
        {
            $this->comName = JRequest::getVar('option');
        }
        if (!$this->viewName)
        {
            $this->viewName = JRequest::getVar('view');
        }
        
        //20140429 zoearth 如果有 search=clean
        if (JRequest::getVar('params') == 'clean')
        {
            JRequest::setVar($name,$default);
            $this->mainframe->setUserState($this->comName.($this->viewName ? '.'.$this->viewName:'').".".$name,$default);
        }
        //20140429 zoearth 如果有 JRequest
        elseif (JRequest::getVar($name))
        {
            $this->mainframe->setUserState($this->comName.($this->viewName ? '.'.$this->viewName:'').".".$name,JRequest::getVar($name));
        }
        return $this->mainframe->getUserStateFromRequest($this->comName.($this->viewName ? '.'.$this->viewName:'').".".$name,$name,$default);
    }
    
    
    function getDisplay($path='')
    {
        $pathArray = explode('/',$path);
        if (!(isset($pathArray[0]) && $pathArray[0] != '' && isset($pathArray[1]) && $pathArray[1] != ''))
        {
            JError::raiseError(500,"版型錯誤");
        }
        $view = $this->getView($pathArray[0],'html');
        $view->setLayout($pathArray[1]);
        return $view;
    }
    
    function setupParams($addParams = array())
    {
        $this->viewData['limit']  = $this->getParams('limit',10);
        $this->viewData['limitstart']  = $this->getParams('limitstart',0);
        $this->viewData['order']  = $this->getParams('order');
        $this->viewData['sort']   = $this->getParams('sort');
        $this->viewData['option'] = JRequest::getVar('option');
        $this->viewData['view']   = JRequest::getVar('view');
        $this->viewData['task']   = JRequest::getVar('task');
        
        foreach ($addParams as $key)
        {
            $this->viewData[$key]  = $this->getParams($key);
        }
    }
    
    //20140429 zoearth 回傳USER
    function user()
    {
        static $user;
        if (!$user)
        {
            $user = JFactory::getUser();
        }
        return $user;
    }
    
    //20140429 zoearth 回傳doc
    function setHeader($gotoUrl='')
    {
        if ($gotoUrl)
        {
            JResponse::setHeader('refresh','3;URL='.$gotoUrl);
        }
    }
    
    function isPost()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
}