/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/9/20 10:54
 */

$(function(){
    var supplierId = getQueryString('supplierId');
    var focusURL = baseUrl + 'newwap/APIs/product/getSupplierBanner.php?supplierId='+supplierId;
    var hotshopURL = baseUrl + 'newwap/APIs/product/getSupplierProduct.php?supplierId='+supplierId+'&num=20';
    var buyshopURL = baseUrl + 'newwap/APIs/shoppingCart/addProduct2Cart.php';

    //焦点图
    var focusHtml = '';
    $.getJSON(focusURL,function(data){
        if(data.resultCode == 0){
            for(var i = 0;i<data.data.length;i++){
                focusHtml += '<div class="swiper-slide"><a href="'+data.data[i].link+'"><img width="100%" height="100%" src="'+data.data[i].image+'" /></a></div>';
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
                    var oldmoney = parseFormatNum(data.data[i].scj,2);
                    var soldQuantity = parseFormatNum(data.data[i].xiaoliang,0);
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
                        '<li class="pro-box" data-id="'+data.data[i].id+'" data-url="'+productUrl+'">' +
                            '<div class="'+proCls+'"></div>' +
                            '<div class="spic"><img width="100%" height="100%" src="'+data.data[i].topic+'" /></div>' +
                            '<div class="hinfo">' +
                                '<h2>'+data.data[i].title+'</h2>' +
                                '<p class="ghs m9">供货商：'+data.data[i].supplierName+'</p>' +
                                '<p class="jifen">+'+act_credit+'积分 '+zoomStr+'<i>销量：</i>'+soldQuantity+'</p>' +
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
    showShopList(hotshopURL);

    //添加到购物车
    $('body').on('tap','.hlist .buy',function(event){
        var _productId = $(this).parents('li').attr('data-id');
        addToMyCart(_productId);
    });

    $('body').on('click','.hlist li',function(event){
        if(!$(event.target).hasClass("buy")){
            var id = $(this).attr('data-id');
            var url = $(this).data('url');
            window.location.href = url;
        }
    });
});