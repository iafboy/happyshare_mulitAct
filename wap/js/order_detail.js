/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/9/30 15:33
 */

$(function(){

    var orderNo = getQueryString('orderNo');
    var supplierId = getQueryString('supplierId');
    var orderURL = baseUrl+ 'newwap/APIs/order/getOrderDetail.php?orderNo='+orderNo+'&supplierId='+supplierId;
    getMyBaseInfo(function (info) {
        var html = '<div class="myface"> \
                <img src="'+info.img+'"/> \
                </div> \
                <div class="bd"> \
                <h3 class="name">'+info.userName+'</h3> \
                <div class="info"> \
                <span class="jifen">积分余额：<i>'+info.credit+'积分</i></span><span class="tgcode">分享推广码：'+info.shareCode+'</span> \
            </div> \
            </div>';
        $('.my-info').empty().append(html);
    });
    $.getJSON(orderURL, function (data) {

        if(data.data){
            var order = data.data;
            var html = '<div class="title"> \
            <h3>订单号：'+order.orderNo+'</h3> \
                <div class="state">'+getOrderStatusText(order.orderStatus)+'</div> \
                </div> \
                <div class="bd"> \
                <h4>收货信息</h4> \
                <p>地址：'+order.receiver_address+'</p> \
            <p>姓名：'+order.receiver_fullname+'</p> \
            <p>电话：'+order.receiver_phone+'</p> \
            <p>实付款：￥'+parseFormatNum(order.pay_money,2)+'+'+parseFormatNum(order.pay_score,0)+'积分（含'+parseFormatNum(order.expMoney,2)+'元快递费）</p> \
            </div>';
            if(order.orderStatus == 0){
                html+= '<div class="btns"> \
                        <a href="javascript:void(0)" onclick="cancelOrder(\''+order.orderNo+'\')">取消</a> \
                        <a href="to_pay_2.html?orderNo='+order.orderNo+'">支付</a> \
                        </div>';
            }else if(order.orderStatus == 2){
               /* html+= '<div class="btns"> \
                        <a href="javascript:void(0)" onclick="sendOrderFake(\''+order.orderNo+'\')">假接口，自己发货</a> \
                        </div>';*/
            }else if(order.orderStatus == 10){
                html+= '<div class="btns"> \
                        <a href="javascript:void(0)" onclick="delOrder(\''+order.orderNo+'\')">删除订单</a> \
                        </div>';
            }

            //'<div class="btns"> \
            //    <a href="javascript:void(0)">查看物流</a> \
            //    </div>';

                /*html+='<div class="extra"> \
                <p>实付款：'+parseFormatNum(order.pay_money,2)+'+'+parseFormatNum(order.pay_score,0)+'积分（含'+order.expMoney+'元快递费）</p> \
            </div>';*/
            $('.order-info').empty().append(html);

            var suppliers = order.suppliers;
            html = '';
            if(suppliers && $.isArray(suppliers) && suppliers.length > 0){
                for(var i = 0; i <suppliers.length;i++){
                    var supplier = suppliers[i];
                    if(i > 0){
                        html = html + '<div style="height:20px;background-color: #eeeeee"></div>';
                    }
                    html = html +
                        '<div class="supplier-item">' +
                        '<div class="title">供货商：'+supplier.supplier_name+'</div> \
                    <div class="product-list">';
                    if(supplier.products && $.isArray(supplier.products) && supplier.products.length>0){
                        for(var j = 0;j < supplier.products.length;j++){
                            var product = supplier.products[j];
                            var baoyou = product.shippmentPrice;
                            html = html+
                                '<div class="product-item"> \
                                    <ul class="ghslist"> \
                                        <li> \
                                            <div class="spic"> \
                                                <img src="'+product.image+'"> \
                                        </div> \
                                        <div class="info"> \
                                            <h2>'+product.name+'</h2> \
                                            <p class="jiage"> \
                                                <span>￥'+ parseFormatNum(product.price,2)+'</span> \
                                                <em class="jf">一共获得'+ parseFormatNum(product.total_score,0)+'积分</em> \
                                            </p> \
                                        </div> \
                                        <div class="num">x'+product.num+'</div> \
                                    </li>';
                            if(isValid(product.express_no)){
                                html = html + '<li style="height: 30px;padding: 0px;">'+product.express_name+' 运单号：'+product.express_no+'</li>';
                            }
                            //if(product.order_product_status==1){
                            if(order.orderStatus != 0 && order.orderStatus != 1 && order.orderStatus != 11){
                                if(product.return_goods_status == 0 && product.can_return_goods == 1) {
                                    html = html +'<div class="btns">' +
                                        '<a href="apply-return-goods.html?product_id='+product.product_id+'&supplier_id='
                                        +supplier.supplier_id+'&order_no='+order.orderNo+'&quantity='+product.num+
                                        '&image='+encodeURIComponent(product.image)+
                                        '&product_name='+encodeURIComponent(product.name)
                                        +'&baoyou='+encodeURIComponent(baoyou)
                                        +'&price='+parseFormatNum(product.price,2)
                                        +'&jifen='+parseFormatNum(product.total_score,0)+'">申请退货</a>' ;
                                    if(order.orderStatus == 3){
                                        html+= ' \
                                        <a href="javascript:void(0)" onclick="beginReceiveGoods(\''+order.orderNo+'\',\''+supplier.supplier_id+'\')">确认收货</a> \
                                        ';
                                    }
                                    html += '</div>';
                                }else if( product.return_goods_status == 2) {
                                    html = html +'<div class="btns">' +
                                        //'<a href="return-goods.html?order_product_id='+product.order_product_id+'&supplier_id='
                                        '<a href="return-goods.html?product_id='+product.product_id+'&supplier_id='
                                        +supplier.supplier_id+'&order_no='+order.orderNo+'&quantity='+product.num+
                                        '&image='+encodeURIComponent(product.image)+
                                        '&product_name='+encodeURIComponent(product.name)
                                        +'&baoyou='+encodeURIComponent(baoyou)
                                        +'&price='+parseFormatNum(product.price,2)
                                        +'&jifen='+parseFormatNum(product.total_score,0)+'&order_product_id='+product.order_product_id+'">填写退货单</a>' +
                                        '</div>';
                                }else{
                                    if(product.return_goods_status == 0){
                                        html = html +'<div class="btns">' +
                                            '<p class="status-text">'+getOrderProductStatusText(product.order_product_status)+'</p>' ;
                                        if(order.orderStatus == 3){
                                            html+= ' \
                                        <a href="javascript:void(0)" onclick="beginReceiveGoods(\''+order.orderNo+'\',\''+supplier.supplier_id+'\')">确认收货</a> \
                                        ';
                                        }
                                        html += '</div>';
                                    }else{
                                        html = html +'<div class="btns">' +
                                            '<p class="status-text">'+getReturnGoodStatusText(product.return_goods_status)+'</p>' +
                                            '</div>';
                                    }
                                }
                            }

                            html = html + '</ul>';
                            html+= '</div>';
                        }
                    }
                    html = html + '</div></div></div>';
                }
            }


            $('.ghsCont').empty().append(html);
        }
    });

    //关闭层
    $('body').on(base_event,'.tipsCont .close',function(){
        $('.tipsBg,.tipsCont,.tipsCont .bd').hide();
    });
});

