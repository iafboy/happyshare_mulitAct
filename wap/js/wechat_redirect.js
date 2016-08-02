/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/9/20 10:54
 */

$(function(){
    var appId = 'wx4ba8a02a5ef1d924';
    var secretId  = '4f4cc4d554de08b0d7628da3164ee92e';
    var code = getQueryString('code');
    var state = getQueryString('STATE');
    if(!isValid(code)){

    }else{
        var url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='+appId+'&secret='+secretId+'&code='+code+'&grant_type=authorization_code';


        /*$.getJSON(url+"&callback=?",
            function(data){

            });*/

    }
    var data = $.parseJSON(json);
    datas = $.parseJSON(datas);
    //console.log(datas);
    //console.log(data);
    if(data && data.openid){
        var openid = data.openid;
        var accessToken = data.access_token;
        var refreshToken = data.refresh_token;
        var unionid = data.unionid;
        var nickName = data.nickname || '微信用户';
        var wxLoginURL = baseUrl + 'newwap/APIs/thirdAuthorization.php';
        $.post(wxLoginURL,{thirdType:1,thirdParty:openid,nickName:nickName}, function (data) {
            data = $.parseJSON(data);
            if(data.resultCode == 0){
                $('#success-area').show().siblings().hide();
                setTimeout(function(){
                    window.location = 'myinfo.html';
                },5000);
            }else{
                $('#fail-area').show().siblings().hide();
            }
        });
    }else{
        $('#fail-area').show().siblings().hide();
        $('#fail-area').show().siblings().hide();
    }
    if(success == 1){
        $('#success-area').show().siblings().hide();
    }else if(success == 2){}else{
        $('#fail-area').show().siblings().hide();
    }



});