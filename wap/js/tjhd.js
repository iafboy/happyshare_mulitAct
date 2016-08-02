/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/10/1 10:07
 */

$(function(){
    var promotionId = getQueryString('promotionId');
    var actDetailURL = baseUrl + 'newwap/APIs/activity/getActDetail.php?promotionId='+promotionId;
    var productListURL = baseUrl + 'newwap/APIs/activity/getActProductList.php?promotionId='+promotionId;

    //焦点图
    var focusHtml = '';
    $.getJSON(actDetailURL,function(data){
        if(data.resultCode == 0){
            focusHtml += '<div class="swiper-slide"><a href="javascript:void(0)"><img src="'+data.data.image+'" /></a></div>';
        }
        $('.swiper-wrapper').html(focusHtml);
        var swiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            paginationClickable: true
        });
    });

    //购买列表
    var shoplistHtml = '';
    $.getJSON(productListURL,function(data){
        console.log(data);
        if(data.resultCode == 0){
            for(var i = 0;i<data.data.length;i++){
                var product = data.data[i];
                var money = parseFormatNum(product.act_price,2);
                var yuanjia = parseFormatNum(product.storeprice,2);
                var scj = parseFormatNum(product.market_price,2);
                shoplistHtml +=
                    '<li data-product-id="'+product.product_id+'">' +
                        '<div class="spic">' +
                            '<a href="act_special_price_product.html?productId='+product.product_id+'&promotionId='+promotionId+'">' +
                                '<img width="100%" height="100%" src="'+data.data[i].image+'">' +
                            '</a>' +
                        '</div>' +
                        '<div class="hinfo">' +
                            '<a href="act_special_price_product.html?productId='+product.product_id+'&promotionId='+promotionId+'"><h2>'+data.data[i].product_name+'</h2></a>' +
                            '<p class="jifen m9">+'+parseFormatNum(data.data[i].jifen)+'积分</p>' +
                            '<div class="toolbar2">' +
                                '<a href="javascript:void(0)" class="buy">购买</a>' +
                                '<div class="money"><p><i>￥</i>'+money+'</p><p class="tj">特价</p></div>' +
                                '<div class="m-num"><p>原价￥'+yuanjia+'</p><p>市场价￥'+scj+'</p></div>' +
                    '</div>' +
                    '</div></li>';
            }
        }
        $('.hotshop-cont .hlist').html(shoplistHtml);
    });


    $('body').on(base_event,'.buy', function () {
        var productId = $(this).parents('li').data('product-id');
        addToMyCart(productId);
    });

});
