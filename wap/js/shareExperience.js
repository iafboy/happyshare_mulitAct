/**
 * Created by 欣 on 2016/1/17.
 */


$(function(){
    var productId = getQueryString('product_id');
    var shareUrl = baseUrl + 'mobile/html/hotshop.html?product_id='+productId+'&shareCode=';

    var url = baseUrl + 'newwap/APIs/product/getProductSharecases.php?product_id='+productId;

    $.getJSON(url,{product_id:productId}, function (data) {

        var cases = data.data.cases;
        var html = '';
        for(var i = 0;i < cases.length; i ++ ){
            var _case = cases[i];
            html = html +
                '<div class="shop-xq mt10" data-case-id="'+_case.prdshare_id+'" style="height:auto;" > \
                <img src="'+_case.imgurl1+'"/> \
                <h3 class="title-2">'+_case.title+'</h3> \
                <p>'+_case.memo+'</p> \
                <div class="more"><span><a href="shareExperienceDetail.html?case_id='+_case.prdshare_id+'&product_id='+productId+'">查看全部</a></span></div> \
            </div>';

        }
        $('.case-box').empty().append(html);
    });

    /*$('body').on('tap','.shop-xq .more',function(){
        $(this).toggleClass('on');
        if($(this).parents('.shop-xq').css('height') != 'auto'){
            $(this).parents('.shop-xq').css('height','auto');
        }else{
            $(this).parents('.shop-xq').css('height','120px');
        }

    });*/
});