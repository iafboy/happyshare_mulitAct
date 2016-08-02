/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/10/1 10:04
 */

$(function(){
    var focusURL = baseUrl + 'newwap/APIs/getFocus.php?pws_id=4';
    var actListURL = baseUrl + 'newwap/APIs/activity/getExcActivities.php';

    //焦点图
    var focusHtml = '';
    $.getJSON(focusURL,function(data){
        if(data.resultCode == 0){
            for(var i = 0;i<data.data.length;i++){
                focusHtml += '<div class="swiper-slide"><a href="'+data.data[i].link+'"><img width="100%" height="100%" src="'+data.data[i].src+'" /></a></div>';
            }
        }
        $('.swiper-wrapper').html(focusHtml);
        var swiper = new Swiper('.swiper-container', {
            pagination: '.swiper-pagination',
            paginationClickable: true
        });
    });

    //展示列表
    var spHtml = '';
    $.getJSON(actListURL,function(data){
        if(data.resultCode == 0){
            for(var i = 0;i<data.data.length;i++){
                var act = data.data[i];
                var link = '';
                if(act.type==0 && act.subType == 0){
                    // special price
                    link = 'act_special_price.html?promotionId='+act.pid;
                }else if(act.type==0 && act.subType == 1){
                    //special score
                    link = 'act_special_score.html?promotionId='+act.pid;
                }else if(act.type == 1){
                    //free trial
                    link = 'act_free_trial.html?promotionId='+act.pid;
                }
                spHtml += '<li><a href="'+link+'"><img width="100%" height="100%" src="'+data.data[i].image+'" /><p>'+data.data[i].promotion_name+'</p></a></li>';
            }
        }
        $('.showpic-cont .slist').html(spHtml);
    });
});