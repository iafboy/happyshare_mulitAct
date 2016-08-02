/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/9/17 22:04
 */

/*百度统计*/
 var _hmt = _hmt || [];
 (function() {
 var hm = document.createElement("script");
 hm.src = "//hm.baidu.com/hm.js?70f4eef64dfffd1d9222d9fa15aa10bf";
 var s = document.getElementsByTagName("script")[0];
 s.parentNode.insertBefore(hm, s);
 })();

document.write('<script language=javascript src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>');
//document.write('<script language=javascript src="../js/wx-config.js"></script>');
var baseUrl = 'http://www.51hjfx.com/leshare/';

//var baseUrl = 'http://www.51hjfx.com/leshare/';
var baseSecureUrl = 'https://www.51hjfx.com/leshare/';
var domain = 'www.51hjfx.com';
var base_event = 'tap';
var DEV_MODE = 'INFO';

var fakeRegister =false;

function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return null;
}

$(function () {
    var nEvent = function(){
        var e = window.event || event;
        e.preventDefault();
    };

    $('.header .menu').on(base_event, function () {
        var w = $(window).width();
        $('.mlist-cont').width(w-35);
        $('.menu-cont').animate({
            'left' : '0'
        },100,function(){
            $('.menu-cont').css({
                'background' : 'rgba(0,0,0,.4)'
            });
        });
        if($('.menu-cont').css("left") == "0px"){
            document.addEventListener('touchmove', nEvent, false);
        }else{
            document.removeEventListener('touchmove',nEvent,false);
        }
    });

    $('.menu-cont .nav_close').on(base_event, function () {
        $('.menu-cont').css({
            'background' : 'none'
        });
        $('.menu-cont').animate({
            'left' : '-100%'
        },100);
        document.removeEventListener('touchmove',nEvent,false);
    });

    if($('#mlist').length > 0){
        var myScroll = new IScroll('#mlist');
        $('.mlist .list-my .toolbar').on(base_event, function () {
            $('.mylist').toggle();
            $(this).toggleClass('on');
            myScroll.refresh();
        });
        $('.mlist .list-shop .toolbar').on(base_event, function () {
            $('.shop-list').toggle();
            $(this).toggleClass('on');
            myScroll.refresh();
        });
    }

    $('.header .search').on(base_event, function () {
        $('.header .search-cont').show();
    });
    $('.header .search-cont .search-btn').on(base_event, function () {
        $('.header .search-cont').hide();
    });
    $('.header .search-cont input').keydown(function(event){
        var key = event.which;
        var text = $('.header .search-cont input').val();
        if(key == 13 && text && text.length > 0){
            console.log(encodeURI('searchlist.html?key='+text));
            window.location.href = encodeURI('searchlist.html?key='+text);
        }
    });

    //$('body').on(base_event,'.menu-cont .login',function(){
    //    window.location.href = 'login.html';
    //});

    $('body').on(base_event,'.tlCont .ui-rmme',function(){
        $(this).toggleClass('on');
    });
    $('body').on(base_event,'.tlCont .ui-login-close',function(){
        $('.tlBg,.tlCont,.tlCont .bd').hide();
        document.removeEventListener('touchmove',nEvent,false);
    });



    function errTips(text){
        $('body').append('<div class="tipstext">'+text+'</div>');
        setTimeout(function(){
            $('.tipstext').fadeOut(function(){
                $('.tipstext').remove();
            });
        },1500);
    }
    window.errTips = errTips;

    //公共菜单链接跳转
    $('body').on(base_event,'.header .buy',function(){
        window.location.href = 'cart.html';
    });
    $('body').on(base_event,'.nav-list .index',function(){
        window.location.href = 'index.html';
    });
    $('body').on(base_event,'.nav-list .share',function(){
        window.location.href = 'shareindex.html';
    });
    $('body').on(base_event,'.nav-list .jchd',function(){
        window.location.href = 'excactivity.html';
    });

    $('body').on(base_event,'.mylist li',function(){
        var _idx = $(this).index();
        if(_idx == 0){
            window.location.href = 'cart.html';
        }else if(_idx == 1){
            window.location.href = 'myorder.html';
        }else if(_idx == 2){
            window.location.href = 'myscore.html';
        }else if(_idx == 3){
            window.location.href = 'mysale.html';
        }else if(_idx == 4){
            window.location.href = 'myshare.html';
        }else if(_idx == 5){
            window.location.href = 'myinfo.html';
        }else if(_idx == 6){
            window.location.href = 'mycollect.html';
        }
    });
    $('body').on(base_event,'.shop-list li',function(){
        var _idx = $(this).index();
        //TODO 需要带上用户信息
        if(_idx == 0){
            window.location.href = 'themeg.html';
        }else if(_idx == 1){
            window.location.href = 'ztglist.html?ztg=bpg';
        }else if(_idx == 2){
            window.location.href = 'ztglist.html?ztg=jkg';
        }else if(_idx == 3){
            window.location.href = 'ztglist.html?ztg=kjg';
        }else if(_idx == 4){
            window.location.href = 'ztglist.html?ztg=crg';
        }else if(_idx == 5){
             window.location.href = 'ztglist.html?ztg=fxg';
        }else if(_idx == 6){
             window.location.href = 'ztglist.html?ztg=hdg';
        }
    });

   /* setTimeout(function(){
        $('img').each(function(){
            var _this = $(this);
            if(_this.hasClass('ui-img')){
                var scale = _this.attr('data-width') / _this.attr('data-height');
                var image = new Image();
                image.src = _this.attr('src');
                image.onload = function(){
                    if(image.width/image.height >= scale){
                        _this.css({
                            'height' : _this.attr('data-height') + 'px',
                            'width' : 'auto'
                        });
                        var wy = (_this.width()-_this.attr('data-width'))/2;
                        if(wy > 0){
                            wy = '-'+wy
                        }else{
                            wy = Math.abs(wy);
                        }
                        _this.css({
                            'transform' : 'translate('+wy+'px,0px)'
                        });
                    }else{
                        _this.css({
                            'width' : '100%',
                            'height' : 'auto'
                        });
                        var wy = (_this.height()-_this.attr('data-height'))/2;
                        if(wy > 0){
                            wy = '-'+wy;
                        }else{
                            wy = Math.abs(wy);
                        }
                        _this.css({
                            'transform' : 'translate(0px,'+wy+'px)'
                        });
                    }
                }
            }
        });
    },10);*/
});


