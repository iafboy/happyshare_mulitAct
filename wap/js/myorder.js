/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/10/2 16:31
 */
$(function(){
    getMyBaseInfo(function (info) {
        var html = '<div class="myface"> \
                <img src="'+info.img+'"/> \
                </div> \
                <div class="bd"> \
                <h3 class="name">'+info.userName+'</h3> \
                <div class="info"> \
                <span class="jifen">积分余额：<i>'+parseFormatNum(info.credit)+'积分</i></span><span class="tgcode">分享推广码：'+info.shareCode+'</span> \
            </div> \
            </div>';
        $('.my-info').empty().append(html);
    });

    $('.state span').on(base_event,function(){
        if($(this).parent().next('.tips').length){
            $(this).parent().next('.tips').toggle();
        }
    });

    $('body').on(base_event,'ul.olist li.order-item .info', function () {
        var that = $(this).parents('.order-item');
        var orderNo = $(that).data('order-no');
        var supplierId = $(that).data('supplier-id');
        window.location = 'order_detail.html?orderNo='+orderNo+'&supplierId='+supplierId;
    });

    function orderHtml(order){

        var html = '<li class="combine-order-item" data-order-no="'+order.orderNo+'">\
            <div class="title order-level">\
            <h3>订单编号：<a href="order_detail.html?orderNo='+order.orderNo+'">'+order.orderNo+'</a></h3> \
        <!--<a class="state" href="javascript:void(0)"></a>--> \
            <div class="state">'+getOrderStatusText(order.orderStatus)+'</div>\
            </div>\
            <div class="v-line"></div>';
        html = html + '<ul class="supplier-list">';

        var suppliers = order.suppliers;
        if(suppliers && $.isArray(suppliers) && suppliers.length > 0) {
            for (var i = 0; i < suppliers.length; i++) {
                var curSupplier = suppliers[i];
                html = html + '<li class="supplier-item">\
                    <div class="title">\
                    <h3>供货商：<span class="supplier-name">'+curSupplier.supplier_name+'</span></h3>\
                    </div>';
                var curProducts = curSupplier.products;
                if(curProducts && $.isArray(curProducts) && curProducts.length > 0) {
                    for (var j = 0; j < curProducts.length; j++) {
                        var curProduct = curProducts[j];
                        html = html + '<div class="bd">\
                            <div class="spic">\
                            <img src="'+curProduct.image+'">\
                            </div>\
                            <div class="info">\
                            <h2>'+curProduct.name+'</h2>\
                        <p class="jiage">\
                            <span>￥'+parseFormatNum(curProduct.price,2)+'</span>\
                            <em class="jf">获得'+parseFormatNum(curProduct.unit_score)+'积分</em>\
                            </p>\
                            </div>\
                            <div class="num">x'+parseFormatNum(curProduct.quantity)+'</div>\
                            </div>';
                    }
                }
                html += '</li>';
            }
        }

        if(order.orderStatus == 0){
            html= html+ '<div class="extra"> \
                <p>下单时间:'+order.orderDate+'</p> \
                <a href="javascript:void(0)" onclick="cancelOrder(\''+order.orderNo+'\')">取消</a> \
                <a href="to_pay_2.html?orderNo='+order.orderNo+'">支付</a> \
                </div>';
        }else if(order.orderStatus == 1){
            html= html+ '<div class="extra"> \
                <p>下单时间:'+order.orderDate+'</p> \
                </div>';
        } else if(order.orderStatus == 2){
            html= html +  '<div class="extra"> \
                <p>下单时间:'+order.orderDate+'</p> \
                <!--<a href="return-goods.html?order_no='+order.orderNo+'supplier_id=product_id=">申请退货</a>--> \
                <!--<a href="javascript:void(0)">查看物流</a>--> \
                </div>';

        } else if(order.orderStatus == 3){
            html= html+ '<div class="extra"> \
                <p>下单时间:'+order.orderDate+'</p> \
                <!--<a href="javascript:void(0)">确认收货</a>--> \
                <!--<a href="javascript:void(0)">申请退货</a>--> \
                <!--<a href="javascript:void(0)">查看物流</a>--> \
                </div>';
        } else if(order.orderStatus == 4){
            html= html+ '<div class="extra"> \
                <p>下单时间:'+order.orderDate+'</p> \
                </div>';


        } else if(order.orderStatus == 5){
            html= html+'<div class="extra"> \
                <p>下单时间:'+order.orderDate+'</p> \
                </div>';

        } else if(order.orderStatus == 6){

            html= html+ '<div class="extra"> \
                <p>下单时间:'+order.orderDate+'</p> \
                </div>';
        } else if(order.orderStatus == 7){

            html= html+'<div class="extra"> \
                <p>下单时间:'+order.orderDate+'</p> \
                </div>';
        } else if(order.orderStatus == 8){

            html= html+ '<div class="extra"> \
                <p>下单时间:'+order.orderDate+'</p> \
                </div>';
        } else if(order.orderStatus == 9){

            html= html+ '<div class="extra"> \
                <p>下单时间:'+order.orderDate+'</p> \
                </div>';
        } else if(order.orderStatus == 10){

            html= html+ '<div class="extra"> \
                <p>下单时间:'+order.orderDate+'</p> \
                </div>';
        } else if(order.orderStatus == 11){

            html= html+ '<div class="extra"> \
                <p>下单时间:'+order.orderDate+'</p> \
                </div>';
        }
        html += '</ul>';
        html += '</li>';
        return html;
    }

    function initOrderList(status,$order_list){
        checkLoginAndDeal(function (customerId) {
            var options = ( isValid(status) || status ==0 )?{statusId:status}:{};
            $.extend(options,{customerId:customerId});
            console.log(options);
            var queryOrdersUrl = baseUrl + 'newwap/APIs/order/getOrderList.php?customerId='+customerId;
            $.get(queryOrdersUrl,options, function (data) {
                data = $.parseJSON(data);
                var html = '';
                if(data.data && $.isArray(data.data) && data.data.length > 0){
                    for(var i = 0;i < data.data.length;i++){
                        var order = data.data[i];
                        html = html + orderHtml(order);
                    }
                }
                $order_list.find('ul.olist').empty().append(html);
            });
        });
    }

    $('.orderMenu li').on(base_event,function(){
        var _this = $(this);
        //var _idx = _this.index();
        _this.addClass('on').siblings().removeClass('on');
        //var $order_list = $('.orderCont .orderItem').eq(0);
        var $order_list = $('.orderCont .orderItem');
        var status = $(_this).data('status');
        if(status=='*'){
            status = null;
        }
        initOrderList(status,$order_list);
        //$order_list.show().siblings().hide();
    });

    initOrderList(null,$('.orderCont .orderItem').eq(0));
    window.initOrderList = initOrderList;
});

