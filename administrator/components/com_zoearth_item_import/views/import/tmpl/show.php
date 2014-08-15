<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');

ZoeSetupJs::lightBox();
?>
<script language="Javascript">
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
            <button type="button" class="btn btn-info saveFiles"><i class="icon-download"></i><?php echo JText::_('COM_ZOEARTH_ITEM_IMPORT_SAVE_FILES')?></button>
    </fieldset>    
    </div>
    
</div>
</div>