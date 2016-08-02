/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/9/28 21:44
 */
/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/10/1 10:07
 */

$(function(){
    var product_id = getQueryString('productId');
    var promotion_id = getQueryString('promotionId');
    //customerId 用户ID
    var productURL = baseUrl + 'newwap/APIs/activity/getActProductDetail.php?product_id='+product_id+'&promotion_id='+promotion_id;
    var actDetailURL = baseUrl + 'newwap/APIs/activity/getActDetail.php?promotionId='+promotion_id;

    $.getJSON(productURL,function(data){
        if(data.data){
            var product =  data.data;
            $('.topic').empty().append('<img src="'+product.src+'" />');
            $('.info').empty().append(
                '<h2>'+product.title+'</h2> \
                <div class="money"> \
                <span><i>￥</i>'+product.act_price+'</span> \
            <em>市场价 ¥'+product.market_price+'</em> \
            <em>原价 ¥'+product.storeprice+'</em> \
            </div> \
            <div class="minge">名额：'+product.limitpeople+'人</div>');
            if(product.isnorefound==1){
                $('.wxcontent').show();
            }else{
                $('.wxcontent').hide();
            }
            $('.sublimit').empty().append(product.sharenumber||0);
            $('.wxlimit').empty().append(product.wxshare||0);
            $('.freedays').empty().append(product.freedays||0);
        }
    });

    $('.lookAll').on(base_event,function(){
        if($('.desc').css('height') != 'auto'){
            $('.desc').css('height','auto');
        }else{
            $('.desc').css('height','42px');
        }
    });

    $('.btns').on(base_event,function(){
        $('.tipsBg,.tips-cont').fadeIn();
        $('.ui-succ').show();
    });

    $('.confirm').on(base_event,function(){
        $('.tipsBg,.tips-cont').fadeOut();
        $('.ui-succ,.ui-error').hide();
    });

});
