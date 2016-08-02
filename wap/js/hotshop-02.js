/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/9/30 15:33
 */

$(function(){
    var product_id = getQueryString('product_id');

    //customerId 用户ID
    var shopInfoURL = baseUrl + 'newwap/APIs/product/getHotShareProductDetail.php?product_id='+product_id+'';


    //添加到购物车
    $('body').on(base_event,'.shop-info .shop',function(){
        addToMyCart(product_id);
    });

    //直接购买
    $('body').on(base_event,'.shop-info .buy',function(){
        directPay(product_id);
    });


    //页面信息回显
    var shopContHtml = '';
    var shopInfoHtml = '';
    var shopXqHtml = '';
    var focusHtml = '';
    $.getJSON(shopInfoURL,function(data){
        if(data.resultCode == 0){
            var money = parseFormatNum(data.data.money,2);
            var scj = parseFormatNum(data.data.scj,2);
            var jifen = parseFormatNum(data.data.jifen);
            focusHtml += '<div class="swiper-slide"><a><img src="'+data.data.image+'" /></a></div>';
            $('.swiper-wrapper').html(focusHtml);
            var swiper = new Swiper('.swiper-container', {
                pagination: '.swiper-pagination',
                paginationClickable: true
            });
            var hasCollented = data.data.hasCollented;
            var collectCz = '';
            if(hasCollented == 1){
                collectCz = 'on';
                isCollect = true;
            }
            shopContHtml = shopContHtml +
                '<h1>'+data.data.title+'</h1>' +
                '<p>供货商：'+data.data.ghs+'　</p>' +
                '<ul class="toolbar">' +
                    '<li class="share-btn">' +
                        '<a href="share.html?product_id='+product_id+'" class="share">分享</a>' +
                        '<p>分享(<i>'+data.data.shareNum+'</i>)</p>' +
                    '</li>' +
                    '<li class="comment-btn">' +
                        '<a href="#MakeComment">评论</a>' +
                        '<p>评论(<i class="cc7a164">'+data.data.commentNum+'</i>)</p>' +
                    '</li>' +
                    '<li class="collect-btn '+collectCz+'">' +
                        '<a href="javascript:void(0)" class="collect">收藏</a>' +
                        '<p>收藏(<i class="cc7a164">'+data.data.collectNum+'</i>)</p>' +
                    '</li>' +
                '</ul>';
            shopInfoHtml = shopInfoHtml +
                '<p class="money"><i>￥</i>'+money+'</p>' +
                '<div class="xinxi">' +
                    '<span class="jiage">市场价￥'+scj+'</span>' +
                    '<span class="jifen">'+jifen+'积分</span>' +
                    '<span>销量:<i>'+data.data.xiaoliang+'</i></span>' +
                    '<span>库存:<i>'+data.data.quantity+'</i></span>' +
                '</div>' +
                '<div class="btns">' +
                    '<a href="javascript:void(0)" class="buy"><span>直接购买</span></a>' +
                    '<a href="javascript:void(0)" class="shop"><span>加入购物车</span></a>' +
                '</div>';
            //shopXqHtml += '<p>'+data.data.content+'</p>';
            for(var i = 0 ;i<data.data.imgs.length;i++){
                shopXqHtml += '<img src="'+data.data.imgs[i].img+'" />'
            }
            shopXqHtml += '<div class="more"><span>查看全部</span></div>';
            $('.shop-cont').html(shopContHtml);
            $('.shop-info').prepend(shopInfoHtml);
            $('.shop-xq').html(shopXqHtml);
            dealWithExpressInfo(data);
        }





    });
    function dealWithExpressInfo(data)
    {
        var expressHtml = '';
        if(data.data.refoundtype == 0){
            expressHtml = '退货说明：不允许退货';
        }
        if(data.data.refoundtype != 0 ){
            expressHtml = '退货说明：签收'+data.data.refoundlimit+'天内允许退货';
        }
        $('.shop-info .tips').empty().append(expressHtml);
    }
    $('body').on(base_event,'.shop-xq .more',function(){
        if($(this).hasClass('on')){
            $('.shop-xq').css('height','120px');
            $(this).removeClass('on');
            $(this).find('span').text('查看全部');
        }else{
            $('.shop-xq').css('height','auto');
            $(this).addClass('on');
            $(this).find('span').text('收起');
        }
    });




    //收藏按钮
    var isCollect = false;

    $('body').on(base_event,'.toolbar .collect',function(){
        if(isCollect){
            removeFromMyCollect(product_id,function(){
                isCollect = false;
                $('.shop-cont .toolbar li.collect-btn').removeClass('on');
                var oriNum =parseInt($('.shop-cont .toolbar li.collect-btn p i').text());
                oriNum = oriNum - 1;
                if(oriNum < 0){
                    oriNum=0;
                }
                $('.shop-cont .toolbar li.collect-btn p i').text(oriNum);

            },function(){
            });
        }else{
            addToMyCollect(product_id,function(){
                isCollect = true;
                $('.shop-cont .toolbar li.collect-btn').addClass('on');
                var oriNum =parseInt($('.shop-cont .toolbar li.collect-btn p i').text());
                oriNum = oriNum + 1;
                $('.shop-cont .toolbar li.collect-btn p i').text(oriNum);
            },function(){
            });
        }
    });

    //评论列表
    function renderComments(){
        getComments(product_id,function (data) {
            if(data.data && $.isArray(data.data) && data.data.length>0){
                var commentHtml = '';
                for(var i = 0;i<data.data.length;i++){
                    var cls = data.data[i].admin_review == 1 ?'admin':'';
                    var img = data.data[i].admin_review == 1 ?'../images/admin-g.png':data.data[i].userImg;
                    var userName = data.data[i].admin_review == 1 ?'管理员':data.data[i].userName;
                    var createDate = data.data[i].createTime?data.data[i].createTime.substr(0,10):'';
                    var reply = data.data[i].reply ? decodeURIComponent($.parseJSON(data.data[i].reply).text) : '';

                    var replyHtml = '';
                    if(isValid(reply)){
                        replyHtml = '<ul style="padding-left: 30px">' +
                        '<li style="padding-bottom: 0px;">'+
                        '<div class="sface '+cls+'">' +
                        '<img src="'+'../images/admin-b.png'+'" />' +
                        '<p>'+'供货商'+'</p>' +
                        '</div>' +
                        '<div class="comment">'+reply+'</div>' +
                        '</li>'+
                        '</ul>';
                    }


                    commentHtml = commentHtml +
                        '<li><div class="comment-block">' +
                            '<div><div class="sface '+cls+'">' +
                                '<img src="'+img+'" />' +
                                '<p>'+userName+'</p>' +
                            '</div>' +
                            '<div class="comment">'+data.data[i].comment+'</div></div>' +
                            '<div class="create-date">'+createDate+'</div></div>' +
                            replyHtml+
                        '</li>';
                }
                $('.shop-pl .plist').empty().html(commentHtml);
            }
        });
    }
    renderComments();

    //发表评论
    $('.pl-hd .btn').on(base_event,function(){
        var _this = $(this);
        var _val = _this.prev('.icon').find('input').val();
        publishComment(product_id,_val, function (data) {
            if(data.resultCode == 0){
                _this.prev('.icon').find('input').val('');
                renderComments();
            }
        });
    });

  /*  $('a').delegate('.comment','click', function () {
       turnToComment();
    });*/

});