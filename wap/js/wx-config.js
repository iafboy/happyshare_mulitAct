

var is_in_wechat = 0;
$(function () {
    var url = getUrl();
    var appId = 'wx4ba8a02a5ef1d924';
    var secretId  = '4f4cc4d554de08b0d7628da3164ee92e';
    var getSignatureURL = baseUrl + 'newwap/APIs/wxSession/getSignatureOfWx.php';

    $.post(getSignatureURL,{url:url}, function (data) {
        var jsApiList =[
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQZone',
            'playVoice',
            'pauseVoice',
            'stopVoice',
            'onVoicePlayEnd',
            'uploadVoice',
            'downloadVoice',
            'chooseImage',
            'previewImage',
            'uploadImage',
            'downloadImage',
            'translateVoice',
            'getNetworkType',
            'openLocation',
            'getLocation',
            'hideOptionMenu',
            'showOptionMenu',
            'hideMenuItems',
            'showMenuItems',
            'hideAllNonBaseMenuItem',
            'showAllNonBaseMenuItem',
            'closeWindow',
            'scanQRCode',
            'chooseWXPay',
            'openProductSpecificView',
            'addCard',
            'chooseCard',
            'openCard'
        ];
        data = $.parseJSON(data);
        var wxConfig = {};
        $.extend(wxConfig,data.data,{
            debug:false,
            jsApiList:jsApiList
        });
        wx.config(wxConfig);
        // share to moment
        window.wxWechatShare = function (title,desc,url,image,cb) {
            wx.onMenuShareTimeline({
                title: title, // 分享标题
                link: url, // 分享链接
                imgUrl: image, // 分享图标
                success: function () {
                    if(cb && $.isFunction(cb)){
                        cb();
                    }
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                    //alert('cancel');
                }
            });
        };
        window.wxFriendShare = function (title, desc, url, image, cb) {
            wx.onMenuShareAppMessage({
                title: title, // 分享标题
                link: url, // 分享链接
                desc:desc,
                imgUrl: image, // 分享图标
                success: function () {
                    if(cb && $.isFunction(cb)){
                        cb();
                    }
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                    //alert('cancel');
                }
            });
        };
        // share to qq zone
        window.wxQZoneShare = function (title,desc,url,image,cb) {
            wx.onMenuShareQZone({
                title: title, // 分享标题
                desc: desc, // 分享描述
                link: url, // 分享链接
                imgUrl: image, // 分享图标
                success: function () {
                    if(cb && $.isFunction(cb)){
                        cb();
                    }
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                    //alert('cancel');
                }
            });
        };
        // share to qq zone
        window.wxQqFriendShare = function (title,desc,url,image,cb) {
            wx.onMenuShareQQ({
                title: title, // 分享标题
                desc: desc, // 分享描述
                link: url, // 分享链接
                imgUrl: image, // 分享图标
                success: function () {
                    if(cb && $.isFunction(cb)){
                        cb();
                    }
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                    //alert('cancel');
                }
            });
        };
        // share to qq zone
        window.wxQqBlogShare = function (title,desc,url,image,cb) {
            wx.onMenuShareWeibo({
                title: title, // 分享标题
                desc: desc, // 分享描述
                link: url, // 分享链接
                imgUrl: image, // 分享图标
                success: function () {
                    if(cb && $.isFunction(cb)){
                        cb();
                    }
                },
                cancel: function () {
                    // 用户取消分享后执行的回调函数
                    //alert('cancel');
                }
            });
        };

        function initShareWx(){
            var productId = getQueryString('product_id');
            if(isValid(productId)){
                var shareCaseURL = baseUrl +  'newwap/APIs/product/getProductShareCase.php?productId='+productId;
                $.getJSON(shareCaseURL, function (data) {
                    if (data.data){
                        var sharecase = data.data;
                        var shareUrl = baseUrl + 'mobile/html/hotshop.html?product_id='+productId+'&shareCode='+sharecase.shareCode;
                        wxWechatShare(sharecase.title,sharecase.memo,shareUrl,sharecase.image,function(){
                            var postShareURL = baseUrl + 'newwap/APIs/product/publishShareReport.php';
                            checkLoginAndDeal(function(customerId){
                                $.getJSON(postShareURL,{customerId:customerId,productId:productId}, function (data) {
                                });
                            });
                        });
                        wxFriendShare(sharecase.title,sharecase.memo,shareUrl,sharecase.image,function(){
                            var postShareURL = baseUrl + 'newwap/APIs/product/publishShareReport.php';
                            checkLoginAndDeal(function(customerId){
                                $.getJSON(postShareURL,{customerId:customerId,productId:productId}, function (data) {
                                });
                            });
                        });
                        wxQZoneShare(sharecase.title,sharecase.memo,shareUrl,sharecase.image,function(){
                            var postShareURL = baseUrl + 'newwap/APIs/product/publishShareReport.php';
                            checkLoginAndDeal(function(customerId){
                                $.getJSON(postShareURL,{customerId:customerId,productId:productId}, function (data) {
                                });
                            });
                        });
                        wxQqFriendShare(sharecase.title,sharecase.memo,shareUrl,sharecase.image,function(){
                            var postShareURL = baseUrl + 'newwap/APIs/product/publishShareReport.php';
                            checkLoginAndDeal(function(customerId){
                                $.getJSON(postShareURL,{customerId:customerId,productId:productId}, function (data) {
                                });
                            });
                        });
                        wxQqBlogShare(sharecase.title,sharecase.memo,shareUrl,sharecase.image,function(){
                            var postShareURL = baseUrl + 'newwap/APIs/product/publishShareReport.php';
                            checkLoginAndDeal(function(customerId){
                                $.getJSON(postShareURL,{customerId:customerId,productId:productId}, function (data) {
                                });
                            });
                        });
                    }
                });
            }
        }


        function initWxPay(){
            $('li.wx-pay').data('is-in-wx',1);
        }
        function initWxLogin(){
            $('#loginWechat').data('is-in-wx',1);
        }

        wx.ready(function(){
            is_in_wechat = 1;
            wx.checkJsApi({
                jsApiList: jsApiList, // 需要检测的JS接口列表，所有JS接口列表见附录2,
                success: function(res) {
                    // 以键值对的形式返回，可用的api值true，不可用为false
                    // 如：{"checkResult":{"chooseImage":true},"errMsg":"checkJsApi:ok"}
                    //console.log(res);
                    if(res && res.checkResult){
                        // if(res.checkResult.onMenuShareTimeline){}
                    }
                }
            });
            initShareWx();
            initWxPay();
            initWxLogin();
        });
        //initShareWx();
        wx.error(function(res){
            console.log(res);
        });
        window.wxLogin = function () {
            var redirectURL = encodeURIComponent(baseUrl+'mobile/html/wechat_redirect.php');
            var loginURL = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='+appId
                +'&redirect_uri='+redirectURL+'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
            window.location = loginURL;
        };
        // request a wechat payment
        window.wxPay = function () {

        };
    });

});