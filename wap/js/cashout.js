/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/10/6 22:28
 */
/*function changeVerifyCode(){
    $('.codepic').attr('src',baseUrl + 'newwap/APIs/customer/getWithdrawVerifyCode.php?'+new Date());
}*/
$(function(){
    $('.lookAll').on('tap',function(){
        if($('.desc').css('height') != 'auto'){
            $('.desc').css('height','auto');
        }else{
            $('.desc').css('height','42px');
        }
    });

    $('body').on(base_event,'.bklist li', function () {
        $(this).addClass('on').siblings().removeClass('on');
    });

    $('.suBtn').on('tap',function(){
        var params = $('.bankInfoCont').targetJSON();
        $.extend(params,{amount:$('#jifen').val()});
        $.extend(params,{bankId:$($('.bklist li.on')[0]).data('bank-id')});
        if(!isValid(params.amount) || (params.amount+'').indexOf('.') != -1){
            return errTips('积分必须为大于0的整数！');
        }
        params.credit = params.amount;

        if(!isValid(params.bankId)){
            return errTips('未选择银行！');
        }
        if(!isValid(params.cardId)){
            return errTips('未填写卡号！');
        }
        if(!verifyBankCard(params.cardId)){
            return errTips('银行卡号不正确！');
        }
        if(!isValid(params.cardHolder)){
            return errTips('未填写姓名！');
        }
        if(!isValid(params.cardHolderId)){
            return errTips('未填写身份证号！');
        }
        if(!verifyPID(params.cardHolderId)){
            return errTips('身份证号不正确！');
        }
        if(!isValid(params.receiverEmail)){
            return errTips('未填写email！');
        }
        checkLoginAndDeal(function (customerId) {
            $.extend(params,{customerId:customerId});
            var postWithdrawURL = baseUrl + 'newwap/APIs/customer/creditToCash.php';
            $.post(postWithdrawURL,params, function (data) {
                data = $.parseJSON(data);
                if(data.resultCode == 0){
                    var html ='';
                    if(data.data.percent > 0){
                        html = '<img src="../images/icon_succ.png" class="icon_state"/> \
                        <h5>提现成功</h5> \
                        <p>扣除'+data.data.percent+'%个人所得税后提现金额为'+data.data.money+'元，我们将在7个工作日内为您进行转账，请注意查收！</p> \
                        <a href="javascript:void(0)" class="confirm" onclick="turnToCreditPage()">确定</a>';

                    }else{
                        html = '<img src="../images/icon_succ.png" class="icon_state"/> \
                        <h5>提现成功</h5> \
                        <p>提现金额为'+data.data.money+'元，我们将在7个工作日内为您进行转账，请注意查收！</p> \
                        <a href="javascript:void(0)" class="confirm" onclick="turnToCreditPage()">确定</a>';
                    }
                    $('.ui-succ').empty().append(html);
                    $('.tipsBg,.tips-cont').fadeIn();
                    $('.ui-succ').show();
                }else{
                    var html = '<img src="../images/icon_alert.png" class="icon_state"/> \
                        <h5>提现失败</h5> \
                        <p>'+data.resultMsg+'</p> \
                    <a href="javascript:void(0)" class="confirm" onclick="closeConfirmWindow()">确定</a>';
                    $('.ui-succ').empty().append(html);
                    $('.tipsBg,.tips-cont').fadeIn();
                    $('.ui-succ').show();
                }
            });
        });
    });

    $('.confirm').on('tap',function(){
        $('.tipsBg,.tips-cont').fadeOut();
        $('.ui-succ').hide();
    });

    function initPage(){
        checkLoginAndDeal(function (customerId) {
            var checkInfoURL = baseUrl + 'newwap/APIs/customer/queryCreditToCash.php';
            $.getJSON(checkInfoURL,{customerId:customerId}, function (data) {
                $('.jifen-text').empty().append(data.data.jifen);
                $('.tax-percent').empty().append(data.data.taxPercent);
            });
        });
    }
    initPage();
});
function closeConfirmWindow(){
    $('.tipsBg,.tips-cont').fadeOut();
    $('.ui-succ').hide();
}

function verifyBankCard(cardNo){
    return isValid(cardNo) && /^\d{16}|\d{19}$/.test(cardNo);
}

function verifyPID(pid){
    return isValid(pid) && /^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/.test(pid);
}

function turnToCreditPage(){
    var url = 'myscore.html?showTyp=3';
    window.location = url;
}