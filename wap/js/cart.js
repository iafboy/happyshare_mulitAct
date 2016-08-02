/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/9/21 23:43
 */

$(function () {
    var xgListHtml = '';
    getMyCartInfo(function (data) {
        if (data.resultCode == 0) {
            for (var i = 0; i < data.data.length; i++) {
                xgListHtml = xgListHtml +
                    '<div class="cart-bd">' +
                    '<div class="shop">' +
                    '<h3><span class="on">供货商：' + data.data[i].ghsName + '</span></h3>' +
                    '</div>' +
                    '<ul class="items">';
                var ghsId = data.data[i].ghsId;
                for (var x = 0; x < data.data[i].lists.length; x++) {
                    var num = data.data[i].lists[x].num;
                    xgListHtml = xgListHtml +
                        '<li class="on" data-supplier-id="' + ghsId + '" data-product-id="' + data.data[i].lists[x].buylink + '">' +
                        '<div class="selbtn"></div>' +
                        '<div class="spic"><img src="' + data.data[i].lists[x].src + '" /></div>' +
                        '<div class="info">' +
                        '<h2>' + data.data[i].lists[x].title + '</h2>' +
                        '<p class="jiage"><span>￥' + data.data[i].lists[x].money + '</span><em class="jf">' + data.data[i].lists[x].jifen + '积分</em></p>' +
                        '<div class="numbox">' +
                        '<a class="sub" href="javascript:void(0)">-</a><div class="num"><input class="num-counter" type="number" value="' + num + '">' +
                        '</div>' +
                        '<a class="add" href="javascript:void(0)">+</a>' +
                        '</div>' +
                        '</div></li>';
                }
                //xgListHtml += '</ul><div class="extra"><p>到<i>'+data.data[i].address+'</i>'+data.data[i].tips1+'</p>';
                xgListHtml += '</ul><div class="extra"><p>' + data.data[i].tips1 + '</p>';
                if (data.data[i].tips2 != '') {
                    xgListHtml += '<p>' + data.data[i].tips2 + '</p>';
                }
                xgListHtml += '</div></div>';
            }
            $('.cart-cont div').eq(0).html(xgListHtml);
            calAmount();
            var cartScroll = new IScroll('#cart-cont');
        }
    });


    $('body').on(base_event, '.numbox .add', function () {
        var _shopid = $(this).parents('li').attr('data-shopid');
        var _num = parseInt($(this).prev('.num').find('.num-counter').val());
        _num++;
        $(this).prev('.num').find('.num-counter').val(_num);
        getCustomerId(function (customerId) {
            $.getJSON(baseUrl + 'newwap/APIs/shoppingCart/changeProductNum.php?customerId=' + customerId + '&productId=' + _shopid + '&num='+_num, function (data) {
                calAmount();
            });
        });
    });
    $('body').on(base_event, '.numbox .sub', function () {
        var _shopid = $(this).parents('li').attr('data-shopid');
        var _num = parseInt($(this).next('.num').find('.num-counter').val());
        if (_num > 0) {
            _num--;
        }
        $(this).next('.num').find('.num-counter').val(_num);
        getCustomerId(function (customerId) {
            $.getJSON(baseUrl + 'newwap/APIs/shoppingCart/changeProductNum.php?customerId=' + customerId + '&productId=' + _shopid + '&num='+_num, function (data) {
                calAmount();
            });
        });

    });

    $('body').on(base_event, '.shop', function () {
        if (!$(this).find('span').hasClass('on')) {
            $(this).find('span').addClass('on');
            $(this).parents('.cart-bd').find('.items li').addClass('on');
        } else {
            $(this).find('span').removeClass('on');
            $(this).parents('.cart-bd').find('.items li').removeClass('on');
            $('.cart-sum .chk').removeClass('on');
        }
        calAmount();
    });

    $('body').on(base_event, '.items li .selbtn', function () {
        var _this = $(this);
        _this.parent('li').toggleClass('on');
        if (_this.parents('.items').find('li.on').length == _this.parents('.items').find('li').length) {
            _this.parents('.cart-bd').find('.shop span').addClass('on');
        } else {
            _this.parents('.cart-bd').find('.shop span').removeClass('on');
            $('.cart-sum .chk').removeClass('on');
        }
        calAmount();
    });

    function calAmount() {
        var amount = 0;
        // calculate money
        $('.items li').each(function () {
            if ($(this).hasClass('on')) {
                var jiage = parseFloat($($(this).find('p.jiage > span')[0]).html().replace('￥', ''));
                var num = parseInt($(this).find('.numbox .num .num-counter').val());
                amount += jiage * num;
            }
        });
        $('.cart-sum i.money').empty().append('￥' + amount);
    }

    $('body').on(base_event, '.cart-sum .chk', function () {
        if (!$(this).hasClass('on')) {
            $(this).addClass('on');
            $('.cart-cont .shop span').addClass('on');
            $('.cart-cont .items li').addClass('on');
        } else {
            $(this).removeClass('on');
            $('.cart-cont .shop span').removeClass('on');
            $('.cart-cont .items li').removeClass('on');
        }
        calAmount();
    });

    $('.buy').on(base_event, function () {
        //var total = parseInt($('.cart-sum i.money').text().replace('￥',''));
        //$('#buyshop').find('input[name="txnAmt"]').val(total*100);
        //$('#buyshop').submit();
        $sels = $('.items li.on');
        if ($sels.length == 0) {
            return errTips('未选择商品！');
        }
        var productIds = '';
        var nums = '';
        var supplierId = '';
        var i = 0;
        var douplicate = false;
        $sels.each(function () {
            var productId = $(this).data('product-id');
            var num = $(this).find('.num .num-counter').val();
            var _supplierId = $(this).data('supplier-id');
            if (num == 0) {
                return true;
                ;
            }
            if (i++ == 0) {
                supplierId = _supplierId;
            } else {
                if (supplierId != _supplierId) {
                    douplicate = true;
                    //return false;
                }
            }
            productIds = productIds + productId + ',';
            nums = nums + num + ',';

        });
        if(nums==0){
            return false;
        }
        //if(douplicate){
        //    return errTips('一次只能选择一个供货商的产品！');
        //}
        if (!isValid(productIds) || !isValid(nums) || nums == 0) {
            console.debug('productIds: '+productIds);
            console.debug('nums: '+nums);
            return errTips('未选择商品！');
        }
        productIds = productIds.substr(0, productIds.length - 1);
        nums = nums.substr(0, nums.length - 1);
        window.location = 'to_pay_1.html?productIds=' + productIds + '&nums=' + nums + '&from=cart';
    });
});
