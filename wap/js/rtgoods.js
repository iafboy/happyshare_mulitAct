/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/10/8 21:46
 */

$(function(){
    function getParameterByName(name)
    {
        name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
        var regexS = "[\\?&]" + name + "=([^&#]*)";
        var regex = new RegExp(regexS);
        var results = regex.exec(window.location.search);
        if(results == null)
            return "";
        else
            return decodeURIComponent(results[1].replace(/\+/g, " "));
    }
    var orderNo = getParameterByName('order_no');
    var productId = getParameterByName('product_id');
    var supplierId = getParameterByName('supplier_id');
    var quantity = getParameterByName('quantity');
    var image = getParameterByName('image');
    var productName = getParameterByName('product_name');
    var baoyou = getParameterByName('baoyou');
    var price = getParameterByName('price');
    var jifen = getParameterByName('jifen');
    $('.thMenu li').on('tap',function(){
        var _this = $(this);
        var _idx = _this.index();
        _this.addClass('on').siblings().removeClass('on');
        $('.thCont .thItem').eq(_idx).show().siblings().hide();
    });
    function initPage(){
        var html = '<div class="spic"> \
                        <img src="'+image+'"> \
                        </div> \
                        <div class="info"> \
                        <h2>'+productName+'<br>'+baoyou+'</h2> \
    <p class="jiage"> \
    <span>￥'+price+'</span> \
    <em class="jf">'+jifen+'积分</em> \
    </p> \
                    </div> \
       <div class="numbox"> \
        <a class="sub" href="javascript:void(0)">-</a><div class="rtnum"><input class="num-counter" type="number" value="' + quantity + '"> \
        </div> \
        <a class="add" href="javascript:void(0)">+</a> \
        </div> \
        </div>';
        $('.goodsinfo .bd').empty().append(html);

        var url = baseUrl +  'newwap/APIs/common/getServiceNumber.php';
        $.getJSON(url,{supplierId:supplierId}, function (data) {
            $('.tel2').empty().append(data.data.admin);
            $('.tel1').empty().append(data.data.supplier);
        });
    }
    initPage();

    $('body').on(base_event,'.smBtn', function () {
        var params = $('#submitFm').formJSON();
        var mode =$('.thMenu li.on').data('mode');

        var reason = $('.rslist_'+mode +' li.on').text();
        if(reason == '其他'){
            reason = params['ireason_'+mode];
        }
        params.reason = reason;
        params.mode = mode;
        if(!isValid(params.reason)){
            return errTips('请填写退货理由');
        }
        if(!isValid(params.phone)){
            return errTips('请填写联系方式');
        }
        var url = baseUrl + 'newwap/APIs/order/applyRefund.php';
        params.orderNo = orderNo;
        params.productId = productId;
        params.num=parseInt($('.goodsinfo .bd .numbox .rtnum').find('.num-counter').val());
        console.debug(params);
        $.post(url,params, function (data) {
            data = $.parseJSON(data);

            if(data.resultCode == 0){
                window.location = 'order_detail.html?orderNo='+orderNo+'&supplierId='+supplierId;
            }else{
                return errTips(data.resultMsg);
            }
        });
    });
    $('body').on(base_event,'.rslist li', function () {
        $(this).addClass('on').siblings().removeClass('on');
    });

    $('body').on(base_event, '.numbox .add', function () {
        var _shopid = $(this).parents('li').attr('data-shopid');
        var _num = parseInt($(this).prev('.rtnum').find('.num-counter').val());
        if(_num<quantity) {
            _num++;
        }
        $(this).prev('.rtnum').find('.num-counter').val(_num);

    });
    $('body').on(base_event, '.numbox .sub', function () {
        var _shopid = $(this).parents('li').attr('data-shopid');
        var _num = parseInt($(this).next('.rtnum').find('.num-counter').val());
        if (_num > 0) {
            _num--;
        }
        $(this).next('.rtnum').find('.num-counter').val(_num);


    });

});

function getFullPath(obj) {    //得到图片的完整路径
    var url;
    if (obj) {
        url = window.URL.createObjectURL(obj.files[0]);
        $('.uploadpic').attr('src',url);
        var oFReader = new FileReader();
        oFReader.readAsDataURL(obj.files[0]);
        oFReader.onload = function (oFREvent) {
            $('.imgSubmit').val(oFREvent.target.result);
        };
    }
}