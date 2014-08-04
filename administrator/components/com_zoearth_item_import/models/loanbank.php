<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

class LoanCustomerModelLoanBank extends ZoeModel
{
    public function hasName($name,$option=array())
    {
        $Query = $this->DB->getQuery(true);
    
        $Query->select('*')
            ->from('#__loan_bank')
            ->where('name LIKE '.$this->DB->quote($name));
        if (isset($option['modifyGuid']))
        {
            $Query->where('guid != '.(int)$option['modifyGuid']);
        }
        $this->DB->setQuery($Query);
        $row = $this->DB->loadObject();
        return $row ? TRUE : FALSE;
    }
    
    public function hasId($guid)
    {
        $Query = $this->DB->getQuery(true);
        
        $Query->select('*')
            ->from('#__loan_bank')
            ->where('guid = '.$this->DB->quote($guid))
            ->where('active = 1');
        $this->DB->setQuery($Query);
        $row = $this->DB->loadObject();
        return $row ? TRUE : FALSE;
    }
    
    public function get($guid=0)
    {
        $Query = $this->DB->getQuery(true);
        $Query->select(array('i.*','u.name AS userName'))
            ->from('#__loan_bank AS i')
            ->join('LEFT','#__users AS u ON i.iuser = u.id')
            ->where('i.guid = '.(int)$guid)
            ->where('i.active = 1');
        $this->DB->setQuery($Query);
        return (array)$this->DB->loadObject();
    }
    
    public function getAll($option=array())
    {
        $Query = $this->DB->getQuery(true);
        $Query->select(array('i.*','u.name AS userName'))
            ->from('#__loan_bank AS i')
            ->join('LEFT','#__users AS u ON i.iuser = u.id');
        
        if (isset($option['s_name']) && $option['s_name'] !== '')
        {
            $Query->where('i.name LIKE '.$this->DB->quote('%'.$option['s_name'].'%'));
        }
        
        if (isset($option['s_active']) && $option['s_active'] !== '')
        {
            $Query->where('i.active = '.(int)$option['s_active']);
        }
        else
        {
            $Query->where('i.active = 1');
        }
        
        if (isset($option['order']) && $option['order'] != '')
        {
            if (isset($option['sort']) && $option['sort'] == 'ASC')
            {$Query->order($option['order'].' ASC');}
            else
            {$Query->order($option['order'].' DESC');}
        }
        else
        {
            if (isset($option['sort']) && $option['sort'] == 'ASC')
            {$Query->order('i.guid ASC');}
            else
            {$Query->order('i.guid DESC');}
        }
        return $this->getAllObjectList($Query,(int)$option['limitstart'],(int)$option['limit']);
    }
    
    public function create($data)
    {
        $this->DB->transactionStart();
        try
        {
            $data = (object)$data;
            $this->DB->insertObject('#__loan_bank',$data);
            $lastId = $this->DB->insertid();
            $this->DB->transactionCommit();
        }
        catch (Exception $e)
        {
            $this->DB->transactionRollback();
            JError::raiseError( 500,$e);
        }
        return $lastId;
    }
    
    public function update($guid,$data)
    {
        $this->DB->transactionStart();
        try
        {
            $data = (object)$data;
            $data->guid = $guid;
            $this->DB->updateObject('#__loan_bank',$data,'guid');
            $this->DB->transactionCommit();
        }
        catch (Exception $e)
        {
            $this->DB->transactionRollback();
            JError::raiseError( 500,$e);
        }
        return $lastId;
    }
    
    public function delete($guid)
    {
        $this->DB->transactionStart();
        try
        {
            $Query = $this->DB->getQuery(true);
            
            $data = (object)array();
            $data->guid   = $guid;
            $data->active = 0;
            $this->DB->updateObject('#__loan_bank',$data,'guid');
            
            //$Query->delete("#__loan_bank")->where("guid = ".(int)$guid);
            //$this->DB->setQuery($Query);
            //$this->DB->query();
            
            $this->DB->transactionCommit();
        }
        catch (Exception $e)
        {
            $this->DB->transactionRollback();
            JError::raiseError( 500,$e);
        }
        return $lastId;
    }
    
    public function active($guid)
    {
        $this->DB->transactionStart();
        try
        {
            $Query = $this->DB->getQuery(true);
            $data = (object)array();
            $data->guid   = $guid;
            $data->active = 1;
            $this->DB->updateObject('#__loan_bank',$data,'guid');
            $this->DB->transactionCommit();
        }
        catch (Exception $e)
        {
            $this->DB->transactionRollback();
            JError::raiseError( 500,$e);
        }
        return $lastId;
    }
}