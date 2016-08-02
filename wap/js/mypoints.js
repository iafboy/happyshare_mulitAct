/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/10/2 21:29
 */

$(function(){

    var showType = getQueryString('showType');
    if(!isValid(showType)){
        showType = 0;
    }
    showType =parseInt(showType);
    queryMyPoint(showType, function (data) {
        $('.ptMenu li').eq(showType).addClass('on').siblings().removeClass('on');
        renderScoreTable(showType,data.data);
        $('.ptCont .ptItem').eq(showType).show().siblings().hide();
    });

    function renderScoreTable(type,data){
        var html = '';
        if(data && $.isArray(data) && data.length > 0){
            if(type == 0){
                for(var i = 0;i < data.length;i++){
                    var score = data[i];
                    html = html +  '<tr> \
            <td>'+score.orderNo+'</td> \
            <td>'+score.orderDate+'</td> \
            <td>'+Number(score.money)+'元</td> \
            <td><p class="jifen">+'+score.credit+'</p></td> \
            </tr>';
                }
            }else if(type == 1){
                for(var i = 0;i < data.length;i++){
                    var score = data[i];
                    html = html +  '<tr> \
                    <td>'+score.actName+'</td> \
                    <td>'+score.addDate+'</td> \
                    <td><p class="jifen">+'+score.credit+'</p></td> \
                </tr>';
                }
            }else if(type == 2){
                for(var i = 0;i < data.length;i++){
                    var score = data[i];
                    html = html +  '<tr> \
                    <td>'+score.customerid+'</td> \
                    <td>'+score.level+'</td> \
                    <td><p class="jifen">'+score.credit+'</p></td> \
                    <td>'+score.status+'</p></td> \
                </tr>';
                }
            }else if(type == 3){
                for(var i = 0;i < data.length;i++){
                    var score = data[i];
                    html = html +  '<tr> \
                    <td>'+score.addDate+'</td> \
                    <td><p class="jifen">-'+score.credit+'</td> \
                    <td>'+score.way+'</p></td> \
                    <td>'+score.comment+'</p></td> \
                </tr>';
                }
            }
        }
        if(type==0){
            $('.pTable.score-01 tbody').empty().append(html);
        }else if(type==1){
            $('.pTable.score-02 tbody').empty().append(html);
        }else if(type == 2){
            $('.pTable.score-03 tbody').empty().append(html);
        }else if(type == 3){
            $('.pTable.score-04 tbody').empty().append(html);
        }
    }


    $('.ptMenu li').on(base_event,function(){
        var _this = $(this);
        var _idx = _this.index();
        _this.addClass('on').siblings().removeClass('on');
        queryMyPoint(_idx, function (data) {
            renderScoreTable(_idx,data.data);
            $('.ptCont .ptItem').eq(_idx).show().siblings().hide();
        });
    });

    getMyBaseInfo(function (info) {
        var html = '<div class="myface"> \
                <img src="'+info.img+'"/> \
                </div> \
                <div class="bd"> \
                <h3 class="name">'+info.userName+'</h3> \
                <div class="info"> \
                <span class="jifen">未入账积分：<i>'+info.unappliedCredit+'积分</i></span> \
                <span class="jifen">积分余额：<i>'+info.credit+'积分</i></span><span class="tgcode">分享推广码：'+info.shareCode+'</span> \
            </div> \
            <div class="sharelink">分享推广链接：'+info.shareUrl+'</div> \
            </div>';
        $('.my-info').empty().append(html);
    });


    function queryMyPoint(type,cb){

        switch (type){
            case 0:{
                var url = baseUrl + 'newwap/APIs/customer/queryMyOrderCredit.php';
                checkLoginAndDeal(function (customerId) {
                    $.get(url,{customerId:customerId}, function (data) {
                        data = $.parseJSON(data);
                        if(cb && $.isFunction(cb)){
                            cb(data);
                        }
                    });
                });

            }break;

            case 1:{
                var url = baseUrl + 'newwap/APIs/customer/queryMyGiftCredit.php';
                checkLoginAndDeal(function (customerId) {
                    $.get(url,{customerId:customerId}, function (data) {
                        data = $.parseJSON(data);
                        if(cb && $.isFunction(cb)){
                            cb(data);
                        }
                    });
                });

            }break;

            case 2:{
                var url = baseUrl + 'newwap/APIs/customer/queryMyShareCredit.php';
                var mydate = new Date();
                var year = mydate.getFullYear();
                var month = mydate.getMonth() + 1;
                month = month>9?month.toString():'0' + month;
                var date = year + month;
                checkLoginAndDeal(function (customerId) {
                    $.get(url,{customerId:customerId, date:date}, function (data) {
                        data = $.parseJSON(data);
                        if(cb && $.isFunction(cb)){
                            cb(data);
                        }
                    });
                });

            }break;

            case 3:{
                var url = baseUrl + 'newwap/APIs/customer/queryMyCreditConsume.php';
                checkLoginAndDeal(function (customerId) {
                    $.get(url,{customerId:customerId}, function (data) {
                        data = $.parseJSON(data);
                        if(cb && $.isFunction(cb)){
                            cb(data);
                        }
                    });
                });

            }break;
        }
    }


    function queryCurrentRule(){
        detectLoginAndDeal(function (customerId) {
            var url = baseUrl + 'newwap/APIs/customer/getShareCreditInfo.php';
            $.getJSON(url,{customerId:customerId}, function (data) {
                $('.share-rule-text').empty().text(data.data.mgs);
            });
        });
    }
    queryCurrentRule();


    $('.timesel select').on('change',function(){
        var _this = $(this);
        var _val = _this.val();
        _this.siblings('.showtxt').find('i').text(_val);
    });

    $('.timecon .selbtn').on(base_event,function(){
        var url = baseUrl + 'newwap/APIs/customer/queryMyShareCredit.php';
        var year = $('#year').val();
        var month = $('#months').val();
        var date = year +''+ month;
        checkLoginAndDeal(function (customerId) {
            $.get(url,{customerId:customerId, date:date}, function (data) {
                data = $.parseJSON(data);
                renderScoreTable(2,data.data);
            });
        });
    });


    initDateSel();
});
function initDateSel(){
    var d = new Date();
    $('#year').val(d.getFullYear());
    var month = d.getMonth() + 1;
    if((''+month).length == 1){
        month = '0' + month;
    }
    $('#months').val(month);
}