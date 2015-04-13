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
function getImgDatas(type)
{
    alert(type);
    return '';
    jQuery('#imgDatas').dataTable({
        "ajax": "data/arrays.txt"
    });
}
</script>
<div class="alert">
  <strong><?php echo JText::_('COM_ZIC_NOTE')?></strong><?php echo JText::_('COM_ZIC_NOTE_CONTENT')?>
</div>
<form class="navbar-form">
    <table class="table table-bordered">
    <tr>
        <td>搜尋內容</td>
        <td>搜尋圖片</td>
        <td>執行動作</td>
    </tr>
    <tr>
        <td>
            <button class="btn actionBtns" onclick="getImgDatas('search_no_img_src')" >沒有圖片的連結</button>
        </td>
        <td>
            <button class="btn actionBtns" onclick="getImgDatas('search_no_used_img')" >沒有用到的圖片</button>
            <button class="btn actionBtns" onclick="getImgDatas('search_params_img')" >名稱或大小圖片</button>
        </td>
        <td>
            <button class="btn replace_img_to_img actionBtns" >取代圖片</button>
            <button class="btn replace_img_to_jpg actionBtns" >壓縮圖片</button>
        </td>
    </tr>
    </table>
</form>
<div id="tableDataDiv">
<table id="imgDatas" class="display table table-bordered" cellspacing="0" width="100%">
	<thead>
		<tr>
		    <th>
		        <input type="checkbox" name="imgDataCheck" class="imgDataCheck" value="1" >
		    </th>
			<th>圖片</th>
			<th>檔名</th>
			<th>大小</th>
			<th>日期</th>
			<th>資料</th>
		</tr>
	</thead>
	<tbody>
		<tr>
		    <td>
		        <input type="checkbox" name="imgDataCheck" class="imgDataCheck" value="1" >
		    </td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</tbody>
</table>
</div>