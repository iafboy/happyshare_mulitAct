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
        if(data.resultCode == 0){
            for(var i = 0;i<data.data.length;i++){
                var product = data.data[i];
                var money = parseInt(product.act_price);
                var yuanjia = parseInt(product.storeprice);
                var scj = parseInt(product.market_price);
                shoplistHtml += '<li><div class="spic"><a href="act_free_trial_product.html?productId='+product.product_id+'&promotionId='+promotionId+'"><img src="'+data.data[i].image+'"></a></div><div class="hinfo"><h2>'+data.data[i].product_name+'</h2><p class="jifen m9">+'+parseInt(data.data[i].jifen)+'积分</p><div class="toolbar2"><a href="'+data.data[i].buylink+'" class="buy">购买</a><div class="money"><p><i>￥</i>'+money+'</p><p class="tj">免费体验</p></div><div class="m-num"><p>原价￥'+yuanjia+'</p><p>市场价￥'+scj+'</p></div></div></div></li>';
            }
        }
        $('.hotshop-cont .hlist').html(shoplistHtml);
    });
});