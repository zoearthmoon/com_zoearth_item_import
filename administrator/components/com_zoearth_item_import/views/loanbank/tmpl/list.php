<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');
?>
<style type="text/css">
.subhead-collapse
{
    display:none;
}
</style>
<div id="j-sidebar-container" class="span2">
    <?php echo ZoeSayPath::outputMenu(); ?>
</div>
<div id="j-main-container" class="span10">
<div class="row-fluid z0-list-HrOrgBranch">
    <div class="row-fluid ZoePath">
    	<?php echo ZoeSayPath::showPath(); ?>
    </div>
    <form action="<?php echo Juri::base().'index.php'; ?>" method="get" name="adminForm" id="adminForm" class="form-inline form-search" >
    <div class="dataTables_wrapper">
        <div class="row-fluid">
            <div class="dataTables_filter span12">
                <div class="form-actions span12 search">
                <div class="text-left">
                    <div class="input-prepend">
                        <span class="add-on">狀態</span>
                        <select name="s_active" id="s_active">
                            <?php foreach(ZoeGetDS::_('active') AS $key=>$v) :?>
                            <option value="<?php echo $key;?>" <?php echo ($this->data['s_active'] === (string)$key ? 'selected':'');?> ><?php echo $v['name']?></option>
                            <?php endforeach;?>
                        </select>
                    </div>
                    <div class="input-prepend">
                        <span class="add-on" >名稱</span>
                        <input id="s_name" name="s_name" size="16" type="text" value="<?php echo $this->data['s_name']; ?>">
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-info" id="search" href="javascript:void(0);" onclick="<?php echo ZoeSubmit::_()?>" ><i class="icon-search"></i>搜尋</button>
                        <button class="btn btn-success" id="search" href="javascript:void(0);" onclick="<?php echo ZoeSubmit::_(array('params'=>'clean'))?>" ><i class="icon-remove"></i></button>
                    </div>
                </div>
    		    <div class="text-right">
    		        <button class="btn btn-info" id="add" href="javascript:void(0);" onclick="<?php echo ZoeSubmit::_(array('task'=>'add'))?>" ><i class="icon-plus"></i>新增</button>
    		    </div>
                
    		    </div>
    		</div>
        </div>
        <table class="table table-striped table-bordered table-hover dataTable" id="table_report">
        <thead>
            <tr class="table-header">
                <th><?php echo ZoeParamsLink::_('name','銀行名稱'); ?></th>
                <th><?php echo ZoeParamsLink::_('code','代號'); ?></th>
                <th><?php echo ZoeParamsLink::_('active','狀態'); ?></th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($this->data['rows'] AS $k => $v) :?>
                <tr data-row-id="2" class="z0-row">
                  <td><?php echo $v['name']?></td>
                  <td><?php echo $v['code']?></td>
                  <td><?php echo ZoeGetDS::_('active',$v['active'])?></td>
                  <td>
                    <div class="hidden-phone visible-desktop btn-group">
                        <a class="btn btn-info z0-edit" href="javascript:void(0);" onclick="<?php echo ZoeSubmit::_(array('task'=>'modify','guid'=>$v['guid']))?>" >
                            <i class="icon-edit"></i>編輯
                        </a>
                        <?php if ($v['active'] == "1"):?>
                        <a class="btn btn-danger z0-edit" href="javascript:void(0);" onclick="<?php echo ZoeSubmit::_(array('task'=>'delete','guid'=>$v['guid']))?>" >
                            <i class="icon-remove"></i>
                        </a>
                        <?php else :?>
                        <a class="btn btn-success z0-edit" href="javascript:void(0);" onclick="<?php echo ZoeSubmit::_(array('task'=>'active','guid'=>$v['guid']))?>" >
                            <i class="icon-ok"></i>
                        </a>
                        <?php endif;?>
                    </div>
                  </td>
                </tr>                
            <?php endforeach;?>
        </tbody>
    	</table>
        <div class="row-fluid">
        	<div class="span4">
        		<div class="dataTables_info">
        		    總共 <?php echo $this->data['rowsCount']?>筆資料</div>
        	</div>
        	<div class="span8">
        		<div class="dataTables_paginate paging_bootstrap pagination">
                    <?php echo $this->pagesLinks;?>
        		</div>
        	</div>
        </div>
    </div>
    <input type="hidden" value="<?php echo $this->data['guid']?>" name="guid">
    <input type="hidden" value="<?php echo $this->data['option']?>" name="option">
    <input type="hidden" value="<?php echo $this->data['view']?>" name="view">
    <input type="hidden" value="<?php echo $this->data['task']?>" name="task">
    
    <input type="hidden" value="<?php echo $this->data['search']?>" name="params">
    <input type="hidden" value="<?php echo $this->data['limit']?>" name="limit">
    <input type="hidden" value="<?php echo $this->data['limitstart']?>" name="limitstart">
    <input type="hidden" value="<?php echo $this->data['order']?>" name="order">
    <input type="hidden" value="<?php echo $this->data['sort']?>" name="sort">
    </form>
</div>
</div>