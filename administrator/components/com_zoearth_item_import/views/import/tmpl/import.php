<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');

ZoeSetupJs::lightBox();
ZoeSetupJs::validate();
?>
<script language="Javascript">

jQuery.validator.addMethod("ckeckPreFixExist", function(value) {
    var imgUploadPath = jQuery("#imgUploadPath").val();
    var imgPrefix = jQuery("#imgPrefix").val();
	var data = {
			'option':'com_zoearth_item_import',
			'view':'Import',
			'task':'ckeckPreFixExist',
			'imgPrefix':imgPrefix,
            'imgUploadPath':imgUploadPath,
			};
    var result = 0;
    try {
        jQuery.ajax({
            type: "POST",
            cache:false,
            async:false,
            url: "<?php echo CONTROLLER_BASE_URL; ?>",
            data: data,
            dataType:"json",
            success: function(data) {
            	result = data.result;
            }
        });
	} catch(err) {
		alert('ckeckPreFixExist POST ERROR! ');
        return false;
	};
	if (result == "1")
	{
		return true;
	}
	else
	{
        jQuery.validator.messages.ckeckPreFixExist = result;
		return false;
	}
}, "<?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_IMG_PREFIX_ERROR')?>");
//檢查圖片資料夾路徑
jQuery.validator.addMethod("ckeckDirExist", function(value) {

	//jQuery.validator.messages.ckeckDirExist = 'QOO';
    var imgUploadPath = jQuery("#imgUploadPath").val();
	var data = {
			'option':'com_zoearth_item_import',
			'view':'Import',
			'task':'ckeckDirExist',
			'imgUploadPath':imgUploadPath,
			};
    var result = 0;
    try {
        jQuery.ajax({
            type: "POST",
            cache:false,
            async:false,
            url: "<?php echo CONTROLLER_BASE_URL; ?>",
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
        jQuery.validator.messages.ckeckDirExist = result;
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
    <form id="mainForm" action="<?php echo Juri::base().'index.php'; ?>" method="post" name="adminForm" id="adminForm" class="form-horizontal"  enctype="multipart/form-data" >
    <div class="dataTables_wrapper">
    
    <fieldset>
        <legend><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT')?></legend>

            <div class="control-group">
                <label class="control-label" ><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_IMG_DIR')?></label>
                <div class="controls">
                    <div class="alert">
                        <strong><?php echo JText::_('JFIELD_NOTE_DESC')?>!</strong><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_STEP01_DESC')?>
                    </div>
                    <input value="<?php echo ($this->data['imgUploadPath'] ? $this->data['imgUploadPath']:'images') ?>" class="required ckeckDirExist" type="text" id="imgUploadPath" name="imgUploadPath" placeholder="<?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_IMG_DIR')?>">
                </div>
            </div>
        
            <div class="control-group">
                <label class="control-label" ><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_IMG_PREFIX')?></label>
                <div class="controls">
                    <div class="alert">
                        <strong><?php echo JText::_('JFIELD_NOTE_DESC')?>!</strong><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_STEP02_DESC')?>
                    </div>
                    <input value="" class="required ckeckPreFixExist" type="text" id="imgPrefix" name="imgPrefix" placeholder="<?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_IMG_PREFIX')?>">
                </div>
            </div>
        
            <div class="control-group">
                <label class="control-label" ><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_FILE_DESC')?></label>
                <div class="controls">
                    <div class="alert">
                        <strong><?php echo JText::_('JFIELD_NOTE_DESC')?>!</strong><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_STEP03_DESC')?>
                    </div>
                    <div class="rows">
                        <a rel="lightbox" href="<?php echo JUri::base().'components/com_zoearth_item_import/media/img/com_zoearth_item_import_step01.jpg';?>" alt="<?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_STEP01_DESC')?>">
                            <img style="width:50px;height:50px" src="<?php echo JUri::base().'components/com_zoearth_item_import/media/img/com_zoearth_item_import_step01.jpg';?>" class="img-circle">
                            <span><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_STEP01_TITLE')?></span>
                        </a>
                        <a rel="lightbox" href="<?php echo JUri::base().'components/com_zoearth_item_import/media/img/com_zoearth_item_import_step02.jpg';?>" alt="<?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_STEP02_DESC')?>">
                            <img style="width:50px;height:50px" src="<?php echo JUri::base().'components/com_zoearth_item_import/media/img/com_zoearth_item_import_step02.jpg';?>" class="img-circle">
                            <span><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_STEP02_TITLE')?></span>
                        </a>
                        <a rel="lightbox" href="<?php echo JUri::base().'components/com_zoearth_item_import/media/img/com_zoearth_item_import_step03.jpg';?>" alt="<?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_STEP03_DESC')?>">
                            <img style="width:50px;height:50px" src="<?php echo JUri::base().'components/com_zoearth_item_import/media/img/com_zoearth_item_import_step03.jpg';?>" class="img-circle">
                            <span><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_STEP03_TITLE')?></span>
                        </a>
                    </div>
                    <div class="rows">
                        <input type="file" class="required" id="evernoteFile" name="evernoteFile" >
                    </div>
                </div>
            </div>
        
        <button type="submit" class="btn btn-success"><i class="icon-ok"></i><?php echo JText::_('JSUBMIT')?></button>
    </fieldset>    
    </div>
    
    <input type="hidden" value="<?php echo $this->data['guid']?>" name="guid">
    <input type="hidden" value="<?php echo $this->data['option']?>" name="option">
    <input type="hidden" value="<?php echo $this->data['view']?>" name="view">
    <input type="hidden" value="<?php echo $this->data['task']?>" name="task">
    
    </form>
</div>
</div>