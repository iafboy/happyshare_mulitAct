/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/9/16 22:57
 */

$(function(){
    var _width = $(window).width();
    function ztInit(){
        _width = $(window).width();
        $('.ztlist,.brandlist').width(_width+10);
        $('.ztlist li,.brandlist li').width(_width/2);
    }
    $(window).on('resize',function(){
        ztInit();
    });

    var focusURL = baseUrl + 'newwap/APIs/getFocus.php?pws_id=1';
    var showPicURL = baseUrl + 'newwap/APIs/hotshop/getHotShop.php?num=3';
    var ztgURL = baseUrl + 'newwap/APIs/getZTG.php';
    var brandURL = baseUrl + 'newwap/APIs/product/getBrandGroupInfo.php';
    var shareURL = baseUrl + 'newwap/APIs/getTopShares.php';

    //焦点图
    var focusHtml = '';
    $.getJSON(focusURL,function(data){
        if(data.resultCode == 0){
            for(var i = 0;i<data.data.length;i++){
                focusHtml += '<div class="swiper-slide"><a href="'+data.data[i].link+'"><img data-width="'+_width+'" data-height="143" class="ui-img" src="'+data.data[i].src+'" /></a></div>';
            }
        }
        $('.swiper-wrapper').html(focusHtml);
        var swiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            paginationClickable: true
        });
    });

    //展示列表
    var spHtml = '';
    $.getJSON(showPicURL,function(data){
        if(data.resultCode == 0){
            for(var i = 0;i<data.data.length;i++){
                var link = '';
                var proCls = '';
                var product = data.data[i];
                var productUrl = 'hotshop.html?product_id='+product.id;
                if(isValid(product.promotion_id) || product.promotion_id == 0){
                    if(product.promotion_type == 0){
                        if(product.sub_promotion_type == 0){
                            proCls = 'tj';
                            productUrl = 'act_special_price_product.html?productId='+product.id+'&promotionId='+product.promotion_id;
                        }else if(product.sub_promotion_type == 1){
                            proCls = 'jfpb';
                            productUrl = 'act_special_score_product.html?productId='+product.id+'&promotionId='+product.promotion_id;

                        }
                    }
                }
                if(i == 0){
                    link = 'hotshop.html';
                    spHtml +=
                        '<li  class="pro-box"><div class="'+proCls+'"></div><a href="'+productUrl+'">' +
                        '<img data-width="'+_width+'" data-height="120" class="ui-img" src="'+data.data[i].src+'" />' +
                        '<p>'+data.data[i].title+'</p>' +
                        '</a></li>';
                }else{
                    spHtml += '<li  class="pro-box"><div class="'+proCls+'"></div><a href="'+productUrl+'"><img data-width="'+_width+'" data-height="120" class="ui-img" src="'+data.data[i].src+'" /><p>'+data.data[i].title+'</p></a></li>';
                }
            }
        }
        $('.showpic-cont .slist').html(spHtml);
    });

    //主题馆
    var ztgHtml = '';
    $.getJSON(ztgURL,function(data){
        if(data.resultCode == 0){
            for(var i = 0;i<data.data.length;i++){
                ztgHtml += '<li><a href="ztglist.html?ztg='+data.data[i].buylink+'"><img data-width="'+_width/2+'" data-height="107" class="ui-img" src="'+data.data[i].src+'" /><div class="bd"><h4>'+data.data[i].TEXT+'</h4><em class="more">更多</em></div></a></li>';
            }
        }
        $('.ztlist-cont .ztlist').html(ztgHtml);
        ztInit();
    });

    //品牌馆
    var brandHtml = '';
    $.get(brandURL,{num:6},function(data){
        data = $.parseJSON(data);
        if(data.resultCode == 0){
            for(var i = 0;i<data.data.length;i++){
                brandHtml += '<li><a href="brandshop.html?supplierId='+data.data[i].supplierId+'"><img data-width="'+_width/2+'" data-height="107" class="ui-img" src="'+data.data[i].img+'" /></a></li>';
            }
        }
        $('.brandlist-cont .brandlist').html(brandHtml);
        ztInit();
    });

    //分享列表
    var shareHtml = '';
    $.getJSON(shareURL,function(data){
        if(data.resultCode == 0){
            for(var i = 0;i<data.data.length;i++){
                var proCls = '';
                var product = data.data[i];
                var productUrl = 'hotshop.html?product_id='+product.product_id;
                if(isValid(product.promotion_id) || product.promotion_id == 0){
                    if(product.promotion_type == 0){
                        if(product.sub_promotion_type == 0){
                            proCls = 'tj';
                            productUrl = 'act_special_price_product.html?productId='+product.product_id+'&promotionId='+product.promotion_id;
                        }else if(product.sub_promotion_type == 1){
                            proCls = 'jfpb';
                            productUrl = 'act_special_score_product.html?productId='+product.product_id+'&promotionId='+product.promotion_id;

                        }
                    }
                }
                var phone = product.username;
                phone = phone || '';
                if(phone != ''){
                    phone = phone.substr(0,3) +'******';
                }
                shareHtml +=
                    '<li class="pro-box"><div class="'+proCls+'"></div>' +
                    '<a href="'+productUrl+'">' +
                    '<div class="spic"><img  data-width="'+_width+'" data-height="179" class="ui-img" src="'+data.data[i].topic+'"></div>' +
                    '<h5>'+data.data[i].title+'</h5>' +
                    '<div class="info"><span class="name">'+phone+'</span><span class="timer">'+data.data[i].sharetime+'</span></div>' +
                    '</a>' +
                    '</li>';
            }
        }

        $('.sharelist-cont .sharelist').html(shareHtml);
    });
});