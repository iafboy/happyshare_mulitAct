/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/9/20 10:54
 */

$(function(){
    var ztg = getQueryString('ztg');

    var focusURL = baseUrl + 'newwap/APIs/getFocus.php?pws_id=3';
    var hotshopURL = baseUrl + 'newwap/APIs/hotshop/getZtgProduct.php?num=20&ztg='+ztg+'';

    //焦点图
    var focusHtml = '';
    $.getJSON(focusURL,function(data){
        if(data.resultCode == 0){
            for(var i = 0;i<data.data.length;i++){
                focusHtml += '<div class="swiper-slide"><a href="'+data.data[i].link+'"><img width="100%" height="100%" src="'+data.data[i].src+'" /></a></div>';
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
                    var money = parseFormatNum(data.data[i].money,2);
                    var oldmoney = parseFormatNum(data.data[i].oldmoney,2);
                    var soldQuantity = parseInt(data.data[i].xiaoliang);
                    var oldMoneyStr = '市场价￥'+oldmoney;
                    var proCls = '';
                    var product = data.data[i];
                    var productUrl = 'hotshop.html?product_id='+product.id;
                    var act_price = 0;
                    var act_credit = product.jifen;
                    var credit_zoom = 0;
                    var zoomStr = '';
                    if(isValid(product.promotion_id) || product.promotion_id == 0){
                        if(product.promotion_type == 0){
                            if(product.sub_promotion_type == 0){
                                proCls = 'tj';
                                productUrl = 'act_special_price_product.html?productId='+product.id+'&promotionId='+product.promotion_id;
                                act_price = parseFormatNum(product.act_price,2);
                                oldmoney = money;
                                money = act_price;
                                oldMoneyStr = '原价￥'+oldmoney;
                            }else if(product.sub_promotion_type == 1){
                                proCls = 'jfpb';
                                productUrl = 'act_special_score_product.html?productId='+product.id+'&promotionId='+product.promotion_id;
                                act_credit = product.act_credit;
                                credit_zoom = parseFormatNum(product.zoom,1);
                                if(credit_zoom == -1){
                                    zoomStr = '<em>~倍</em>&nbsp;';
                                }else{
                                    zoomStr = '<em>'+credit_zoom+'倍</em>&nbsp;';
                                }
                            }
                        }
                    }
                    hotshopHtml +=
                        '<li data-id="'+data.data[i].id+'" class="pro-box">' +
                        '<div class="'+proCls+'"></div>' +
                            '<div class="spic">' +
                                '<a href="'+productUrl+'">' +
                                    '<img src="'+data.data[i].src+'" />' +
                                '</a>' +
                            '</div>' +
                            '<div class="hinfo">' +
                                '<h2><a href="'+productUrl+'">'+data.data[i].title+'</a></h2>' +
                                '<p class="ghs m9">供货商：'+data.data[i].ghs+'</p><p class="jifen">+'+act_credit+'积分 '+zoomStr+'<i>销量：</i>'+soldQuantity+'</p>' +
                                '<div class="toolbar">' +
                                    '<a href="javascript:void(0)" class="buy">购买</a>' +
                                    '<div class="mcon"><span class="money"><i>￥</i>'+money+'</span><span class="oldmoney">'+oldMoneyStr+'</span></div>' +
                                '</div>' +
                            '</div>' +
                        '</li>';
                }
            }
            $('.hotshop-cont .hlist').html(hotshopHtml);
        });
    }
    showShopList(baseUrl +'newwap/APIs/hotshop/getHotShop.php?num=20&show=recommended&ztg='+ztg);
    $('.filter a').on(base_event,function(){
        var _idx = $(this).index();
        $(this).addClass('on').siblings().removeClass('on');
        if(_idx == 0){
            hotshopURL = baseUrl + 'newwap/APIs/hotshop/getHotShop.php?num=20&show=recommended&ztg='+ztg;
        }else if(_idx == 1){
            hotshopURL = baseUrl + 'newwap/APIs/hotshop/getHotShop.php?num=20&show=newArrival&ztg='+ztg;
        }else if(_idx == 2){
            hotshopURL = baseUrl + 'newwap/APIs/hotshop/getHotShop.php?num=20&show=buyNum&ztg='+ztg;
        }
        showShopList(hotshopURL);
    });

    //添加到购物车
    $('body').on(base_event,'.hinfo .buy',function(){
        var _productId = $(this).parents('li').attr('data-id');
        addToMyCart(_productId);
    });
});
