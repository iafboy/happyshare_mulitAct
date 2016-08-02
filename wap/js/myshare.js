/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/10/2 22:04
 */

$(function(){
    var shareListURL = baseUrl + 'newwap/APIs/customer/showProductShareInfo.php';

    var registerShareURL = 'register.html';
    var productShareURL =  'hotshop.html?product_id=';

    function minifyUrl(url){
        if(!isValid(url)){
            return '';
        }
        if(url.trim().length>30){
            var size = url.trim().length - 30;
            var left =size / 2;
            var right = size = left;

        }
    }

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
        var regLink=baseUrl+'mobile/html/register.html?shareCode='+info.shareCode;
        var shareListHtml  =
            '<li>' +
            '<div class="title"><h3>产品注册推广链接</h3></div>' +
            '<div class="bd">' +
            '<div class="spic"><img src="'+baseUrl+'image/customer/register-share.png"><a><p></p></a></div>' +
            '<div class="info">' +
                '<p>'+regLink+'</p>'+
            '<p>链接点击次数：'+info.clickNum+'次</p>' +
            '<p>链接转化注册人数：'+info.regNum+'人</p>' +
            '<p>积分收益：<i>获得'+info.regCredit+'积分</i></p>' +
            '</div>' +
            '</div>' +
            '</li>';
        //产品分享列表
        getMyShareInfo(function (customerId) {
            $.getJSON(shareListURL,{customerId:customerId},function(data){
                if(data.resultCode == 0){
                    for(var i = 0;i<data.data.length;i++){
                        var shareUrl = baseUrl + 'mobile/html/hotshop.html?product_id='+data.data[i].productId+'&shareCode='+data.data[i].shareCode;
                        var productUrl = baseUrl + 'mobile/html/hotshop.html?product_id='+data.data[i].productId;
                        shareListHtml = shareListHtml +
                            '<li>' +
                            '<div class="title"><h3>产品分享推广链接</h3></div>' +
                            '<div class="bd">' +
                            '<div class="spic"><a href="'+productUrl+'"><img src="'+data.data[i].topic+'"><p>'+data.data[i].title+'</p></a></div>' +
                            '<div class="info">' +
                            '<p>'+shareUrl+'</p>'+
                            '<p>链接点击次数：'+data.data[i].clickNum+'次</p>' +
                            '<p>链接转化注册人数：'+data.data[i].regNum+'人</p>' +
                            '<p>积分收益：<i>获得'+data.data[i].regCredit+'积分</i></p>' +
                            '</div>' +
                            '</div>' +
                            '</li>';
                    }
                    $('.tglist').empty().html(shareListHtml);
                }
            });
        });


    });

});