/**
 * Created by 欣 on 2016/1/3.
 */

$(function(){
    //var themeid = getQueryString('themeid');
    var focusURL = baseUrl + 'newwap/APIs/getFocus.php?pws_id=3';
    var tlistURL = baseUrl + 'newwap/APIs/product/getBrandGroupInfo.php';

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
    //品牌列表
    var tlistHtml = '';
    $.getJSON(tlistURL,function(data){
        if(data.resultCode == 0){
            for(var i = 0;i<data.data.length;i++){
                tlistHtml +=
                    '<li><a href="brandshop.html?supplierId='+data.data[i].supplierId+'">' +
                        '<img width="100%" height="100%" src="'+data.data[i].img+'" />' +
                        '<div class="cont">' +
                            '<h2>'+data.data[i].name+'</h2>' +
                            '<p>'+data.data[i].intro+'</p>' +
                            '<a href="brandshop.html?supplierId='+data.data[i].supplierId+'" class="btn">进入品牌店</a>' +
                        '</div>' +
                    '</a></li>';
            }
        }
        $('.themeg .tlist').html(tlistHtml);
    });
});