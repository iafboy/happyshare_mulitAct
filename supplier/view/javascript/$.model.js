(function($){
    var debug = true;
    $.model = {
        user:{
            login:function(){

            }
        },
        supplier:{
            changeSupplierStatus:function(url,params,callback){
                ajaxData(url,params,callback);
            }
        },
        reports:{
            calfee: function (url, params, callback) {
                ajaxData(url,params,callback);
            }
        },
        order:{
            getShipment: function (url, params, callback) {
                ajaxData(url,params,callback);
            }
        },
        commonAjax: function (url, params, callback) {
            ajaxData(url,params,callback);
        }

    };
    function ajaxData(url,params,callback){
        $.post(url,params,function(data){
            data = JSON.parse(data);
            if(debug){
                console.log(data);
            }
            if(callback && $.isFunction(callback)){
                callback(data);
            }
        })
    }
})(jQuery);