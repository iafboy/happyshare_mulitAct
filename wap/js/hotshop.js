/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/9/20 10:54
 */

$(function(){
    var focusURL = baseUrl + 'newwap/APIs/getFocus.php?pws_id=3';
    var hotshopURL = baseUrl + 'newwap/APIs/hotshop/getHotShop.php?num=20';

    //焦点图
    var focusHtml = '';
    $.getJSON(focusURL,function(data){
        if(data.resultCode == 0){
            for(var i = 0;i<data.data.length;i++){
                focusHtml += '<div class="swiper-slide"><a href="'+data.data[i].link+'"><img src="'+data.data[i].src+'" /></a></div>';
            }
        }
        $('.swiper-wrapper').html(focusHtml);
        var swiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            paginationClickable: true
        });
    });

    //热门商品列表
    function showShopList(hotshopURL){
        var hotshopHtml = '';
        $.getJSON(hotshopURL,function(data){
            if(data.resultCode == 0){
                for(var i = 0;i<data.data.length;i++){
                    var money = parseInt(data.data[i].money);
                    var oldmoney = parseInt(data.data[i].oldmoney);
                    hotshopHtml += '<li data-id="'+data.data[i].id+'"><div class="spic"><a href="hotshop.html?product_id='+data.data[i].id+'"><img src="'+data.data[i].src+'" /></a></div><div class="hinfo"><h2><a href="hotshop.html?product_id='+data.data[i].id+'">'+data.data[i].title+'</a></h2><p class="ghs m9">供货商：'+data.data[i].ghs+'</p><p class="jifen">+'+data.data[i].jifen+'积分 <i>销量：</i>1000</p><div class="toolbar"><a href="javascript:void(0)" class="buy">购买</a><div class="mcon"><span class="money"><i>￥</i>'+money+'</span><span class="oldmoney">市场价￥'+oldmoney+'</span></div></div></div></li>';
                }
            }
            $('.hotshop-cont .hlist').html(hotshopHtml);
        });
    }
    showShopList(hotshopURL);
    $('.filter a').on(base_event,function(){
        var _idx = $(this).index();
        $(this).addClass('on').siblings().removeClass('on');
        if(_idx == 0){
            hotshopURL = baseUrl + 'newwap/APIs/hotshop/getHotShop.php?num=20&show=recommended';
        }else if(_idx == 1){
            hotshopURL = baseUrl + 'newwap/APIs/hotshop/getHotShop.php?num=20&show=newArrival';
        }else if(_idx == 2){
            hotshopURL = baseUrl + 'newwap/APIs/hotshop/getHotShop.php?num=20&show=buyNum';
        }
        showShopList(hotshopURL);
    });

    //添加到购物车
    $('body').on(base_event,'.hinfo .buy',function(){
        var _productId = $(this).parents('li').attr('data-id');

        addToMyCart(_productId);
    });
});