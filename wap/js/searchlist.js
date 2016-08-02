/**
 * Created by 欣 on 2016/1/17.
 */


$(function(){
    //补充的搜索页面，里面数据调用的是热门商品的接口，请替换接口
    var key = decodeURI(window.location.href);
    function UrlSearch(){
       var name,value;
       var str=location.href; //取得整个地址栏
       var num=str.indexOf("?")
       str=str.substr(num+1); //取得所有参数   stringvar.substr(start [, length ]

       var arr=str.split("&"); //各个参数放到数组里
       for(var i=0;i < arr.length;i++){
        num=arr[i].indexOf("=");
        if(num>0){
         name=arr[i].substring(0,num);
         value=arr[i].substr(num+1);
         this[name]=value;
         }
        }
    }
    var Request=new UrlSearch(); //实例化
    var hotshopURL = baseUrl + 'newwap/APIs/index/search.php?key='+decodeURI(Request.key);
    var buyshopURL = baseUrl + 'newwap/APIs/shoppingCart/addProduct2Cart.php';
    $('.header .search-cont').show();
    $('.header .search-cont input').val(decodeURI(Request.key));
    //热门商品列表
    function showShopList(hotshopURL){
        var hotshopHtml = '';
        $.getJSON(hotshopURL,function(data){
            if(data.resultCode == 0){
                if(data.data && data.data.length > 0){
                    for(var i = 0;i<data.data.length;i++){
                        var money = parseInt(data.data[i].money);
                        var oldmoney = parseInt(data.data[i].scj);
                        hotshopHtml = hotshopHtml+
                            '<li data-id="'+data.data[i].id+'">' +
                            '<div class="spic">' +
                            '<a href="hotshop.html?product_id='+data.data[i].id+'"><img src="'+data.data[i].topic+'" /></a>' +
                            '</div>' +
                            '<div class="hinfo">' +
                            '<h2><a href="hotshop.html?product_id='+data.data[i].id+'">'+data.data[i].title+'</a></h2>' +
                            '<p class="ghs m9">供货商：'+data.data[i].supplier_name+'</p>' +
                            '<p class="jifen">+'+data.data[i].jifen+'积分　<i style="color:#999999;">销量：</i>1000</p>' +
                            '<div class="toolbar">' +
                            '<a href="javascript:void(0)" class="buy">购买</a>' +
                            '<div class="mcon">' +
                            '<span class="money"><i>￥</i>'+money+'</span>' +
                            '<span class="oldmoney">市场价￥'+oldmoney+'</span>' +
                            '</div>' +
                            '</div>' +
                            '</div></li>';
                    }
                }
                $('.hotshop-cont .hlist').html(hotshopHtml);
            }else{
                $('.nosearch').show();
            }
        });
    }
    showShopList(hotshopURL);

    //添加到购物车
    $('body').on('tap','.hinfo .buy',function(){
        var _productId = $(this).parents('li').attr('data-id');
        $.getJSON(buyshopURL,{
            customerId : 1,
            productId : _productId
        },function(data){
            if(data.resultCode == 0){
                $('body').append('<div class="tipstext">添加购物车成功</div>');
                setTimeout(function(){
                    $('.tipstext').fadeOut(function(){
                        $('.tipstext').remove();
                    });
                },1500);
            }else{
                console.log(data.resultMsg);
            }
        });
    });

    //点取消返回上一页
    $('body').on('tap','.search-btn',function(){
        history.go(-1);
    });
});