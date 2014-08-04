<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');

class LoanCustomerControllerIndex extends JControllerLegacy
{
    function display($cachable = false, $urlparams = false)
    {
        $this->setRedirect('index.php?option=com_loan_customer&view=LoanRecord','', 'notice');
    }
}