<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');

$document = JFactory::getDocument();
//$document->addStyleSheet(JURI::root().'media/jui/css/bootstrap.min.css');
JHtml::_('bootstrap.tooltip');

JToolBarHelper::title(JText::_('COM_ZOEARTHCONTACTUS'));
$document = JFactory::getDocument();
jimport('joomla.html.pagination');
jimport('joomla.application.component.controller' );

?>
<style type="text/css">
.subhead-collapse
{
    display:none;
}
</style>
<script language="Javascript">
function sendAction(action)
{
    if (document.adminForm.boxchecked.value==0)
    {alert('<?php echo JTEXT::_("COM_ZOEARTHCONTACTUS_PLEASE_SELECT_ONE"); ?>');}
    else{Joomla.submitbutton(action);}
}
</script>
<div id="j-sidebar-container" class="span2">
    <?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<div class="row-fluid z0-list-HrOrgBranch">
    <div class="row-fluid">
        <!-- 
		<div style="float:right;">
		    <a data-toggle="modal" class="btn btn-primary z0-add" href="/egg/build/add">
		        <i class="icon-pencil"></i>TESTADD
		    </a>
	    </div>
	    -->
    	<legend><?php echo JText::_('COM_ZOEARTHCONTACTUS_MESSAGELIST'); ?>/<small><?php echo JText::_('COM_ZOEARTHCONTACTUS_MESSAGEDISPLAY'); ?></small></legend>
    </div>
    <form action="<?php echo JRoute::_('index.php?option=com_zoearthcontactus&task=edit'); ?>" method="post" name="adminForm" id="adminForm" >
    
    <div class="dataTables_wrapper">
        <table class="table table-striped table-bordered table-hover">
        <thead>        
            <tr class="table-header" style="background-color: #B1C5E6;" ><th><?php echo JTEXT::_("COM_ZOEARTHCONTACTUS_NAME"); ?></th><td><?php echo $this->rs_msg->name; ?></td></tr>
            <tr class="table-header" style="background-color: #B1C5E6;" ><th><?php echo JTEXT::_("COM_ZOEARTHCONTACTUS_EMAIL"); ?></th><td><?php echo $this->rs_msg->email; ?></td></tr>
        </thead>
        <tbody>
            <?php
            $content = json_decode($this->rs_msg->content);
            foreach ($content as $key => $val)
            {
                ?>
                <tr><td><?php echo $key; ?></td><td><?php echo nl2br($val); ?></td></tr>
                <?php
            }
            ?>
            <tr><td><?php echo JTEXT::_("JDATE"); ?></td><td><?php echo $this->rs_msg->idate; ?></td></tr>
            <tr>
                <td><?php echo JTEXT::_("COM_ZOEARTHCONTACTUS_REPLY_CONTENT"); ?></td>
                <td>
                <?php if (count($this->rs_reply) > 0 ):?>
                <?php foreach ($this->rs_reply as $replys): ?>
                    <div class="well">
                    <p><h3><?php echo $replys->name; ?>&nbsp;<span class="label"><?php echo substr($replys->idate,0,10); ?></span></h3></p>
                    <p><?php echo nl2br($replys->content); ?></p>
                    <a class="btn btn-danger" type="button" href="<?php echo JRoute::_('index.php?option=com_zoearthcontactus&task=unpublishReply&guid='.$this->rs_msg->guid.'&replyguid='.$replys->guid); ?>" ><i class="icon-trash"></i><?php echo JTEXT::_("JACTION_DELETE"); ?></a>
                    </div>
                <?php endforeach;?>
                <?php endif;?>
                </td></tr>
        </tbody>
        </table>
        
        <div class="control-group" style="display:none">
            <div class="control-label"><?php echo JTEXT::_("JTOOLBAR_PUBLISH"); ?></div>
            <div class="controls">
                <?php foreach ($this->ds_active as $key=>$val):?>
                    <input type="radio" <?php echo ($this->rs_msg->state === (String)$key ? "checked":""); ?> value="<?php echo $key;?>" name="state"><span class="lbl"> <?php echo $val; ?></span>
                <?php endforeach;?>
            </div>
        </div>
        <div class="control-group">
            <div class="control-label"><?php echo JTEXT::_("COM_ZOEARTHCONTACTUS_REPLY"); ?></div>
            <div class="controls">
                <textarea maxlength="50" name="reply" class="span5 limited" id="form-field-9" data-maxlength="50" placeholder="<?php echo JTEXT::_("COM_ZOEARTHCONTACTUS_REPLY_CONTENT"); ?>"><?php echo @$this->row['reply']; ?></textarea>
            </div>
        </div>
        <div class="row-fluid">
            <div class="form-actions">
                <button class="btn btn-info" type="submit">
                    <i class="icon-ok"></i> <?php echo JTEXT::_("COM_ZOEARTHCONTACTUS_REPLY"); ?>
                </button>
            </div>
        </div>
    </div>
    <input type="hidden" value="com_zoearthcontactus" name="option">
    <input type="hidden" value="<?php echo $this->rs_msg->guid; ?>" name="guid">
    <input type="hidden" value="edit" name="task">
    <input type="hidden" value="messageList" name="controller">
    <?php echo JHTML::_('form.token'); ?>
    </form>
</div>
</div>