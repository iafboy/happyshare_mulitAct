/**
 *
 */
$(function(){
    var hasShareCode = false;
    function getShareCode(){
        var shareCode = getQueryString('shareCode');
        if(!isValid((shareCode))){
            var getShareCodeURL = baseUrl+'newwap/APIs/customer/getShareCode.php';
            $.getJSON(getShareCodeURL, function (data) {
                if(data && data.data && data.data.shareCode && isValid(data.data.shareCode)){
                    shareCode = data.data.shareCode;
                    hasShareCode = true;
                    $('.shareCode').val(shareCode);
                }
            });
        }else{
            hasShareCode = true;
            $('.shareCode').val(shareCode);
        }
    }
    getShareCode();

    /*var queryAvatarUrl = baseUrl + 'newwap/APIs/common/getCustomerAvatar.php';
    $.get(queryAvatarUrl,{}, function (data) {
        data = $.parseJSON(data);
        var html = '';
        if(data.data && $.isArray(data.data) && data.data.length>0){
            for(var i = 0;i <data.data.length;i++){
                var avatar = data.data[i];
                var clz = '';
                if(i==0){
                    clz = 'on';
                }
                html = html +
                    '<div class="myface" data-avatar-id="'+avatar.avatar_id+'"> \
                    <img class="'+clz+'" src="'+avatar.img+'"/> \
                    </div>';
            }
        }
        $('.img-selector').empty().append(html);
    });*/

    /*$('body').on(base_event,'.myface img', function () {
        if($(this).hasClass('on')){

        }else{
            $('.myface img').removeClass('on')
            $(this).addClass('on')
        }
    });*/

    //注册
    var regURL = baseUrl + 'newwap/APIs/register.php';
    if(fakeRegister){
        regURL = baseUrl + 'newwap/APIs/fakeRegister.php';
    }
    var getCode = baseUrl + 'newwap/APIs/getVerifyCode.php';
    var codeTimer = null;
    var codeNum = 120;
    var isReg = true;
    $('body').on(base_event,'.ui-register .getcode',function(){
        if(!$(this).hasClass('on')){
            var _phone =$(this).parents('.bd').find('.phone').val();
            //$('.ui-register .phone').val();
            if(_phone == ''){
                errTips('手机号不能为空');
                isReg = false;
                return false;
            }else{
                isReg = true;
            }
            if(isReg){
                $.post(getCode,{
                    mobile : _phone
                },function(data){
                    var data = json = eval( "(" + data + ")" );
                    if(data.resultCode == 0){
                        errTips('验证码发送成功');
                        $('.ui-register .getcode').addClass('on');
                        codeTimer = setInterval(function(){
                            if(codeNum > -1){
                                $('.getcode').text(codeNum+'秒');
                                codeNum--;
                            }else{
                                clearInterval(codeTimer);
                                $('.getcode').text('获取验证码');
                                $('.ui-register .getcode').removeClass('on');
                                codeNum = 120;
                            }
                        },1000);
                    }
                });
            }
        }
    });
    $('body').on(base_event,'.ui-register .btn_03',function(){
        var _username = $('.ui-register .user').val();
        var _psd1 = $('.ui-register .mima').eq(0).val();
        var _psd2 = $('.ui-register .mima').eq(1).val();
        var _phone = $('.ui-register .phone').val();
        var _verifyCode = $('.ui-register .pw').val();
        var _referenceCode = $('.ui-register .shareCode').val();
        var _rmme = $('.ui-rmme').hasClass('on');
        //var avatarId = $('.img-selector .myface img.on').parent().data('avatar-id');
        //密码不一致
        if(_psd1 != _psd2){
            $('.pwerror').css({
                'left' : $('.mima').val().length *5 + 12
            }).fadeIn(function(){
                setTimeout(function(){
                    $('.pwerror').hide().css({
                        'left' : 0
                    });
                },1000);
            });
            isReg = false;
            return false;
        }else{
            isReg = true;
        }
        //if(_username == ''){
        //    errTips('用户名不能为空');
        //    isReg = false;
        //    return false;
        //}else{
        //    isReg = true;
        //}
        if(!isValid(_verifyCode)){
            return errTips('未输入验证码！');
        }
        if(!(verifyPwd(_psd1) && verifyPwd(_psd2))){
            errTips('密码最小长度为6，需同时含字母和数字');
            isReg = false;
            return false;
        }else{
            isReg = true;
        }
        if(_phone == ''){
            errTips('手机号不能为空');
            isReg = false;
            return false;
        }else{
            isReg = true;
        }

        if(!_rmme){
            errTips('请同意服务条款');
            isReg = false;
            return false;
        }else{
            isReg = true;
        }
        var params = {};
        if(isValid(_referenceCode)){
            params.shareCode = _referenceCode;
        }else{
            return errTips('分享码未填写！');
        }
        $.extend(params,{
            userName : _username,
            password : _psd1,
            mobile : _phone,
            verifyCode : _verifyCode
        });
        if(isReg){
            $.post(regURL,params,function(data){
                data = $.parseJSON(data);
                if(data.resultCode == 0){
                    errTips('恭喜你，注册成功');
                    setTimeout(function(){
                         window.location.href = 'myinfo.html';
                    },2000);
                }else if(data.resultCode == 2){
                    errTips('手机号对应用户已存在');
                }else{
                    return errTips(data.resultMsg);
                }
            });
        }

    });

});