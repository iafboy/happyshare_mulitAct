/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/10/1 10:56
 */

$(function () {
    var focusURL = baseUrl + 'newwap/APIs/getFocus.php?pws_id=2';
    var maybeListURL = baseUrl + 'newwap/APIs/product/getHighProfitProductList.php';
    var getTopShare = baseUrl + 'newwap/APIs/product/getShareList.php';

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
    function renderShareProductsByZTG(ztg){
        var maybeListHtml = '';
        $.get(maybeListURL,{ztg:ztg,limit:6},function(data){
            data = $.parseJSON(data);
            if(data.resultCode == 0){
                if(data.data && data.data.length>0){
                    for(var i = 0;i<data.data.length;i++){
                        for(var x = 0;x<data.data[i].list.length;x++){
                            maybeListHtml += '<li><a href="'+data.data[i].list[x].link+'"><img src="'+data.data[i].list[x].src+'"></a></li>';
                        }
                    }
                    $('.maybelist .'+ztg+' .splist').empty().html(maybeListHtml);
                }else{
                    $('.maybelist .'+ztg).remove();
                }
            }else{
                $('.maybelist .'+ztg).remove();
            }
        });
    }


    function renderTopShare(){
        var maybeListHtml = '';
        $.get(getTopShare,{num:6},function(data){
            data = $.parseJSON(data);
            console.log(data);
            if(data.resultCode == 0){
                if(data.data && data.data.length>0){
                    for(var i = 0;i<data.data.length;i++){
                        var share = data.data[i];
                        maybeListHtml += '<li><a href="hotshop.html?product_id='+share.product_id+'"><img src="'+share.topic+'"></a></li>';
                    }
                    $('.maybelist .exp .splist').empty().html(maybeListHtml);
                    //负责调整图片高度一致
                    $('.splist li').each(function(){
                        var _this = $(this);
                        var _w = _this.find('img').width();
                        _this.find('img').css({
                            width : _w,
                            height : _w
                        });
                        _this.height(_w);
                    });
                }else{
                    $('.maybelist .exp').remove();
                }
            }else{
                $('.maybelist .exp').remove();
            }
        });
    }

    function renderShareList(){
        renderShareProductsByZTG('bpg');
        renderShareProductsByZTG('jkg');
        renderShareProductsByZTG('kjg');
        renderShareProductsByZTG('crg');
        renderShareProductsByZTG('fxg');
        renderShareProductsByZTG('hdg');
        renderTopShare();
    }
    renderShareList();
});