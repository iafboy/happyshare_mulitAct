/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/9/30 15:12
 */

$(function(){
    var focusURL = '../data/tjsp/focus.json';
    var shopInfoURL = '../data/tjsp/spinfo.json';

    //焦点图
    var focusHtml = '';
    $.getJSON(focusURL,function(data){
        if(data.datacode == 0){
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

    var shopInfoHtml = '';
    $.getJSON(shopInfoURL,function(data){
        if(data.datacode == 0){
            shopInfoHtml += '<h3><span>'+data.data.bigtitle+'</span></h3><div class="title">'+data.data.title+'</div><div class="topic"><img src="'+data.data.src+'"></div><div class="bd"><div class="info"><h2>购买价格：<i><em>￥</em>'+data.data.money+'</i></h2></div><div class="desc">'+data.data.desc+'</div></div>'
            $('.splist').html(shopInfoHtml);
        }
    });
});