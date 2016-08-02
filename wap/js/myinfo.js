/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/10/3 11:33
 */

$(function(){


    var getCodeUrl = baseUrl + 'newwap/APIs/getModifyPhoneVerifyCode.php';
    var modifyPhoneURL = baseUrl + 'newwap/APIs/customer/modifyMobile.php';
    var verifyCodeUrl = baseUrl + 'newwap/APIs/verifyCode.php';

    // 获取基本信息
    getMyBaseInfo(function (info) {
        var html = '';
        if(info){
            html =
                '<div class="myface"> \
                    <img src="'+info.img+'"/> \
                        </div> \
                        <div class="bd"> \
                        <h3 class="name">'+info.userName+'</h3> \
                    <div class="info"> \
                    <p class="jifen">积分余额：<i>'+info.credit+'积分</i></p> \
                    <p class="tgcode">分享推广码：'+info.shareCode+'</p> \
                    <p>分享推广链接：</p> \
                    <p>'+info.shareUrl+'</p> \
                </div> \
                    </div> \
                    <div class="ewm"> \
                        <img src="'+info.qrCode+'" /> \
                        <p>注册链接二维码</p> \
                        </div>';
        }
        $('.my-info').empty().append(html);
    });

    // 确认删除地址
    $('body').on(base_event,'.ui-delAdress .btn-del-address',function(){
        var addressId = $('.tipsBg,.tipsCont,.tipsCont .ui-delAdress').data('address-id');
        var delAddressUrl = baseUrl + 'newwap/APIs/customer/delMyAddress.php?addressId='+addressId;
        $.get(delAddressUrl,{}, function (data) {
            data = $.parseJSON(data);
            if(data.resultCode==0){
                var _this = $('.dzlist li.on');
                if(_this.hasClass('on')){
                    if(_this.next().length > 0){
                        _this.siblings().removeClass('on');
                        _this.next().addClass('on');
                    }else{
                        _this.siblings().removeClass('on');
                        _this.prev().addClass('on');
                    }
                }
                _this.remove();
                $('.tipsBg,.tipsCont,.tipsCont .ui-delAdress').data('address-id','');
                $('.tipsBg,.tipsCont,.tipsCont .ui-delAdress').hide();
            }else{
                errTips(data.resultMsg);
            }
        });
    });
    //取消删除地址
    $('body').on(base_event,'.ui-delAdress .btn-cancel-del-address',function(){
        $('.tipsBg,.tipsCont,.tipsCont .ui-delAdress').data('address-id','');
        $('.tipsBg,.tipsCont,.tipsCont .ui-delAdress').hide();
    });

    // 开始删除地址
    function delAddress(addressId){
        $('.tipsBg,.tipsCont,.tipsCont .ui-delAdress').data('address-id',addressId);
        $('.tipsBg,.tipsCont,.tipsCont .ui-delAdress').show();
    }
    window.delAddress = delAddress;
    //选中当前地址
    $('body').on(base_event,'.dzlist li',function(){
        $(this).addClass('on').siblings().removeClass('on');
    });

    $('body').on(base_event,'.chkbox',function(){
        $(this).toggleClass('on');
    });

    var InterValObj;
    var timeMillis =120;
    //修改捆绑的手机号
    $('.ui-edit-phone').on(base_event,function(){
        $('.tipsBg,.tipsCont,.tipsCont .ui-oldPhone').show();
    });

    $('body').on(base_event,'.tipsCont .ui-next-phone',function(){
        clearInterval(InterValObj);

        detectLoginAndDeal(function (customerId) {
            var params = $('.tipsBg,.tipsCont,.tipsCont .ui-oldPhone').targetJSON();
            if(!isValid(params.mobile)){
                return errTips('手机号码不能为空');
            }
            if(!isValid(params.pwd)){
                return errTips('密码不能为空');
            }
            if(!isValid(params.verifyCode)){
                return errTips('验证码不能为空');
            }
            params.customerId = customerId;
            $.post(modifyPhoneURL,params, function (data) {
                data = $.parseJSON(data);
                if(data.resultCode == 0){
                    $('.tipsBg,.tipsCont,.tipsCont .bd').hide();
                    errTips('绑定成功');
                }else{
                    return errTips(data.resultMsg);
                }
            });
        });

        //isClick = true;
        //$('.tipsCont .ui-oldPhone').hide();
        //$('.tipsCont .ui-newPhone').show();
    });
    //修改密码
    $('.ui-edit-pw').on(base_event,function(){
        $('.tipsBg,.tipsCont,.tipsCont .ui-password').show();
    });


    //修改密码
    $('.ui-password .modify-pwd').on(base_event,function(){
        var pwd1 = $(this).parents('.bd').find('.mima.pwd1').val();
        var pwd2 = $(this).parents('.bd').find('.mima.pwd2').val();
        var pwd3 = $(this).parents('.bd').find('.mima.pwd3').val();
        if(isValid(pwd1) && isValid(pwd2) && isValid(pwd3)){
            if(pwd2.trim() == pwd3.trim()){
                if(!verifyPwd(pwd2)){
                    return errTips('密码最小长度为6，需同时含字母和数字');
                }
                var modifyPwdUrl = baseUrl+'newwap/APIs/customer/changePwd.php';
                getCustomerId(function(customerId){

                    $.post(modifyPwdUrl,{customerId:customerId,password:pwd1,newPassword:pwd2},function(data){
                        data = $.parseJSON(data);
                        if(data.resultCode==0){
                            $('.tipsBg,.tipsCont,.tipsCont .bd').hide();
                            return errTips('修改成功!');
                        }else{
                            return errTips(data.data.errMsg);
                        }
                    });
                });
            }else{
                return errTips('新密码不一致！');
            }
        }else{
            return errTips('密码为空！');
        }
    });
    //获取旧的绑定号码
    var isClick = true;
    var codeTimer = null;
    var codeNum = 120;
    $('body').on(base_event,'.tipsCont .getcode',function(){
        var _phone = $.trim($(this).parents('.bd').find('.newPhone').val());
        var _password = $.trim($(this).parents('.bd').find('.pwd').val());
        if(!$(this).hasClass('on')){
            if(_phone && _phone != ''){
                $.post(getCodeUrl,{
                    mobile : _phone
                },function(data){
                    var data = json = eval( "(" + data + ")" );
                    if(data.resultCode == 0){
                        errTips('验证码发送成功');
                        $('.tipsCont .getcode').addClass('on');
                        codeTimer = setInterval(function(){
                            if(codeNum > -1){
                                $('.ui-oldPhone .getcode').text(codeNum+'秒');
                                codeNum--;
                            }else{
                                clearInterval(codeTimer);
                                $('.getcode').text('获取验证码');
                                $('.ui-oldPhone .getcode').removeClass('on');
                                codeNum = 120;
                            }
                        },1000);
                    }
                });
                /*if(isClick){
                    var _this = $(this);
                    var count = 60;
                    InterValObj = setInterval(setRemainTime, 1000);
                    function setRemainTime() {
                        if (count == 0) {
                            isClick = true;
                            clearInterval(InterValObj);//停止计时器
                            _this.text("获取验证码");
                        }
                        else {
                            count--;
                            _this.text("" + count + "秒");
                            isClick = false;
                        }
                    }
                }*/
            }else{
                errTips('手机号不能为空');
                return;
            }
        }
    });

    //关闭层
    $('body').on(base_event,'.tipsCont .close',function(){
        $('.tipsBg,.tipsCont,.tipsCont .bd').hide();
    });

    var provUrl = baseUrl + 'newwap/APIs/common/getProvince.php';
    var cityUrl = baseUrl + 'newwap/APIs/common/getCity.php';
    var distUrl = baseUrl + 'newwap/APIs/common/getDistrict.php';
    $.getJSON(provUrl,function(data){
        var html = '<option value="-1" selected>-省/直辖市-</option>';
        for(var i = 0;i < data.data.length;i++){
            html += '<option value="'+data.data[i].provinceCode+'">'+data.data[i].name+'</option>';
        }
        $('.prov-sel').append(html);
    });

    $('.prov-sel').on('change',function(){
        var code = $('.prov-sel').val();
        $('.city-sel , .dist-sel').empty();
        $.getJSON(cityUrl+'?provinceCode='+code,function(data){
            var html = '<option value="-1" selected>-城区-</option>';
            for(var i = 0;i < data.data.length;i++){
                html += '<option value="'+data.data[i].cityCode+'">'+data.data[i].name+'</option>';
            }
            $('.city-sel').append(html);
        });
    });
    $('.city-sel').on('change',function(){
        var code = $('.city-sel').val();
        $('.dist-sel').empty();
        $.getJSON(distUrl+'?cityCode='+code,function(data){
            var html = '<option value="-1" selected>-地区-</option>';
            for(var i = 0;i < data.data.length;i++){
                html += '<option value="'+data.data[i].districtCode+'">'+data.data[i].name+'</option>';
            }
            $('.dist-sel').append(html);
        });
    });

    function addressList(){
        getCustomerId(function (customerId) {

            var addressListUrl = baseUrl + 'newwap/APIs/customer/queryMyAddress.php?customerId='+customerId;
            $.get(addressListUrl,{}, function (data) {
                data = $.parseJSON(data);
                if(data.data && $.isArray(data.data) && data.data.length>0){
                    var html = '';
                    for(var i = 0; i < data.data.length;i++){
                        var address = data.data[i];
                        var liClz = '';
                        if(address.isDefalut==1){
                            liClz = 'on';
                        }
                        html = html +
                            '<li class="'+liClz+'" data-address-id="'+address.addressId+'"> \
                            <div class="title"> \
                            <h4>地址'+address.seq+'</h4> \
                            <a href="javascript:void(0)" class="del" onclick="delAddress(\''+address.addressId+'\')">删除</a> \
                            </div> \
                            <div class="arinfo"> \
                            <p>'+address.provinceName+' '+address.city+' '+address.districtName+'</p> \
                            <p>'+address.address+'</p> \
                            <p>'+address.name+'／'+address.mobile+'</p> \
                        </div> \
                        </li>';
                    }
                    $('ul.dzlist').empty().append(html);
                }
            });
        });
    }
    addressList();
    $('.newDz,.modDz').hide();
    $('body').on(base_event,'.newAr',function(){
        $('.newDz').toggle();
        $(this).hide();
    });

    function onCheckAddressParams(addressDiv,url){
        var $form = $($(addressDiv).find('form')[0]);
        var params = $form.formJSON();
        var valid_arr = [
            {field:'province',required:true,errMsg:'未选择省份！'},
            {field:'city',required:true,errMsg:'未选择地市！'},
            {field:'district',required:true,errMsg:'未选择地区！'},
            {field:'address',required:true,errMsg:'详细地址未填写！'},
            {field:'name',required:true,errMsg:'收件人未填写！'},
            {field:'mobile',required:true,errMsg:'收件人联系方式未填写！'}
        ];
        var tfOrMsg = validFormParams(params,valid_arr);
        if(tfOrMsg!==true){
            return errTips(tfOrMsg);
        }
        if(params.provId=='-1'){
            return errTips('未选择省份！');
        }
        if(params.cityId=='-1'){
            return errTips('未选择地市！');
        }
        if(params.distId=='-1'){
            return errTips('未选择地区！');
        }
        if($(addressDiv).find('.chkbox.on').length==1){
            params.isDefault = 1;
        }
        checkLoginAndDeal(function (customerId) {
            params.customerId = customerId;
            $.get(url,params,function(data){
                data = $.parseJSON(data);
                if(data.resultCode == 0){
                    addressList();
                    $(addressDiv).parents('.dz-wiget').formClear();
                    $(addressDiv).parents('.dz-wiget').find('select.prov-sel').empty().append('<option value="-1" selected>-省/直辖市-</option>');
                    $(addressDiv).parents('.dz-wiget').find('select.city-sel').empty().append('<option value="-1" selected>-城区-</option>');
                    $(addressDiv).parents('.dz-wiget').find('select.dist-sel').empty().append('<option value="-1" selected>-地区-</option>');
                    $(addressDiv).parents('.dz-wiget').hide();
                    $('.newAr').show();
                }else{
                    return errTips(data.resultMsg);
                }
            });
        });
    }

    // save address || modify address
    $('body').on(base_event, '.newDz a.savebtn,.modDz a.savebtn',function () {
        if($(this).hasClass('btn-add-address')){
            var addAddressUrl = baseUrl + 'newwap/APIs/customer/addMyAddress.php';
            onCheckAddressParams($(this).parents('.bd'),addAddressUrl);
        }else if($(this).hasClass('btn-edit-address')){
            var modAddressUrl = baseUrl + 'newwap/APIs/customer/modifyMyAddress.php';
            onCheckAddressParams($(this).parents('.bd'),modAddressUrl);
        }
    });
    $('body').on(base_event, 'a.nosavebtn',function () {
        $('.newDz,.modDz').hide();
        $('.newAr').show();
    });

    // set default address
    $('body').on(base_event,'a.defaultAr', function () {
        var selectId = $('ul.dzlist li.on').data('address-id');
        if(!isValid(selectId)){
            return errTips('未选定地址！');
        }
        var setDefaultArURL = baseUrl + 'newwap/APIs/customer/setDefaultAddress.php';
        checkLoginAndDeal(function (customerId) {
            $.get(setDefaultArURL,{customerId:customerId,addressId:selectId}, function (data) {
                data = $.parseJSON(data);
                if(data.resultCode == 0){
                    addressList();
                    $('.bd').parents('.dz-wiget form').formClear();
                    $('.bd').parents('.dz-wiget').hide();
                    return errTips('操作成功！');
                }else{
                    return errTips('操作失败！');
                }
            });
        });
    });

});
