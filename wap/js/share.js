/**
 *
 * @authors wawow_haha@live.com
 * @date      2015/9/30 15:55
 */

$(function(){

    var productId = getQueryString('product_id');
    var shareUrl = baseUrl + 'mobile/html/hotshop.html?product_id='+productId+'&shareCode=';
    var shareCaseURL = baseUrl +  'newwap/APIs/product/getProductShareCase.php?productId='+productId;

    var callbackURL = window.location;

    detectLoginAndDeal(function (customerId) {

        $.getJSON(shareCaseURL,{customerId:customerId}, function (data) {
            if(data.data){
                var sharecase = data.data;
                shareUrl += sharecase.shareCode;
                function qqInit(){
                    var qqShareUrl = '';
                    var p = {
                        url:shareUrl,
                        targetUrl:callbackURL,
                        successUrl:callbackURL,
                        callbackUrl:callbackURL,
                        showcount:'0',/*是否显示分享总数,显示：'1'，不显示：'0' */
                        desc:sharecase.title,/*默认分享理由(可选)*/
                        summary:sharecase.memo,/*分享摘要(可选)*/
                        title:sharecase.title,/*分享标题(可选)*/
                        site:'好就分享',/*分享来源 如：腾讯网(可选)*/
                        pics:sharecase.image /*分享图片的路径(可选)*/
                    };
                    var s = [];
                    for(var i in p){
                        s.push(i + '=' + encodeURIComponent(p[i]||''));
                    }
                    qqShareUrl += ['http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?',s.join('&')].join('');
                    $('.share-list li.qq a').data('redirecturl',qqShareUrl);
                }
                qqInit();

                $('body').on(base_event,'.share-list li a', function () {
                    var that = this;
                    checkLoginAndDeal(function(customerId){

                        var postShareURL = baseUrl + 'newwap/APIs/product/publishShareReport.php';
                        $.getJSON(postShareURL,{customerId:customerId,productId:productId}, function (data) {
                            if(data.resultCode == 0){
                                if($(that).parent().hasClass('weixin')){
                                    // in wechat
                                    if(is_in_wechat == 1){
                                        try{
                                            return errTips('请点击右上角进行分享!');
                                        }catch(e){
                                            alert('error');
                                        }
                                        // not in wechat
                                    }else{
                                        return errTips('请在微信中打开网页后在重试！');
                                    }
                                }
                                else if($(that).parent().hasClass('renren')){
                                    var rrShareParam = {
                                        resourceUrl : shareUrl,	//分享的资源Url
                                        srcUrl : shareUrl,	//分享的资源来源Url,默认为header中的Referer,如果分享失败可以调整此值为resourceUrl试试
                                        pic : sharecase.image,		//分享的主题图片Url
                                        title : sharecase.title,		//分享的标题
                                        description : sharecase.memo	//分享的详细描述
                                    };
                                    rrShareOnclick(rrShareParam);
                                }
                                else if($(that).parent().hasClass('weibo')){
                                    var appkey = '1573784490';
                                    var url = encodeURIComponent(shareUrl);
                                    var html = 'http://service.weibo.com/share/share.php?' +
                                        'url='+url+'&type=icon&language=zh_cn&appkey='+appkey+'&searchPic=true&style=simple&title='+sharecase.title+'&pic='+sharecase.image;
                                    window.location = html;
                                }
                                else if($(that).parent().hasClass('qq')){
                                    var loc = $(that).data('redirecturl');
                                    window.location = loc;
                                }
                            }else{
                                return errTips('分享失败！');
                            }
                        });
                    });
                });
            }else{

            }
        });

        $('.more-share').empty().append('<a href="shareExperience.html?product_id='+productId+'">更多分享内容</a>');

    });
});