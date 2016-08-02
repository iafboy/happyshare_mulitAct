/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/10/1 21:05
 */

$(function(){
    var mcListURL = baseUrl + 'newwap/APIs/customer/queryMyCollection.php';
    var removeMcURL = baseUrl + 'newwap/APIs/product/removeCollection.php';

    //收藏列表

    getMyCollectInfo(function (customerId) {
        var mcListHtml = '';
        customerId = getDefaultCustomerId(customerId);
        mcListURL = mcListURL +  '?customerId='+customerId;
        $.getJSON(mcListURL,function(data){
            if(data.data && $.isArray(data.data) && data.data.length > 0){
                for(var i = 0;i<data.data.length;i++){
                    mcListHtml += '<li><div class="spic"><a href="hotshop.html?product_id='+data.data[i].buylink+'"><img src="'+data.data[i].topic+'" /></a></div><p><a href="hotshop.html?product_id='+data.data[i].buylink+'">'+data.data[i].title+'</a></p><a href="javascript:void(0)" class="shoucang" data-id="'+data.data[i].buylink+'"><span>取消收藏</span></a></li>';
                }
                $('.mclist').html(mcListHtml);
            }else{
                return errTips('收藏为空！');
            }
        });

        //取消收藏
        $('body').on(base_event,'.shoucang',function(){
            var _this = $(this);
            var _id = $(this).attr('data-id');

            removeFromMyCollect(_id,function(){
                _this.parents('li').remove();
            },function(){
            });

            /*$.get(removeMcURL,{customerId : customerId, productId : _id},function(data){
                data = $.parseJSON(data);
                if(data.resultCode == 0){
                    $('body').append('<div class="tipstext">取消收藏成功</div>');
                    setTimeout(function(){
                        $('.tipstext').fadeOut(function(){
                            $('.tipstext').remove();
                        });
                    },1500);
                    _this.parents('li').remove();
                }
            });*/
        });

    });
});