function isValid(str){
    return str && (str+'').trim().length>0;
}

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
            params[$(this).attr('name')] = $(this).val();
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


$.fn.formClear = function() {
    target = this;
    $(target).each(function(){
       $(this).find('input').val('');
       $(this).find('textarea').val('');
       $(this).find('textarea').text('');
       $(this).find('select').val('');
       $(this).find('input[type="checkbox"]').removeAttr('checked');
       $(this).find('input[type="radio"]').removeAttr('checked');
    });
};

$.fn.formLoad = function(obj){
    obj = obj || {};
    var that = this;
    for(var k in obj){
        var v = obj[k];
        $(that).each(function () {
            $(this).find('input[name="'+k+'"]').val(v);
            $(this).find('textarea[name="'+k+'"]').text(v);
        });
    }
};

/**
 * Tool Functions to verify
 *
 *
 var valid_arr = [
     {field:'act_name',regex:'',required:true,errMsg:'活动名称不能为空！'},
     {field:'act_start_date',regex:'',required:true,errMsg:'活动开始日期不能为空！'},
     {field:'act_end_date',regex:'',required:true,errMsg:'活动结束日期不能为空！'},
     {field:'act_memo',regex:'',required:true,errMsg:'活动说明不能为空！'},
     {field:'special_type',regex:'',required:true,errMsg:'活动类型不能为空！'},
     {field:'imgurl',regex:'',required:mode=='create',errMsg:'活动图片不能为空！'}
 ];
 if(validFormParams(params,valid_arr)!==true){
      return false;
 }
 *
 *
 * @param params
 * @param arr
 * @returns {boolean}
 */
function validFormParams(params,arr){
    if(arr && arr.length>0){
        for(var i = 0;i < arr.length; i++){
            var item = arr[i];
            var regex = item.regex;
            var value = params[item['field']];
            if(item['required']===true){
                if(!isValid(value)){
                    return item['errMsg'];
                }else{
                    if(isValid(regex) && !value.match(regex)){
                        return item['errMsg'];
                    }
                }
            }else{
                if(is_valid_str(value)){
                    if(isValid(regex)){
                        if(isValid(regex) && !value.match(regex)){
                            return item['errMsg'];
                        }
                    }
                }
            }
        }
    }
    return true;
}



function getCustomerId(cb){
    $.get(baseUrl + 'newwap/APIs/customer/getCustomerId.php',{},function(data){
        data = $.parseJSON(data);
        if(cb){
            cb(getDefaultCustomerId(data.data.customerId));
        }
    });
}



function getDefaultCustomerId(customerId){
    if(!customerId){
        if(DEV_MODE=='DEBUG'){
            return 16;
        }else{
            return customerId;
        }
    }else{
        return customerId;
    }
}

/**
 * add product to cart
 * @param productId
 * @returns {*}
 */
function addToMyCart(productId){
    detectLoginAndDeal(function (customerId) {
        if(!isValid(productId)){
            return errTips('参数错误，请稍后重试！');
        }
        var buyshopURL = baseUrl + 'newwap/APIs/shoppingCart/addProduct2Cart.php';
        if(!customerId || (''+customerId).trim().length==0){
            return window.location = 'login.html';
        }
        $.getJSON(buyshopURL,{
            customerId : customerId,
            productId : productId
        },function(data){
            if(data.resultCode == 0){
                $('body').append('<div class="tipstext">添加购物车成功</div>');
                setTimeout(function(){
                    $('.tipstext').fadeOut(function(){
                        $('.tipstext').remove();
                    });
                },1500);
            }else{
                //console.log(data.resultMsg);
            }
        });
    });

}


