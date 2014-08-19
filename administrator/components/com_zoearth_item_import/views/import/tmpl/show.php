<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');
ZoeSetupJs::lightBox();
?>
<script language="Javascript">
//20140819 zoearth 存入圖片
jQuery(document).ready(function() {
    jQuery(".saveImgFiles").click(function(){
        jQuery("#saveImgFiles").attr('disabled',true);
        var postUrl = "<?php echo JUri::base().'index.php?option=com_zoearth_item_import&view=Import&task=saveImgs'?>";
        jQuery.post(postUrl,jQuery("#saveImgForm").serialize(), function(data) {
            result = data.result;
            jQuery("#saveImgFiles").attr('disabled',false);
            if (result != "1")
        	{
                alert(result);
        	}
            else
            {
                alert("<?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_SAVE_FILES_SUCCESS')?>");
            }
        },"json");
    });
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

<!-- 預覽用 -->
<div id="preViewModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel"><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_PREVIEW')?></h3>
    </div>
    <div class="modal-body">
        <p>
        <?php echo $this->data['preViewFileContent'] ?>
        </p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
</div>

<div class="row-fluid z0-list-HrOrgBranch">
    <div class="row-fluid ZoePath">
    	<?php echo ZoeSayPath::showPath(); ?>
    </div>
    
    <div class="dataTables_wrapper">
    <fieldset>
        <legend><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT')?></legend>

        
            <div class="control-group">
                <label class="control-label" ><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_SOURCE_CODE')?></label>
                <div class="controls">
                    <a href="#preViewModal" role="button" class="btn" data-toggle="modal">
                        <i class="icon-eye-open"></i>
                        <?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_PREVIEW')?>
                    </a><br>
                    <div class="alert">
                        <strong><?php echo JText::_('JFIELD_NOTE_DESC')?>!</strong><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_SOURCE_CODE_DESC')?>
                    </div>
                    <textarea name="forCopy"><?php echo $this->data['fileContent'] ?></textarea>
                </div>
            </div>
        
            <?php if (is_array($this->data['preUploadFiles']) && count($this->data['preUploadFiles']) > 0 ):?>
            <div class="control-group">
                <label class="control-label" ><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_SAVE_FILES')?></label>
                <div class="controls">
                    <!-- 寫入圖片 -->
                    <button type="button" class="btn btn-info saveImgFiles"><i class="icon-download"></i><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_SAVE_FILES')?></button>
                    <form id="saveImgForm" name="saveImgForm" action="<?php echo Juri::base().'index.php'; ?>" method="post" >
                    <ul class="thumbnails">
                        <?php foreach ($this->data['preUploadFiles'] as $file):?>
                        <li class="span3">
                        <div class="thumbnail">
                            <img src="<?php echo $file['tmpView']?>" >
                            <h3><?php echo $file['newFile']?></h3>
                            <input type="hidden" name="tmpFile[]" value="<?php echo $file['tmpFile']?>">
                            <input type="hidden" name="newFile[]" value="<?php echo $file['newFile']?>">
                        </div>
                        </li>
                        <?php endforeach;?>
                    </ul>
                    </form>
                </div>
            </div>
            <?php endif;?>
    </fieldset>    
    </div>
    
</div>
</div>