//20140415 zoearth google Chart

google.load('visualization', '1', {'packages':['corechart']});

//20140415 zoearth 設定選項
function setupOptions(options)
{
    var goOptions = {};

    if (options.type == 'PieChart')
    {
        goOptions.title  = options.title;
        goOptions.width  = options.width;
        goOptions.height = options.height;
    }
    else if (options.type == 'AreaChart')
    {
        goOptions.title  = options.title;
        goOptions.width  = options.width;
        goOptions.height = options.height;
        goOptions.hAxis  = {titleTextStyle: {color: '#333'}};
        var vAxes0 = {titleTextStyle: {fontSize:15},'textStyle':{'color': '#aaaaaa'}};
        var vAxes1 = {titleTextStyle: {fontSize:15},'textStyle':{'color': '#aaaaaa'}};
        if (options.vAxes0_format)
        {
            vAxes0.format = options.vAxes0_format;
        }
        else
        {
            vAxes0.format = 'NT #,###.##';
        }
        if (options.vAxes1_format)
        {
            vAxes1.format = options.vAxes1_format;
        }
        else
        {
            vAxes1.format = '# 筆';
        }
        //20140522 zoearth 使用雙X軸
        if (goOptions.twoX)
        {
            goOptions.series = {0:{targetAxisIndex:0},1:{targetAxisIndex:1}};
            goOptions.vAxes  = {0:vAxes0,1:vAxes1};
        }
    }    
    return goOptions;
}

//20140415 zoearth 讀取設定塞入資料
function setupWidgetData(options)
{
    if (!(options.url && options.id))
    {
        alert('ERROR!');
        return false;
    }
    var addUrl = options.url;
    jQuery("#"+options.id+"Setup .searchInput").each(function(){
        if (jQuery(this).val())
        {
            addUrl += '&'+jQuery(this).attr('name')+'='+jQuery(this).val();
        }
    });
    
    try {
        var output = [];
        jQuery.ajax({
            type: "GET",
            cache:false,
            async:false,
            url: addUrl,
            dataType:"json",
            success: function(data) {
                output = data;
            },
            error: function(data){alert('錯誤：取值失敗。');}
        });
        return output;
    } catch(err) {
        alert('錯誤：取值失敗。');
        return false;
    };
}