/**
 * get Current Url , exclude content after #
 * @returns {*}
 */
function getUrl(){
    var loc = window.location.href;
    if(loc.indexOf('#') != -1){
        var index = loc.indexOf('#');
        return loc.substring(0,index);
    }
    return loc;
}


/**
 * check whether login, if not , navigate to login page
 */
function checkLoginAndDeal(cb){
    detectLoginAndDeal(cb);
    // 获取基本信息
    /*getCustomerId(function(customerId){

        if(!customerId || (''+customerId).trim().length==0){
            window.location = 'login.html';
        }
        if(cb && $.isFunction(cb)){
            cb(customerId);
        }

    });*/
}
function getMyBaseInfo(cb){
    detectLoginAndDeal(function (customerId) {
        var myinfoURL = baseUrl+'newwap/APIs/customer/showRegShareInfo.php?customerId='+customerId;
        $.get(myinfoURL,{},function(data){
            data= $.parseJSON(data);
            var info = data.data;
            if(cb && $.isFunction(cb)){
                cb(info);
            }
        });
    });
}

function getMyCartInfo(cb){
    // 获取基本信息
    getCustomerId(function(customerId){
        if(!customerId || (''+customerId).trim().length==0){
            window.location = 'login.html';
        }
        var cartURL = baseUrl + 'newwap/APIs/shoppingCart/getCartDetail.php?customerId='+customerId;
        $.getJSON(cartURL,function(data){
            if(cb && $.isFunction(cb)){
                cb(data);
            }
        });
    });
}


function getMyCollectInfo(cb){
    // 获取基本信息
    getCustomerId(function(customerId){
        if(!customerId || (''+customerId).trim().length==0){
            window.location = 'login.html';
        }
        if(cb && $.isFunction(cb)){
            cb(customerId);
        }
    });
}

function getMyShareInfo(cb) {
    // 获取基本信息
    getCustomerId(function (customerId) {
        if (!customerId || ('' + customerId).trim().length == 0) {
            window.location = 'login.html';
        }
        if (cb && $.isFunction(cb)) {
            cb(customerId);
        }
    });
}


function addToMyCollect(productId,sucCb,failCb){
    detectLoginAndDeal(function (customerId) {
        var collectionURL = baseUrl + 'newwap/APIs/product/publishCollection.php';
        $.get(collectionURL,{customerId:customerId,productId:productId},function(data){
            data = $.parseJSON(data);
            if(data.resultCode == 0){
                errTips('收藏成功!');
                if(sucCb && $.isFunction(sucCb)){
                    sucCb();
                }
            }else{
                if(failCb && $.isFunction(failCb)){
                    failCb();
                }
                return errTips(data.resultMsg);
            }
        });
    });
}
function detectLoginAndDeal(cb){
    var url = getUrl();
    getCustomerId(function (customerId) {
        if(!isValid(customerId)){
            window.location = 'login.html?redirect_uri='+encodeURIComponent(url);
        }else{
            if(cb && $.isFunction(cb)){
                cb(customerId);
            }
        }
    });
}
function directPay(productId){
    detectLoginAndDeal(function () {
        window.location = 'to_pay_1.html?productIds='+productId;
    });
}

//评论列表
function getComments(productId,cb){
    var commentURL = baseUrl + 'newwap/APIs/product/getProductComments.php?product_id='+productId+'&num=20';
    $.getJSON(commentURL,function(data){
        if(cb && $.isFunction(cb)){
            cb(data);
        }
    });
}

function publishComment(productId,comment,cb){
    var postURL = baseUrl + 'newwap/APIs/product/publishComments.php';
    detectLoginAndDeal(function (customerId) {
        $.get(postURL,{customerId : customerId, productId : productId, comment : comment
        },function(data){
            var data = $.parseJSON(data);
            if(cb && $.isFunction(cb)){
                cb(data);
            }
        });
    });
}

getCustomerId(function(customerId){
    if(isValid(customerId)){
        $('.menu-cont .last .login').text('登出').addClass('ui-logout');
    }else{
        $('.menu-cont .last .login').text('登录/注册').removeClass('ui-logout');
    }
});

$('body').on(base_event,'.login',function(){
    if(!$(this).hasClass('ui-logout')){
        window.location = 'login.html';
    }else{
        var logoutURL = baseUrl + 'newwap/APIs/logout.php';
        getCustomerId(function (customerId) {
            $.getJSON(logoutURL,{customerId:customerId}, function (data) {
                if(data.resultCode == 0){
                    window.location = 'index.html';
                }else{
                    return errTips('登录失败！');
                }
            });
        });
    }

});

/**
 *
 * @param numStr
 * @param decimal opcity to keep
 * @param tf whether remove '0' after '.'
 * @returns {*}
 */
function parseFormatNum(numStr,decimal,tf){
    var dealStr = '';
    if(isValid(numStr) || numStr == 0){
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
    if(!isValid(str)){
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

function verifyPwd(pwd){
    return isValid(pwd) && pwd.trim().length >= 6 && /\d+/.test(pwd) && /[a-z A-Z]+/.test(pwd);
}
