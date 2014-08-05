<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');

class ZoearthItemImportControllerIndex extends JControllerLegacy
{
    function display($cachable = false, $urlparams = false)
    {
        $this->setRedirect('index.php?option='.COM_NAME.'&view=Import','', 'notice');
    }
}