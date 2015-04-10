jQuery.validator.setDefaults({
    debug: false,
    onkeyup: false,
    onclick: false,
    onfocusout: false,
    onsubmit:true,
	ignore: null,
	ignore: 'input[type="hidden"]',
    errorClass:"error",
    validClass:"success",
    errorPlacement: function(error, element) {
        //20140331 zoearth 如果有相同名稱的狀況 則選用最後一個
        element = getLastNameElement(element);
        
        var inputName = getInputName(jQuery(element).attr('name'));
        
        if (jQuery(element).parent().find("."+inputName).length)
        {
            jQuery(element).parent().find("."+inputName).html('<i class="icon-remove-sign"></i> '+error.html());
        }
        else
        {
            jQuery(element).parent().append('<span class="'+inputName+' text-error"><i class="icon-remove-sign"></i> '+error.html()+'</span>');
        }
    },
    highlight: function(element, errorClass, validClass) {
        this.findByName(element.name).parents("div.control-group").addClass(errorClass);
    },
    unhighlight: function(element, errorClass, validClass) {
        jQuery(element).parents("div.control-group").removeClass(errorClass);
        
        element = getLastNameElement(element);
        var inputName = getInputName(jQuery(element).attr('name'));
        jQuery(element).parent().find("."+inputName).remove();
    }
});

function getLastNameElement(element)
{
    var nowName = jQuery(element).attr('name');
    if (jQuery("[name='"+nowName+"']").length > 0)
    {
        element = jQuery("[name='"+nowName+"']").last();
    }
    return element;
}

function getInputName(input)
{
    var inputName = input+"_vName";
    inputName = inputName.replace('[','');
    inputName = inputName.replace(']','');
    return inputName;
}

jQuery.extend(jQuery.validator.messages, {
	required: "必填",
	remote: "請修正此欄位",
	email: "請輸入正確的電子信箱",
	url: "請輸入合法的URL",
	date: "請輸入合法的日期",
	dateISO: "請輸入合法的日期 (ISO).",
	number: "請輸入數字",
	digits: "請輸入整數",
	creditcard: "請輸入合法的信用卡號碼",
	equalTo: "請重複輸入一次",
	accept: "請輸入有效的後缀字串",
	maxlength: jQuery.validator.format("請輸入長度不大於{0} 的字串"),
	minlength: jQuery.validator.format("請輸入長度不小於 {0} 的字串"),
	rangelength: jQuery.validator.format("請輸入長度介於 {0} 和 {1} 之間的字串"),
	range: jQuery.validator.format("請輸入介於 {0} 和 {1} 之間的數值"),
	max: jQuery.validator.format("請輸入不大於 {0} 的數值"),
	min: jQuery.validator.format("請輸入不小於 {0} 的數值")
});

jQuery.validator.addMethod("alpha_numeric", function(value) {
    if (jQuery.trim(value).length == 0) return true; // 因為 非必填，故空值時直接返回 true
    var letters = /^[a-zA-Z0-9]+$/;
    var result = letters.test(value);
    if (result)
    {
        return true;
    }
    else
    {
        return false;
    }
}, "請輸入英文與數字");

//20140609 zoearth 手機數字+10碼
jQuery.validator.addMethod("phone_mobile", function(value) {
    if (jQuery.trim(value).length == 0) return true; // 因為 非必填，故空值時直接返回 true
    var letters = /^[0-9]+$/;
    var result = letters.test(value);
    if (result)
    {
        if (jQuery.trim(value).length == 10 )
        {
            return true;
        }
    }
    return false;
}, "請輸入10碼數字");

// 台灣身份證字號格式檢查程式
jQuery.validator.addMethod("TWIDCheck", function(value, element, param)
{
    var a = new Array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'X', 'Y', 'W', 'Z', 'I', 'O');
    var b = new Array(1, 9, 8, 7, 6, 5, 4, 3, 2, 1);
    var c = new Array(2);
    var d;
    var e;
    var f;
    var g = 0;
    var h = /^[a-z](1|2)\d{8}$/i;
    if (value.search(h) == -1)
    {
        return false;
    }
    else
    {
        d = value.charAt(0).toUpperCase();
        f = value.charAt(9);
    };
    for (var i = 0; i < 26; i++)
    {
        if (d == a[i])//a==a
        {
            e = i + 10; //10
            c[0] = Math.floor(e / 10); //1
            c[1] = e - (c[0] * 10); //10-(1*10)
            break;
        };
    };
    for (var i = 0; i < b.length; i++)
    {
        if (i < 2)
        {
            g += c[i] * b[i];
        }
        else
        {
            g += parseInt(value.charAt(i - 1)) * b[i];
        };
    };
    if ((g % 10) == f)
    {
        return true;
    };
    if ((10 - (g % 10)) != f)
    {
        return false;
    };
    return true;
}, "請輸入有效的身份證字號!");

