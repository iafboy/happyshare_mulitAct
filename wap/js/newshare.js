/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/9/16 22:57
 */

$(function(){
    var focusURL = baseUrl + 'newwap/APIs/getFocus.php?pws_id=1';
    var shareURL = baseUrl + 'newwap/APIs/getTopShares.php';

    //焦点图
    var focusHtml = '';
    $.getJSON(focusURL,function(data){
        console.log(data);
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

    //分享列表
    var shareHtml = '';
    $.getJSON(shareURL,function(data){
        console.log(data);
        if(data.resultCode == 0){
            for(var i = 0;i<data.data.length;i++){
                shareHtml += '<li><a href="hotshop.html?product_id='+data.data[i].product_id+'"><div class="spic"><img src="'+data.data[i].topic+'"></div><h5>'+data.data[i].title+'</h5><div class="info"><span class="name">'+data.data[i].username+'</span><span class="timer">'+data.data[i].sharetime+'</span></div></a></li>';
            }
        }

        // $('.sharelist-cont .sharelist').html(shareHtml);
    });
});
