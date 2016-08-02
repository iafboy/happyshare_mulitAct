/**
 * Created by 欣 on 2016/1/11.
 */

$(function(){


    var findPwd = baseUrl + 'newwap/APIs/customer/findPwd.php';
    $('.tlCont .btn_01').on(base_event,function(){
        var mobile = $('.phone').val();
        if(!isValid(mobile)){
            return errTips('手机号码未填写！');
        }
        $.post(findPwd,{
            mobile : mobile
        },function(data){
            data = $.parseJSON(data);
            if(data.resultCode==0){
                errTips('密码已短信发送至绑定手机！');
                setTimeout(function(){
                    window.location = 'login.html';
                },2000);
            }else{
                return errTips(data.resultMsg);
            }

        });
    });

});