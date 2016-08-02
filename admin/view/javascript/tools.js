$.fn.formJSON = $.fn.serializeObject = function() {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [ o[this.name] ];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};


$.fn.targetJSON = function(){
    var obj = this;
    var params = {};
    $(obj).each(function(){
        $(this).find('input').each(function(){
            var that = this;
            if($(that).attr('type')=='checkbox' ||$(that).attr('type')=='radio' ){
                if($(that).attr('checked')){
                    params[$(this).attr('name')] = $(this).val();
                }
            }else{
                params[$(this).attr('name')] = $(this).val();
            }
        });
        $(this).find('textarea').each(function(){
            params[$(this).attr('name')] = $(this).text();
        });

        $(this).find('select').each(function(){
            params[$(this).attr('name')] = $(this).val();
        });
    });
    return params;
};

function is_valid_str(str){
    return str && (str+'').trim().length >0;
}
function validFormParams(params,arr){
    if(arr && arr.length>0){
        for(var i = 0;i < arr.length; i++){
            var item = arr[i];
            if(item['required']===true){
                if(!is_valid_str(params[item['field']])){
                    showErrorText(item['errMsg']);
                    return false;
                }
            }
        }
    }
    return true;
}

function GetQueryString(name)
{
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if(r!=null)return  unescape(r[2]); return null;
}

/**
 *
 * @param numStr
 * @param decimal opcity to keep
 * @param tf whether remove '0' after '.'
 * @returns {*}
 */
function parseFormatNum(numStr,decimal,tf){
    var dealStr = '';
    if(is_valid_str(numStr) || numStr == 0){
        numStr = numStr + '';
        dealStr =  getNumberWithDecimal(numStr,decimal,tf);
    }else {
        dealStr = getNumberWithDecimal("0", decimal,tf);
    }
    if(tf !== false){
        if(dealStr.indexOf('.') != -1){
            var pos = dealStr.indexOf('.');
            var bool= false;
            for(var i = pos+1;i<dealStr.length;i++){
                if(dealStr.charAt(i)!='0'){
                    bool = true;
                }
            }
            if(!bool){
                dealStr = dealStr.substring(0,pos);
            }
        }
    }
    return dealStr;
}
function getNumberWithDecimal(numStr,decimal){
    if(!decimal){
        decimal = 0;
    }
    //8.34
    //6
    var index = numStr.indexOf('.'); // 1
    var length = numStr.length;  // 4
    var decimalLen = (length-1) - index;

    if(index== -1){

        if(decimal >0){
            numStr += '.';
            for(var i = 0;i < decimal;i++){
                numStr += '0';
            }
        }
        return numStr;
    }else{
        //99.0
        //888888888.301
        //9999999.80
        if(decimalLen > decimal){
            numStr = numStr.substr(0,length-(decimalLen-decimal)-1);
        }else if(decimalLen < decimal){
            for(var i = 0;i < decimal-decimalLen;i++){
                numStr += '0';
            }
        }else{
            return numStr;
        }
    }
    return numStr;
}

function getNumberOpacity(str){
    if(!is_valid_str(str)){
        return 0;
    }
    str = (str + '').trim();
    if(str.indexOf('.') == -1){
        return 0;
    }
    var len = str.length;
    var pos = str.indexOf('.');
    return len- 1 -pos;
}

function verifyBankCard(cardNo){
    return is_valid_str(cardNo) && /^\d{16}|\d{19}$/.test(cardNo);
}