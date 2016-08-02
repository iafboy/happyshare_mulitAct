/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/10/1 9:57
 */
$(function(){
    var money = 0;       // money to pay
    var scoreAmount = 0; // available score
    var orderMoney = 0; // order money
    var _money = 0;    //money to fill
    var jifenMoney = 0; // available score money
    var creditToCashTransferPercent = 0; // credit to cash percent

    var _scoreAmount = 0; // score to fill


    var firstOrder = 0;
    var firstOrderScore= 0;
    var firstOrderScoreMoney= 0;

    // array string  xx1,xx2,xx3
    var orderNo = getQueryString('orderNo')||'';
    if(!isValid(orderNo)){
        //TODO
    }

    var relateType = 1;
    var orderGroupNo = getQueryString('orderGroupNo')||'';
    //order
    if(!isValid(orderGroupNo)){
        relateType = 1;
    //order group
    }else{
        relateType = 2;
    }
    detectLoginAndDeal(function(customerId){
        var myinfoURL = baseUrl+'newwap/APIs/customer/showRegShareInfo.php?customerId='+customerId;
        $.getJSON(myinfoURL, function (data) {
            data = data.data;
            $('.plist li.score-pay > div p em').empty().html('(余额:'+data.credit+')');
            $('.plist li.score-pay > div.jf').empty()
                .html('<input id="scoreAmount" min="0" type="number" style="width: 70%;height: 80%;border:none;background-color: #ececec;" />'+'元');
            initPay(customerId);
            $('#scoreAmount').on('blur',function () {
                var _m = calPayMoney();
                $('.plist.third-party li.on .money').text(_m);
            });
        });
    });


    function initPay(customerId){
        var getOrderURL = baseUrl + 'newwap/APIs/order/getOrderFeeInfo.php';
        $.get(getOrderURL,{orderNoStr:orderNo,customerId:customerId}, function (data) {
            data = $.parseJSON(data);
            if(data.data){
                scoreAmount = parseInt(data.data.jifen);
                jifenMoney = parseFormatNum(data.data.jifenMoney,2);
                creditToCashTransferPercent = parseInt(data.data.creditToCashTransferPercent);
                money = parseFormatNum(data.data.money >=0 ?data.data.money: 0,2);
                orderMoney = parseFormatNum(data.data.totalMoney,2);

                _scoreAmount = scoreAmount;
                if(scoreAmount > orderMoney){
                    _scoreAmount = orderMoney;
                }
                if(data.data.firstOrder == 1){
                    $('.plist.inner-pay li').addClass('first-order');
                    firstOrder= 1;
                    firstOrderScore = data.data.firstOrderJifen;
                    firstOrderScoreMoney = data.data.firstOrderJifenMoney;
                    _scoreAmount = firstOrderScoreMoney;
                    $('#scoreAmount').attr('readonly',true);
                }
                $('#totalAmount').empty().append(orderMoney+'元');
                //$('#jifenAmount').empty().append('(余额:'+scoreAmount+')');
                //$('#jifenMoneyAmount').empty().append(_scoreAmount+'元');
                // _scoreAmount需要按照比率转换成可抵扣的现金
                //_scoreAmount = scoreAmount ;
                $('#scoreAmount').val(_scoreAmount);
                $($('.third-party.plist li')[0]).addClass('on');
                $($('.third-party.plist li')[0]).find('.money').text(calPayMoney());
            }
        });
    }

    function calPayMoney(){
        if($('li.score-pay.on #scoreAmount').length==0){
            _scoreAmount = 0;
        }else{
            _scoreAmount = parseFloat($('#scoreAmount').val());
            if(_scoreAmount < 0){
                _scoreAmount = 0;
            }
            if(_scoreAmount>parseFloat(jifenMoney)){
                _scoreAmount = jifenMoney;
            }
            if(_scoreAmount>parseFloat(orderMoney)){
                _scoreAmount = orderMoney;
            }
        }
        if(firstOrder == 1){
            _scoreAmount = firstOrderScoreMoney;
        }
        $('#scoreAmount').val(_scoreAmount);
        return parseFormatNum((orderMoney-_scoreAmount).toFixed(2),2);
    }



    $('.plist li .sel-check').on(base_event,function(){
        var that = $(this);

        if($(that).parent().hasClass('score-pay') && !$(that).parent().hasClass('first-order')){
            $(that).parent().toggleClass('on');
            var _m = calPayMoney();
            $('.plist.third-party li.on .money').text(_m);
        }else{
            $(this).parent().find('.money').text(calPayMoney());
            $(this).parent().siblings().find('.money').text('');
            $(this).parent().addClass('on').siblings().removeClass('on');
        }
    });

    function waitToPay(){
        var html =
            '<img src="../images/icon_alert.png" class="icon_state"/> \
                <h5>提现失败</h5> \
                <p>'+data.resultMsg+'</p> \
            <a href="javascript:void(0)" class="confirm">确定</a>';
        $('.ui-succ').empty().append('');
        $('.tipsBg,.tips-cont').fadeIn();
        $('.ui-succ').show();
    }

    $('body').on(base_event,'a.buy-btn', function () {
        var score ;
        if($('li.score-pay.on #scoreAmount').length==0){
            score = 0;
        }else{
            score = $('#scoreAmount').val();
        }
        //if(getNumberOpacity(score)> 0){
        //    return errTips('积分为大于0的整数！');
        //}
        if(score < 0){
            return errTips('积分不能小于0');
        }
        if((firstOrder==0)&&(score>scoreAmount)){
            return errTips('填写的积分大于积分余额！');
        }

        $('#scoreAmount').val(score);
        var _money = parseFormatNum(orderMoney-score,2);
        $('ul.third-party.plist li.on').find('money').text(_money);
        var that = this;
        if($(that).data('disable')==1){
            return;
        }
        $(that).data('disable',1);
        $(that).css('background-color','#ccc');

        detectLoginAndDeal(function (customerId) {
            var payURL = baseUrl + 'newwap/APIs/order/payOrder.php';
            var payMethod = $('.third-party.plist li.on').data('pay-method');
            //payMethod : wx/union/ali
            $.get(payURL,{orderNo:orderNo,scoreAmount:score,payMethod:payMethod,payAmount:_money,customerId:customerId,relateType:relateType,orderGroupNo:orderGroupNo}, function (data) {
                data = $.parseJSON(data);
                if(data.resultCode  == 0){
                    var paymentNo = data.data.orderPaymentNo;
                    if(_money > 0){
                        if(payMethod=='fake'){
                            var directPayURL = baseUrl + 'newwap/APIs/order/confirmOrder.php';
                            $.post(directPayURL,{orderNo:paymentNo,respMsg:'success',queryId:'directpay',isSuccess:1},function(data){
                                data = $.parseJSON(data);
                                if(data.resultCode == 0){
                                    window.location = 'pay_result.php?test=1';
                                }else{
                                    errTips(data.resultMsg);
                                    $(that).data('disable',0);
                                    $(that).css('background-color','#c7a164');
                                    return;
                                }
                            });
                        }else if(payMethod == 'wx'){
                            if($('li.wx-pay').data('is-in-wx')!=1){
                                // not in wechat
                                errTips('微信支付只在微信中支持！');
                                $(that).data('disable',0);
                                $(that).css('background-color','#c7a164');
                                return;
                            }
                            var wxpayURL = baseUrl+'system/pay/wxpay/example/jsapi.php';
                            var $form = $('#postWxPay');
                            $form.attr('action',wxpayURL);
                            $form.find('#totalFee').val(parseInt(_money*100));
                            $form.find('#orderNo').val(paymentNo);
                            $form.find('#productDesc').val('乐分享订单');
                            $form.find('#wxFrontURL').val(baseUrl+'mobile/html/wx_pay_result.php');
                            //$form.find('#backUrl').val(baseUrl + 'system/pay/unionpay/BackReceive.php');
                            $form.submit();
                        }else if(payMethod == 'union'){
                            var $form = $('#postUnionPay');
                            $form.attr('action',baseUrl + 'system/pay/unionpay/payment.php');
                            $form.find('#txnAmt').val(parseInt(_money*100));
                            $form.find('#orderId').val(paymentNo);
                            $form.find('#frontUrl').val(baseUrl+'mobile/html/pay_result.php');
                            $form.find('#backUrl').val(baseUrl + 'system/pay/unionpay/BackReceive.php');
                            $form.submit();
                        }else if(payMethod == 'ali'){
                            $(that).data('disable',0);
                            $(that).css('background-color','#c7a164');
                        }
                    }else{
                        //window.location = 'pay_result.php?orderNo='+orderNo+'&pay_result=1';
                        var directPayURL = baseUrl + 'newwap/APIs/order/confirmOrder.php';
                        $.post(directPayURL,{orderNo:paymentNo,respMsg:'支付成功',queryId:'SX',isSuccess:1},function(data){
                            if(data.resultCode == 0){
                                window.location = 'jifen_pay_result.php?jifenResult=1';
                            }else{
                                var resultMsg = isValid(data.resultMsg)?data.resultMsg:'支付失败!';
                                window.location = 'jifen_pay_result.php?jifenResult=1&resultMsg='+resultMsg;
                            }
                        });
                    }
                }else{
                    errTips(data.resultMsg);
                    $(that).data('disable',0);
                    $(that).css('background-color','#c7a164');
                    return;
                }
            });
        });
    });
});