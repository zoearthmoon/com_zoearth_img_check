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
    <div class="row-fluid">
    	<?php echo ZoeSayPath::showPath(); ?>
    </div>
    <blockquote class="alert alert-success">
        <p><?php echo $this->data['title']?></p>
        <small><?php echo $this->data['message']?></small>
    </blockquote>
</div>
</div>