function cancelOrder(orderNo){
    var cancelOrderURL = baseUrl + 'newwap/APIs/order/cancelOrder.php?orderNo='+orderNo;
    $.getJSON(cancelOrderURL, function (data) {
        if(data.resultCode == 0){
            window.location = 'myorder.html';
        }else{
            return errTips(data.resultMsg);
        }
    });
}


function delOrder(orderNo){
    $('.tipsCont .ui-del-order').data('order-no',orderNo);
    $('.tipsBg,.tipsCont,.tipsCont .ui-del-order').show();
}

function confirmDelOrder(){
    var orderNo = $('.tipsCont .ui-del-order').data('order-no');
    var delOrderURL = baseUrl + 'newwap/APIs/order/removeOrder.php?orderNo='+orderNo;
    $.getJSON(delOrderURL, function (data) {
        if(data.resultCode == 0){
            window.location = 'myorder.html';
        }else{
            return errTips(data.resultMsg ||'删除订单失败！');
        }
    });
}

function beginReceiveGoods(orderNo,supplierId){
    $('.tipsCont .ui-receive-goods').data('order-no',orderNo);
    $('.tipsCont .ui-receive-goods').data('supplier-id',supplierId);
    $('.tipsBg,.tipsCont,.tipsCont .ui-receive-goods').show();
}
function confirmReceiveGoods(){
    var orderNo = $('.tipsCont .ui-receive-goods').data('order-no');
    var supplierId = $('.tipsCont .ui-receive-goods').data('supplier-id');
    var receptOrderURL = baseUrl + 'newwap/APIs/order/receiptOrder.php?orderNo='+orderNo+'&supplierId='+supplierId;
    $.getJSON(receptOrderURL, function (data) {
        if(data.resultCode == 0){
            var loc = location.href;
            window.location = loc;
        }else{
            return errTips(data.resultMsg ||'确认收货失败！');
        }
    });
}
function cancelReceiveGoods(){
    $('.tipsBg,.tipsCont,.tipsCont .bd').hide();
}
function cancelDelOrder(){
    $('.tipsBg,.tipsCont,.tipsCont .bd').hide();
}

function sendOrderFake(orderNo){
    var fakeURL = baseUrl + 'newwap/APIs/fakeFahuo.php';
    $.getJSON(fakeURL,{orderNo:orderNo}, function (data) {
        if(data.resultCode == 0){
            var loc = location.href;
            window.location = loc;
        }else{
            return errTips(data.resultMsg||'error');
        }
    });
}