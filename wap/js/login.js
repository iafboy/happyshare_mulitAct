/**
 * Created by 欣 on 2016/1/11.
 */

$(function(){
    var redirect_url = getQueryString('redirect_uri');
    if(!isValid(redirect_url)){
        redirect_url = 'myinfo.html';
    }

    var hasShareCode = false;
    var shareCode;
    function getShareCode(){
        shareCode = getQueryString('shareCode');
        if(!isValid((shareCode))){
            var getShareCodeURL = baseUrl+'newwap/APIs/customer/getShareCode.php';
            $.getJSON(getShareCodeURL, function (data) {
                if(data && data.data && data.data.shareCode && isValid(data.data.shareCode)){
                    shareCode = data.data.shareCode;
                    hasShareCode = true;
                    //$('.shareCode').val(shareCode);
                    $('#loginWechat').click(function(){
                        if($(this).data('is-in-wx')==1){
                            wxLogin();
                        }else{
                            return errTips('请在微信中打开网页后在重试！');
                        }
                    });
                }else{
                    //return errTips('您的来源链接没有推荐码！');
                }
            });
        }else{
            hasShareCode = true;
            $('#loginWechat').click(function(){
                if($(this).data('is-in-wx')==1){
                    wxLogin();
                }else{
                    return errTips('请在微信中打开网页后在重试！');
                }
            });
        }
    }
    getShareCode();


    var loginUrl = baseUrl + 'newwap/APIs/login.php';

    var weiboURL = '../WeiboLogin/index.php';
    $.get(weiboURL,{}, function (data) {
        $('#loginWeiBo').attr('href',data);
    });
    $('.ui-login .btn_02').on(base_event,function(){
        var phone = $('.phone').val();
        var pwd = $('.mima').val();
        var forget = 0;
        if($('.rmme.on').length==1){
            forget = 1;
        }
        $.post(loginUrl,{
            mobile : phone,
            forget : forget,
            password : pwd
        },function(data){
            try{
                data = $.parseJSON(data);
                if(data.resultCode == 0){
                    window.location = redirect_url;
                }else{
                    errTips(data.resultMsg);
                }
            }catch(e){
            }
        });
    });
    var childWindow;
    function toQzoneLogin() {
        childWindow = window.open("../QQLogin/example/oauth/index.php","TencentLogin","width=450,height=320,menubar=0,scrollbars=1, resizable=1,status=1,titlebar=0,toolbar=0,location=1");
    }

    function closeChildWindow() {
        childWindow.close();
    }
    $('#loginQQ').click(function(){
        toQzoneLogin();
    });
    $('#loginWechat').click(function(){
        if(is_in_wechat == 1){
            wxLogin();
        }else{
            $('#loginWechat').remove();
            return errTips('请在微信中打开网页后在重试！');
        }
    });

});