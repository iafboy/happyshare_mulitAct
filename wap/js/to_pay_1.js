/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/9/30 13:29
 */
function refreshVerifyCode(){
    $('#verifyCode').attr('src',baseUrl+'newwap/APIs/customer/getPlaceOrderVerifyCode.php?time='+new Date());
}

$(function(){
    checkLoginAndDeal();

    var provUrl = baseUrl + 'newwap/APIs/common/getProvince.php';
    var cityUrl = baseUrl + 'newwap/APIs/common/getCity.php';
    var distUrl = baseUrl + 'newwap/APIs/common/getDistrict.php';
    var productidstr = getQueryString('productIds') || '';
    var from = getQueryString('from') || 'product';
    var nums = getQueryString('nums') || '1';

    $('.selcon select').on('change',function(){
        var _this = $(this);
        var _text = _this.find('option').not(function(){ return !this.selected }).text();
        _this.prev('.text').html('<span>'+_text+'</span>');
    });
    $('body').on(base_event,'.chkbox',function(){
        $(this).toggleClass('on');
    });

    $('body').on(base_event,'.ar-cont .arinfo-list .arinfo', function () {
        var that = this;
        if($(that).hasClass('on')){
            return;
        }
        $(that).addClass('on').siblings('.arinfo').removeClass('on');
        var addressId = $(that).data('address-id');
        $.get(baseUrl + 'newwap/APIs/customer/queryMyAddressDetail.php',{addressId:addressId}, function (data) {
            data = $.parseJSON(data);
            var address = data.data[0];
            $('.modDz.editAr').data('address-id',address.addressId);
            $('.modDz.editAr').formLoad(address);
            $('.modDz.editAr .prov-sel option').removeAttr('selected');
            $('.modDz.editAr .prov-sel').val(address.provinceId);
            $('.modDz.editAr').find('.city-sel , .dist-sel').empty();
            $.getJSON(cityUrl+'?provinceCode='+address.provinceId,function(data){
                var html = '<option value="-1" selected>-城区-</option>';
                for(var i = 0;i < data.data.length;i++){
                    html = html + '<option value="'+data.data[i].cityCode+'">'+data.data[i].name+'</option>';
                }
                $('.modDz.editAr').find('.city-sel').append(html);
                $('.modDz.editAr').find('.city-sel').val(address.cityId);
                $.getJSON(distUrl+'?cityCode='+address.cityId,function(data){
                    var html = '<option value="-1" selected>-地区-</option>';
                    for(var i = 0;i < data.data.length;i++){
                        html = html +  '<option value="'+data.data[i].districtCode+'">'+data.data[i].name+'</option>';
                    }
                    $('.modDz.editAr').find('.dist-sel').append(html);
                    $('.modDz.editAr').find('.dist-sel').val(address.districtId);
                });
            });
        });

    });

    $('body').on(base_event,'.add-addr', function () {
        $('.modDz.editAr').hide();
        $('.addDz.editAr').toggle();
        $(this).addClass('on');
        $('.edit-addr').removeClass('on');
    });
    $('body').on(base_event,'.edit-addr', function () {
        $('.addDz.editAr').hide();
        $('.modDz.editAr').toggle();
        $(this).addClass('on');
        $('.add-addr').removeClass('on');
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
        getCustomerId(function (customerId) {
            params.customerId = customerId;
            $.get(url,params,function(data){
                data = $.parseJSON(data);
                if(data.resultCode == 0){
                    addressList();
                    $(addressDiv).find('form').formClear();
                    $(addressDiv).hide();
                    $('.edit-addr,.add-addr').removeClass('on');
                }else{
                    return errTips(data.resultMsg);
                }
            });
        });
    }
    $('body').on(base_event, '.addDz a.savebtn,.modDz a.savebtn',function () {
        if($(this).hasClass('btn-add-address')){
            var addAddressUrl = baseUrl + 'newwap/APIs/customer/addMyAddress.php';
            onCheckAddressParams($(this).parents('.editAr'),addAddressUrl);
        }else if($(this).hasClass('btn-edit-address')){
            var addressId  = $(this).parents('.modDz').data('address-id');
            var modAddressUrl = baseUrl + 'newwap/APIs/customer/modifyMyAddress.php?addressId='+addressId;
            onCheckAddressParams($(this).parents('.editAr'),modAddressUrl);
        }
    });

    // create order
    $('body').on(base_event,'a.to_next_step', function () {

        var orderURL = baseUrl + 'newwap/APIs/shoppingCart/placeOrder.php';
        var addressId = $($('.ar-cont .arinfo-list .arinfo.on')[0]).data('address-id');
        if(!isValid(addressId)){
            return errTips('请选择地址！');
        }
        var verifyCode = $('#verifyCodeText').val();
        var mainComment = $('#mainComment').val();
        //if(!isValid(verifyCode)){
        //    return errTips('请填写验证码!');
        //}
        checkLoginAndDeal(function (customerId) {
            var msgs = {};
            var suppliers = '';
            $('#productGroupList .cart-bd').each(function () {
                var that = this;
                var supplierId = $(that).data('supplier-id');
                var msg = $(that).find('#supplier_msg_'+supplierId).val();
                suppliers = suppliers + supplierId+ ',';
                msgs['supplier_msg_'+supplierId] = msg;
            });
            if(suppliers.length > 0){
                suppliers = suppliers.substr(0,suppliers.length-1);
            }
            var json = msgs;
            $.extend(json,{
                productIds:productidstr,
                nums:nums,
                customerId:customerId,
                addressId:addressId,
                supplierIds : suppliers,
                from:from,
                verifyCode:verifyCode,
                orderMsg:mainComment
            });
            $.get(orderURL,json, function (data) {
                var data = $.parseJSON(data);
                if(data.resultCode == 0 && data.data){
                    window.location = 'to_pay_2.html?orderNo='+data.data.orderNoStr+'&orderGroupNo='+data.data.orderGroupNo;
                }else{
                    return errTipsBig(data.resultMsg);
                }
            });
        });
    });


    var productGroupURL = baseUrl + 'newwap/APIs/shoppingCart/shoppingProduct.php';
    function productList(){
        checkLoginAndDeal(function (customerId) {
            var addressId = $($('.ar-cont .arinfo-list .arinfo.on')[0]).data('address-id');
            if(!isValid(addressId)){
                errTips('请选择地址！');
            }
            $.get(productGroupURL,{productIds:productidstr,nums:nums,customerId:customerId,addressId:addressId}, function (data) {
                data = $.parseJSON(data);
                var suppliers = data.data ? data.data.suppliers:[];
                if(suppliers && $.isArray(suppliers) && suppliers.length > 0){
                    var html = '';
                    for(var i = 0; i < suppliers.length; i++){
                        var supplier = suppliers[i];
                        var products = supplier.lists;
                        if(products && $.isArray(products) && products.length>0){
                            html = html + '<div class="cart-bd" data-supplier-id="'+supplier.ghsId+'"> \
                                <div class="shop"> \
                                <h3><span>供货商：'+supplier.ghsName+'</span></h3> \
                            </div><ul class="items">';
                            for(var j = 0;j < products.length; j++){
                                var product = products[j];
                                html = html + ' <li data-product-id="'+product.buylink+'"> \
                                <div class="spic"> \
                                <img src="'+product.src+'"> \
                                </div> \
                                <div class="info"> \
                                <h2>'+product.title+'</h2> \
                            <p class="jiage"> \
                                <span>￥'+parseFormatNum(product.money,2)+'</span> \
                                <em class="jf">'+parseInt(product.jifen)+'积分</em> \
                            </p> \
                            </div> \
                            <div class="num">x'+product.num+'</div> \
                                </li>';
                            }
                            html = html + '</ul> \
                                <div class="extra"> \
                                <div class="ta-head">\
                                <p>'+supplier.tips1+'</p> \
                            <!--<a href="javascript:void(0)" class="liuyan">留言<em></em></a>--> \
                            </div>\
                                <!--<div class="ta-container"><textarea id="supplier_msg_+supplier.ghsId"></textarea></div>--> \
                                </div> \
                                </div>';
                        }
                    }
                    $('#productGroupList').empty().append(html);
                }
                if(data.data.hasOfflineProduct == 1){
                    errTips('存在已下架商品，自动丢弃！');
                }
                var t_html =
                    '<div class="yunfei">运费：'+data.data.totalExpressAmount+'元</div> \
                        <div class="allMoney"> \
                        <p class="money">实付款：<i>￥'+data.data.totalAmount+'</i></p> \
                    <p class="tips">(当前积分可抵消'+data.data.jifenMoney+'元)</p> \
                        <a href="javascript:void(0)" class="ok to_next_step">确定</a> \
                        </div>';
                $('.checkout').empty().append(t_html);
            });

        });
    }

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
                            '<div class="arinfo '+liClz+'" data-address-id="'+address.addressId+'"> \
                            <p>'+address.provinceName+' '+address.city+' '+address.districtName+'</p> \
                            <p>'+address.address+'</p> \
                            <p>'+address.name+'／'+address.mobile+'</p> \
                        </div>';
                    }
                    $('.ar-cont .arinfo-list').empty().append(html);
                    if($('.ar-cont .arinfo-list .arinfo.on').length==0){
                        $($('.ar-cont .arinfo-list .arinfo')[0]).addClass('on');
                    }
                }
                productList();
                chkArinfoLength();
            });
        });
    }

    addressList();
    refreshVerifyCode();

    $.getJSON(provUrl,function(data){
        var html = '<option value="-1" selected>-省/直辖市-</option>';
        for(var i = 0;i < data.data.length;i++){
            html += '<option value="'+data.data[i].provinceCode+'">'+data.data[i].name+'</option>';
        }
        $('.prov-sel').append(html);
    });

    $('.prov-sel').on('change',function(){
        var that = this;
        var code = $(that).val();
        $(that).parents('.bd').find('.city-sel , .dist-sel').empty();
        $.getJSON(cityUrl+'?provinceCode='+code,function(data){
            var html = '<option value="-1" selected>-城区-</option>';
            for(var i = 0;i < data.data.length;i++){
                html = html + '<option value="'+data.data[i].cityCode+'">'+data.data[i].name+'</option>';
            }
            $(that).parents('.bd').find('.city-sel').append(html);
        });
    });
    $('.city-sel').on('change',function(){
        var that = this;
        var code = $(that).val();
        $(that).parents('.bd').find('.dist-sel').empty();
        $.getJSON(distUrl+'?cityCode='+code,function(data){
            var html = '<option value="-1" selected>-地区-</option>';
            for(var i = 0;i < data.data.length;i++){
                html = html +  '<option value="'+data.data[i].districtCode+'">'+data.data[i].name+'</option>';
            }
            $(that).parents('.bd').find('.dist-sel').append(html);
        });
    });



    $('body').on(base_event,'.cart-bd .extra a.liuyan', function () {
        var that = this;
        $(that).parent().siblings('.ta-container').toggle();
    });

    $('body').on('tap','.btn-nosave', function () {
        $('.modDz,.addDz').hide();
        $('.edit-addr,.add-addr').removeClass('on');
    });

    function chkArinfoLength(){
        var len = $('.arinfo-list .arinfo').length;
        if(len == 0){
            $('.edit-addr').hide();
        }else{
            $('.edit-addr').show();
        }
    }
    chkArinfoLength();
});