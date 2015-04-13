<?php
/*
@author zoearth
*/
defined('_JEXEC') or die('Restricted access');
$srcPath = JUri::base().'components/com_zoearth_img_check/media/js/DataTables-1.10.6/';
?>
<!-- 20150413 zoearth DataTables Table plug-in for jQuery -->
<link rel="stylesheet" type="text/css" href="<?php echo $srcPath?>css/jquery.dataTables.css">
<script type="text/javascript" language="javascript" src="<?php echo $srcPath?>js/jquery.dataTables.js"></script>
<script language="Javascript">
//20150413 zoearth 取得
function getImgDatas(actionName)
{
    var addUrl  = '&task=searchFiles&actionName='+actionName;
    var dataUrl = 'index.php?'+jQuery("#imgCheckForm").serialize()+addUrl;
    
    jQuery('#imgDatas').dataTable({
        "ajax": dataUrl,
    });
}

//20150413 zoearth 選擇全部
function selectAll(btnObj)
{
    jQuery(".itemCheckBox").attr('checked',jQuery(btnObj).attr('checked') == 'checked' ? true:false );
}
</script>
<div id="j-sidebar-container" class="span2">
    <?php echo ZoeSayPath::outputMenu(); ?>
</div>
<div id="j-main-container" class="span10">
    <div class="row-fluid ZoePath">
    	<?php echo ZoeSayPath::showPath(); ?>
    </div>
    <div class="alert">
      <strong><?php echo JText::_('COM_ZIC_NOTE')?></strong><?php echo JText::_('COM_ZIC_NOTE_CONTENT')?>
    </div>
    <form id="imgCheckForm" class="navbar-form">
        <input type="hidden" value="com_zoearth_img_check" name="option">
        <input type="hidden" value="check" name="view">
        
        <table class="table table-bordered">
        <tr>
            <td>搜尋內容</td>
            <td>搜尋圖片</td>
            <td>執行動作</td>
        </tr>
        <tr>
            <td>
                <button type="button" class="btn btn-info actionBtns" onclick="getImgDatas('search_no_img_src')" ><i class="icon-search fa search"></i>沒有圖片的連結</button>
            </td>
            <td>
                <button type="button" class="btn btn-success actionBtns" onclick="getImgDatas('search_no_used_img')" ><i class="icon-search fa search"></i>沒有用到的圖片</button>
                <table class="table table-bordered">
                <tr>
                    <td>
                        <button type="button" class="btn btn-primary actionBtns" onclick="getImgDatas('search_params_img')" ><i class="icon-search fa search"></i>名稱或大小圖片</button><br>
                        <div class="btn-group">
                            <button type="button" class="btn" disabled>名稱</button>
                            <input type="text" id="fileName" name="fileName" placeholder="請輸入名稱">
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn" disabled>大小</button>
                            <input type="text" id="fileSizeMin" name="fileSizeMin" placeholder="最小">
                            <input type="text" id="fileSizeMax" name="fileSizeMax" placeholder="最大">
                        </div>
                    </td>
                </tr>
                </table>
            </td>
            <td>
                <button type="button" class="btn btn-info actionBtns" onclick="actionImgs('replace_img_to_img')" ><i class="icon-share-alt fa fa-share-square-o"></i>取代圖片</button>
                <button type="button" class="btn btn-success actionBtns" onclick="actionImgs('replace_img_to_jpg')" ><i class="icon-list fa fa-database"></i>壓縮圖片</button>
                <button type="button" class="btn btn-danger actionBtns" onclick="actionImgs('replace_img_to_null')" ><i class="icon-trash fa fa-trash"></i>刪除圖片</button>
            </td>
        </tr>
        </table>
    </form>
    <div id="tableDataDiv" style="min-height: 500px">
    <legend>Legend</legend>
    
    <div class="control-group">
        <input type="checkbox" onclick="selectAll(this)" >選擇
    </div>
    
    <table id="imgDatas" class="display table table-bordered" cellspacing="0" width="100%">
    	<thead>
    		<tr>
    		    <th>勾選</th>
    			<th>圖片</th>
    			<th>檔名</th>
    			<th>大小</th>
    			<th>日期</th>
    			<th>資料</th>
    		</tr>
    	</thead>
    	<tbody>
    		<tr>
    		    <td colspan="6" >請使用上方搜尋功能</td>
    		</tr>
    	</tbody>
    </table>
    </div>
</div>