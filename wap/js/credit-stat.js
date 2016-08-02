/**
 * Created by Samuel on 2016/2/20.
 */


$(function () {
    var shareCode = getQueryString('shareCode');
    var productId = getQueryString('product_id');
    var url = getUrl();

    /**
     * 0. product 1.register
     */
    function shareClickStat(){
        var type = '';
        if((url+'').indexOf('register.html') != -1){
            type = 1;
        }else if((url+'').indexOf('hotshop.html') != -1){
            type = 0;
        }
        if((type==0 || type ==1) && isValid(shareCode) ){
            var postStatURL = baseUrl + 'newwap/APIs/cybershare.php';
            var params = {shareCode:shareCode,type:type,productId:productId};
            if(isValid(productId)){
                params.productId = productId;
            }
            $.getJSON(postStatURL,params, function (data) {});
        }
    }
    shareClickStat();
});