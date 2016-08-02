/**
 * Created by Samuel on 2016/1/23.
 */


function getOrderStatusText(status){
    if(!status){
        return '';
    }
    status += '';
    switch (status){
        case '0':return '待付款';
        case '1':return '已取消';
        case '2':return '已支付';
        case '3':return '已发货';
        case '4':return '商品退货中'; // 有商品在退货进行中
        //case '5':return '退货审核通过';
        //case '6':return '退货中';
        //case '7':return '退货成功';
        //case '8':return '退货异常';
        //case '9':return '退货审核不通过';
        case '10':return '订单完成';//确认收货之后的状态 或者 退款成功之后的状态
        case '11':return '交易关闭';//订单删除之后的状态
        case '12':return '部分已发货';
    }
}
function getOrderProductStatusText(status){
    if(!status){
        return '';
    }
    status += '';
    switch (status){
        case '0':return '未发货';
        case '1':return '已发货';
        case '2':return '已收货';
        case '3':return '申请退货';
        case '4':return '退货审核通过';
        case '5':return '退货审核未通过';
        case '6':return '退货成功';
        case '7':return '退货异常';
        case '8':return '货物收到，等待退款';
    }
}

function getShippmentTypeText(status){
    if(!status){
        return '';
    }
    status += '';
    switch (status){
        case '0':return '现货包邮';
        case '1':return '快递直邮';
    }
}

function getReturnGoodStatusText(status){
    if(!status){
        return '';
    }
    status += '';
    switch (status){
        case '0':return '正常订单状态';
        case '1':return '退货申请中';
        case '2':return '退货审核通过';
        case '3':return '退货审核不通过';
        case '4':return '退货：客户已发货';
        case '5':return '退货：供货商已收货，等待退款';
        case '6':return '退货：退货款已经返还';
        case '7':return '退货：退货完成';
        case '8':return '退货：退货关闭';
    }
}
function getRefoundStatusText(status){
    if(!status){
        return '';
    }
    status += '';
    switch (status){
        case '1':return '退货申请';
        case '2':return '退货审核通过';
        case '3':return '退货审核不通过';
        case '4':return '客户已发货';
        case '5':return '供货商已收货，等待退款';
        case '6':return '退货款已经返还';//改成由平台退款，并且将退款金额累加到order表中
        case '7':return '退货完成';
        case '8':return '退货关闭';
    }
    //退货状态：1申请；2可退货；3不可退货 4.客户已发货；5.供货商已收货。 6.退货款已经返还 7.退货完成；8.关闭
}


