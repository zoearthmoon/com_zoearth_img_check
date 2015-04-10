
//20140826 zoearth 跳出視窗用
function showSqueezeBox(button,url,onOpen)
{
    var bw = jQuery(window).width();
    var bh = jQuery(window).height();
    bw = parseInt(bw*0.8);
    bh = parseInt(bh*0.9);
    
    SqueezeBox.initialize();
    //SqueezeBox.presets.concat(presets);
    if (onOpen)
    {
        SqueezeBox.presets.onOpen = onOpen;
    }
    
    SqueezeBox.fromElement(button, {
        handler : 'iframe',
        url : url,
        size : {
            x : bw,
            y : bh
        }
    });
    return SqueezeBox;
    
    /*
    presets: {
        onOpen: function () {
        },
        onClose: function () {
        },
        onUpdate: function () {
        },
        onResize: function () {
        },
        onMove: function () {
        },
        onShow: function () {
        },
        onHide: function () {
        },
    */
}

//20140520 zoearth 修改表單符合ajax方式
function setAjaxAdd(target,key,url)
{
    jQuery(target).find(".header").remove();
    jQuery(target).find("#j-sidebar-container").remove();
    jQuery(target).find(".ZoePath").remove();
    
    jQuery(target).find("#adminForm").attr("name",key+"AjaxForm").attr("id",key+"AjaxForm");
    jQuery("#"+key+"AjaxForm").validate({
        submitHandler: function(form) {
            jQuery("#"+key+"AjaxForm .saveButton").attr('disabled',true);
            jQuery.post(url, jQuery("#"+key+"AjaxForm").serialize(),
            function(data){
                var isError = jQuery(data).find(".alert-error").length;
                //20140520 zoearth 如果有錯誤訊息 則不關閉modal
                if (isError > 0 )
                {
                    alert("發生錯誤! "+jQuery(data).find(".alert-error p").html());
                }
                //20140520 zoearth 如果沒有錯誤訊息 則關閉modal
                else
                {
                    alert("新增成功!");
                    jQuery("#"+key+"Modal").modal('hide');
                }
                jQuery("#"+key+"AjaxForm .saveButton").attr('disabled',false);
            });
        }
    });
}

//20140520 zoearth 取消值按鈕
function NoneSelect(key)
{
    jQuery("#"+key+"Name").val("");
    jQuery("#"+key+"Guid").val("");
    jQuery("#"+key+"Data").html("");
}

//20140429 zoearth 刪除功能
function ZoeDelete(guid)
{
    var y = confirm("確定停止發佈資料?");
    if (y)
    {
        document.adminForm.task.value='delete';
        document.adminForm.guid.value=guid;
        Joomla.submitform();
    }
}

//20140429 zoearth 發佈功能
function ZoeActive(guid)
{
    var y = confirm("確定發佈資料?");
    if (y)
    {
        document.adminForm.task.value='active';
        document.adminForm.guid.value=guid;
        Joomla.submitform();
    }
}

//20130729 zoearth 檔案用的javascript
function addFileInput(type,className)
{
    if (className)
    {
        var addClassName = '.'+className+' ';
    }
    else
    {
        var addClassName = '';
    }
    var nowFileCount = jQuery(addClassName+"."+type+"Div").length;
    nowFileCount++;
    var html = '';
    //addFileDiv
    html += '<div class="'+type+'Div">';
    html += '<div class="span4" >';
    html += '<input type="text" name="'+type+'Note['+nowFileCount+']" placeholder="檔案說明" >';
    html += '</div>';
    html += '<div class="span5" >';
    if (addClassName == '')
    {
        html += '<div class="ace-file-input" >';
    }
    else
    {
        html += '<div class="" >';
    }
    html += '<input type="file" class="'+type+nowFileCount+'" id="'+type+nowFileCount+'" name="'+type+'['+nowFileCount+']" >';
    html += '</div></div></div>';
    jQuery(addClassName+".add"+type+"Div").append(html);
}

function getLastKey(divName,key)
{
    while (jQuery("#"+divName+key).length > 0)
    {
        key++;
    }
    return key; 
}

//20130801 zoearth 刪除檔案
function deleteFiles(guid)
{
    
    jQuery.post("/person/files/delete", { "guid": guid },function(data){
        if (data != "1")
        {
            console.log(data);
            alert("刪除失敗");
        }
        else
        {
            alert("刪除成功");
            jQuery('#fileSpan'+guid).remove();
        }
    });
}

function getInputNameArrayNewKey(name)
{
    for (var i=1;i <= 999;i++)
    {
        if (jQuery('input[name*='+name+'\\['+i+'\\]]').length == 0 )
        {
            return i;
        }
    }
}
