/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/10/6 11:46
 */
$(function(){
    $('.sMenu li').on('tap',function(){
        var _this = $(this);
        var _idx = _this.index();
        _this.addClass('on').siblings().removeClass('on');
        var type = $(_this).data('category');
        queryMySales(type, function () {
            $('.sCont .sItem').eq(_idx).show().siblings().hide();
        });
    });


    function queryMySales(type,cb){
        detectLoginAndDeal(function (customerId) {
            var mysaleURL = baseUrl + 'newwap/APIs/customer/queryMySaleStructure.php';
            var params = {customerId:customerId};
            if(isValid(type)){
                params.type = type;
            }
            $.getJSON(mysaleURL,params, function (data){
                if(cb && $.isFunction(cb)){
                    cb();
                }
                renderHtml(type,data);
            });
        });
    }

    function renderHtml(type,data){
        if(!isValid(type)){
            type = 'total';
        }
        var list = data.data.list;
        var html = '';
        if(list && $.isArray(list) && list.length > 0){
            for(var i = 0;i < list.length; i++){
                var row = list[i];
                html= html+ '<tr class="score-row"> \
            <td class="tl">'+row.fullname+'</td> \
            <td>'+row.buyCredit+'</td> \
            <td>'+row.shareCredit+'</td>\
            <td>'+row.developCredit+'</td> \
            <td>'+row.rewardCredit+'</td> \
            </tr>';
            }
        }
        $('.sCont .sItem.'+type+' table tbody').empty().append(html);
        $('#sub-num').empty().append(data.data.num+'人');
    }



    queryMySales(null);
});