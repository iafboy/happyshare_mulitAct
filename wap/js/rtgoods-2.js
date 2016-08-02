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
    //var quantity = getParameterByName('quantity');
    var quantity = getParameterByName('return_num');
    var image = getParameterByName('image');
    var productName = getParameterByName('product_name');
    var baoyou = getParameterByName('baoyou');
    var price = getParameterByName('price');
    var jifen = getParameterByName('jifen');
    var order_product_id = getParameterByName('order_product_id');
    $('.thMenu li').on('tap',function(){
        var _this = $(this);
        var _idx = _this.index();
        _this.addClass('on').siblings().removeClass('on');
        $('.thCont .thItem').eq(_idx).show().siblings().hide();
    });
    function initPage(){


        var url = baseUrl +  'newwap/APIs/order/getReturnGoodsInfo.php';
        $.getJSON(url,{supplierId:supplierId,order_product_id:order_product_id}, function (data) {
            var html1 = '<div class="spic"> \
                        <img src="'+image+'"> \
                        </div> \
                        <div class="info"> \
                        <h2>'+productName+'<br>'+baoyou+'</h2> \
                    <p class="jiage"> \
                        <span>￥'+price+'</span> \
                        <em class="jf">'+jifen+'积分</em>\
                    </p> \
                    </div> \
                    <div class="num">x'+data.data.return_num+'</div>';
            $('.goodsinfo .bd').empty().append(html1);
            var html =  '<p>收货人：'+data.data.salesreturn+'</p> \
                <p>联系电话：'+data.data.telephone+'</p> \
                <p>收货地址：'+data.data.address+'</p> \
                <p>请填退货号：'+data.data.refound_history_no+'</p>';
            $('.adrCont').empty().append(html);
            $('.rt-money').empty().append(parseFormatNum(data.data.shippment_cost,2));

            var banks = data.data.bankList;
            var banks_html = '';
            for(var i = 0;i < banks.length;i++){
                banks_html = banks_html + '<option value="'+banks[i].bank_id+'">'+banks[i].bank_name+'</option>';
            }
            $('.bank-list').empty().append(banks_html);
        });
    }
    initPage();

    $('body').on(base_event,'.smBtn', function () {
        var params = $('#submitFm').formJSON();
        if(!isValid(params.shippment_company)){
            return errTips('请填写快递公司');
        }
        if(!isValid(params.shippment_no)){
            return errTips('请填写快递单号');
        }
        if(!isValid(params.cardHolder)){
            return errTips('请填写持卡人姓名');
        }
        if(!verifyBankCard(params.cardId)){
            return errTips('银行卡号无效');
        }
        var url = baseUrl + 'newwap/APIs/order/submitRefund.php';
        params.order_product_id = order_product_id;
        $.getJSON(url,params, function (data) {
            if(data.resultCode == 0){
                window.location = 'order_detail.html?orderNo='+orderNo+'&supplierId='+supplierId;
            }else{
                return errTips(data.resultMsg);
            }
        });
    });

});
function verifyBankCard(cardNo){
    return isValid(cardNo) && /^\d{16}|\d{19}$/.test(cardNo);
}