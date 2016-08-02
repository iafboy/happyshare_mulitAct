/**
 *
 */
$(function(){


    getMyBaseInfo(function (info) {
        var html = '<div class="myface"> \
                <img src="'+info.img+'"/> \
                </div> \
                <div class="bd"> \
                <h3 class="name">'+info.userName+'</h3> \
                <div class="info"> \
                <span class="jifen">积分余额：<i>'+info.credit+'积分</i></span><span class="tgcode">分享推广码：'+info.shareCode+'</span> \
            </div> \
            <div class="sharelink">分享推广链接：'+info.shareUrl+'</div> \
            </div>';
        $('.my-info').empty().append(html);
        $('.title').empty().html('当前账户积分：'+info.credit+'积分，每个积分价值0.1元');
    });


    $('body').on(base_event,'.do-give', function () {
        var params = $('.pform').targetJSON();
        if(!isValid(params.phone1)){
            return errTips('未填写手机号！');
        }
        if(!isValid(params.phone2)){
            return errTips('未填写确认手机号！');
        }
        if(params.phone1.trim() != params.phone2.trim()){
            return errTips('手机号不一致！');
        }
        if(!isValid(params.credit) || (params.credit + '').indexOf('.') != -1){
            return errTips('积分数值必须大于0的整数！');
        }
        var giveScoreURL = baseUrl+'newwap/APIs/customer/creditTrans.php';
        checkLoginAndDeal(function(customerId){
            $.get(giveScoreURL,{customerId:customerId,targetMobile:params['phone1'],credit:params.credit},function(data){
                data = $.parseJSON(data);
                if(data.resultCode == 0){
                    var html = '<div class="tips-cont"> \
                        <div class="ui-succ undis"> \
                        <img src="../images/icon_succ.png" class="icon_state"/> \
                        <h5>赠送成功</h5> \
                        <a href="javascript:void(0)" onclick="sucDeal()" class="confirm confirm-ok">确定</a> \
                        </div>';
                    $('#dialog').empty().append(html);
                    $('.tipsBg,.tips-cont').fadeIn();
                    $('.ui-succ').show();
                }else{
                    var errMsg = isValid(data.resultMsg)?data.resultMsg:'赠送积分失败！';
                    var html = '<div class="tips-cont"> \
                        <div class="ui-succ undis"> \
                        <img src="../images/icon_alert.png" class="icon_state"/> \
                        <h5>'+errMsg+'</h5> \
                        <a href="javascript:void(0)" onclick="failDeal()" class="confirm confirm-fail">确定</a> \
                        </div>';
                    $('#dialog').empty().append(html);
                    $('.tipsBg,.tips-cont').fadeIn();
                    $('.ui-succ').show();
                }
            });
        });
    });

});
function failDeal(){
    $('.tipsBg,.tips-cont').fadeOut();
    $('.ui-succ').hide();
}
function sucDeal(){
    $('.tipsBg,.tips-cont').fadeOut();
    $('.ui-succ').hide();
    $('.pform').formClear();
}