function cancelOrder(orderNo){
    $('.tipsCont .ui-cancel-order').data('order-no',orderNo);
    $('.tipsBg,.tipsCont,.tipsCont .ui-cancel-order').show();
}
function confirmCancelOrder(){
    var orderNo = $('.tipsCont .ui-cancel-order').data('order-no');
    var cancelOrderURL = baseUrl + 'newwap/APIs/order/cancelOrder.php?orderNo='+orderNo;
    $.getJSON(cancelOrderURL, function (data) {
        if(data.resultCode == 0){
            $('.tipsBg,.tipsCont,.tipsCont .bd').hide();
            var status = $('.orderMenu li.on').data('status');
            var $order_list = $('.orderCont .orderItem').eq(0);
            initOrderList(status, $order_list);
        }else{
            return errTips(data.resultMsg);
        }
    });
}
function cancelCancelOrder(){
    $('.tipsBg,.tipsCont,.tipsCont .bd').hide();
}
function deleteOrder(orderNo){
    var cancelOrderURL = baseUrl + 'newwap/APIs/order/removeOrder.php?orderNo='+orderNo;
    $.getJSON(cancelOrderURL, function (data) {
        if(data.resultCode == 0){
            var status = $('.orderMenu li.on').data('status');
            //var index = $('.orderMenu li.on').index();
            var $order_list = $('.orderCont .orderItem').eq(0);
            initOrderList(status, $order_list);
        }else{
            return errTips(data.resultMsg);
        }
    });
}