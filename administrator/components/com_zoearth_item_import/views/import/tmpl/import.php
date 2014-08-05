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
        TESTing
        
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