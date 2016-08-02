/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/10/1 11:07
 */

$(function(){
    var focusURL = '../data/hotshare/focus.json';
    var spListURL = '../data/hotshare/splist.json';

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

    //商品列表
    var spListHtml = '';
    $.getJSON(spListURL,function(data){
        if(data.datacode == 0){
            for(var i = 0;i<data.data.length;i++){
                spListHtml += '<li><div class="topic"><img src="'+data.data[i].src+'" /></div><div class="info"><h2>'+data.data[i].title+'</h2><div class="money"><span><i>￥</i>'+data.data[i].money+'</span><em>市场价 ¥'+data.data[i].scj+'</em></div><div class="jifen">+'+data.data[i].jifen+'积分</div></div><ul class="toolbar"><li><a href="'+data.data[i].buylink+'" class="buy">购买</a><p>购买(<i class="cc7a164">'+data.data[i].buyNum+'</i>)</p></li><li><a href="javascript:void(0)" class="share">分享</a><p>分享(<i>'+data.data[i].shareNum+'</i>)</p></li><li><a href="javascript:void(0)" class="shouyi">收益</a><p>累计分享收益</p><p>(<i class="cc7a164">'+data.data[i].syNum+'</i>)</p></li></ul><div class="fd"><span class="fl">分享冠军：'+data.data[i].topone+'</span><span class="fr">该产品累计收益：<i>'+data.data[i].ljsy+'</i></span></div></li>';
            }
            $('.hslist').html(spListHtml);
        }
    });
});