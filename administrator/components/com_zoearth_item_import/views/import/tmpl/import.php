<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');

ZoeSetupJs::lightBox();
ZoeSetupJs::validate();
?>
<script language="Javascript">
//檢查圖片資料夾路徑
jQuery.validator.addMethod("ckeckDirExist", function(value) {

	//jQuery.validator.messages.ckeckDirExist = 'QOO';
	var data = {
			'option':'com_zoearth_item_import',
			'view':'Import',
			'task':'ckeckDirExist',
			'imgUploadPath':imgUploadPath,
			};
    var result = 0;
    try {
        $.ajax({
            type: "POST",
            cache:false,
            async:false,
            url: url,
            data: data,
            dataType:"json",
            success: function(data) {
            	result = data.result;
            }
        });
	} catch(err) {
		alert('ckeckDirExist POST ERROR! ');
        return false;
	};

	if (result == "1")
	{
		return true;
	}
	else
	{
		return false;
	}
}, "<?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_IMG_DIR_ERROR')?>");

jQuery(document).ready(function() {
	jQuery("#mainForm").validate();
});
</script>
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
    <form id="mainForm" action="<?php echo Juri::base().'index.php'; ?>" method="get" name="adminForm" id="adminForm" class="form-horizontal"  enctype="multipart/form-data" >
    <div class="dataTables_wrapper">
    
    <fieldset>
        <legend><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT')?></legend>

            <div class="control-group">
                <label class="control-label" ><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_IMG_DIR')?></label>
                <div class="controls">
                    <div class="alert">
                        <strong><?php echo JText::_('JFIELD_NOTE_DESC')?>!</strong><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_STEP01_DESC')?>
                    </div>
                    <input class="required ckeckDirExist" type="text" id="imgUploadPath" name="imgUploadPath" placeholder="<?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_IMG_DIR')?>">
                </div>
            </div>
        
            <div class="control-group">
                <label class="control-label" ><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_IMG_PREFIX')?></label>
                <div class="controls">
                    <div class="alert">
                        <strong><?php echo JText::_('JFIELD_NOTE_DESC')?>!</strong><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_STEP02_DESC')?>
                    </div>
                    <input type="text" id="imgPrefix" name="imgPrefix" placeholder="<?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_IMG_PREFIX')?>">
                </div>
            </div>
        
            <div class="control-group">
                <label class="control-label" ><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_FILE_DESC')?></label>
                <div class="controls">
                    <div class="alert">
                        <strong><?php echo JText::_('JFIELD_NOTE_DESC')?>!</strong><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_STEP03_DESC')?>
                    </div>
                    <div class="rows">
                        <a rel="lightbox" href="<?php echo JUri::base().'components'.DS.'com_zoearth_item_import'.DS.'media'.DS.'img'.DS.'com_zoearth_item_import_step01.jpg';?>" alt="<?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_STEP01_DESC')?>">
                            <img style="width:50px;height:50px" src="<?php echo JUri::base().'components'.DS.'com_zoearth_item_import'.DS.'media'.DS.'img'.DS.'com_zoearth_item_import_step01.jpg';?>" class="img-circle">
                            <span><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_STEP01_TITLE')?></span>
                        </a>
                        <a rel="lightbox" href="<?php echo JUri::base().'components'.DS.'com_zoearth_item_import'.DS.'media'.DS.'img'.DS.'com_zoearth_item_import_step02.jpg';?>" alt="<?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_STEP02_DESC')?>">
                            <img style="width:50px;height:50px" src="<?php echo JUri::base().'components'.DS.'com_zoearth_item_import'.DS.'media'.DS.'img'.DS.'com_zoearth_item_import_step02.jpg';?>" class="img-circle">
                            <span><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_STEP02_TITLE')?></span>
                        </a>
                        <a rel="lightbox" href="<?php echo JUri::base().'components'.DS.'com_zoearth_item_import'.DS.'media'.DS.'img'.DS.'com_zoearth_item_import_step03.jpg';?>" alt="<?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_STEP03_DESC')?>">
                            <img style="width:50px;height:50px" src="<?php echo JUri::base().'components'.DS.'com_zoearth_item_import'.DS.'media'.DS.'img'.DS.'com_zoearth_item_import_step03.jpg';?>" class="img-circle">
                            <span><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_STEP03_TITLE')?></span>
                        </a>
                    </div>
                    <div class="rows">
                        <input type="file" id="evernoteFile" name="evernoteFile" >
                    </div>
                </div>
            </div>
        
        <button type="submit" class="btn"><?php echo JText::_('JSUBMIT')?></button>
    </fieldset>
    
        
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