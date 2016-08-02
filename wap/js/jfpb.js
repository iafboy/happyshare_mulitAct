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
            focusHtml += '<div class="swiper-slide"><a href="javascript:void(0)"><img width="100%" height="100%" src="'+data.data.image+'" /></a></div>';
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
                var money = parseFormatNum(product.act_price,2);
                var yuanjia = parseFormatNum(product.storeprice,2);
                var scj = parseFormatNum(product.market_price,2);
                var zoom = parseFormatNum(product.zoom,1);
                var zoomStr = '';
                if(zoom == -1){
                    zoomStr = '<em>~倍</em>&nbsp;';
                }else{
                    zoomStr = '<em>'+zoom+'倍</em>&nbsp;';
                }
                //shoplistHtml += '<li><div class="spic"><a href="act_special_score_product.html?productId='+product.product_id+'"><img src="'+data.data[i].image+'"></a></div><div class="hinfo"><h2>'+data.data[i].product_name+'</h2><p class="jifen m9">+'+parseInt(data.data[i].jifen)+'积分</p><div class="toolbar2"><a href="'+data.data[i].buylink+'" class="buy">购买</a><div class="money"><p><i>￥</i>'+money+'</p><p class="tj">特价</p></div><div class="m-num"><p>原价￥'+yuanjia+'</p><p>市场价￥'+scj+'</p></div></div></div></li>';
                shoplistHtml = shoplistHtml +
                    '<li data-product-id="'+product.product_id+'"> \
                    <div class="spic"> \
                    <a href="act_special_score_product.html?productId='+product.product_id+'&promotionId='+promotionId+'"> \
                    <img src="'+product.image+'" /> \
                    </a> \
                    </div> \
                    <div class="hinfo"> \
                    <a href="act_special_score_product.html?productId='+product.product_id+'&promotionId='+promotionId+'">' +
                    '<h2>'+product.product_name+'</h2></a> \
                <p class="jifen m9">+'+product.jifen+'积分&nbsp;'+zoomStr+'</p> \
                <div class="toolbar2"> \
                    <a href="javascript:void(0)" class="buy">购买</a> \
                    <div class="money"> \
                    <p class="jiage"><i>￥</i>'+money+'</p> \
                </div> \
                <div class="m-num"> \
                    <p>市场价 '+scj+'</p> \
                </div> \
                </div> \
                </div> \
                </li>';
            }
        }
        $('.hotshop-cont .hlist').html(shoplistHtml);
    });
    $('body').on(base_event,'.buy', function () {
        var productId = $(this).parents('li').data('product-id');
        addToMyCart(productId);
    });
});