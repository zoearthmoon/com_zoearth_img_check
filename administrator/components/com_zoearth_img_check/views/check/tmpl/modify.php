<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');
ZoeSetupJs::validate();
ZoeSetupJs::datePicker();
?>
<script type="text/javascript">
//var baseUrl = "";
jQuery(function(){
    jQuery(".<?php echo CONTROLLER.'form' ?>").validate();
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
    <form action="<?php echo Juri::base().'index.php'; ?>" method="post" name="adminForm" id="adminForm" class="<?php echo CONTROLLER.'form' ?> form-horizontal" enctype="multipart/form-data" >

    <div class="control-group">
        <label class="control-label" >銀行名稱</label>
        <div class="controls">
            <input type="text" class="required" id="name" name="name" value="<?php echo $this->data['name'];?>" placeholder="銀行名稱" >
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" >銀行代號</label>
        <div class="controls">
            <input type="text" class="required" id="code" name="code" value="<?php echo $this->data['code'];?>" placeholder="銀行代號" >
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" >內容</label>
        <div class="controls">
            <textarea rows="3" name="note" id="note"><?php echo $this->data['note'];?></textarea>
        </div>
    </div>
    
    <div class="form-actions">
        <button class="btn btn-info saveButton" type="submit"><i class="icon-ok"></i> 送出表單</button>
        &nbsp; &nbsp; &nbsp;
        <button class="btn" type="reset"><i class="icon-undo"></i> 重新填寫</button>
    </div>
    <input type="hidden" value="<?php echo $this->data['guid']?>" name="guid">
    <input type="hidden" value="<?php echo $this->data['option']?>" name="option">
    <input type="hidden" value="<?php echo $this->data['view']?>" name="view">
    <input type="hidden" value="<?php echo $this->data['task']?>" name="task">
    </form>
</div>
</div>