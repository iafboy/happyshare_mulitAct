<?php
/**
 * Created by PhpStorm.
 * User: samuel
 * Date: 1/30/16
 * Time: 5:08 AM
 *
 * This file is to access DB related with Wx Session
 */

define('DICT_KEY_WX_ACCESS_TOKEN','DICT_KEY_WX_ACCESS_TOKEN');
define('DICT_KEY_WX_JS_TICKET','DICT_KEY_WX_JS_TICKET');
define('WX_APP_ID','wx4ba8a02a5ef1d924');
define('WX_SECRETKEY','4f4cc4d554de08b0d7628da3164ee92e');
define('WX_ACCESS_TOKEN_REFRESH_TIME',7200);
define('WX_JS_TICKET_REFRESH_TIME',7200);
define('WX_PRE_TIME_IN_SECONDS',600);
class WxController
{
    private $db;
    private $log;

    static $getAccessTokenURL =
        "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".WX_APP_ID."&secret=".WX_SECRETKEY;
    static $getJsApiTicketURL =
        "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi";
//        "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=ACCESS_TOKEN&type=jsapi";


    public function __construct($registry)
    {
        $this->db = $registry->get('db');
        $this->log = $registry->get('log');
    }

    public function getWxAccessToken(){
        $sql = "select *,now() as curtime from mcc_wx_config where key_name=".to_db_str(DICT_KEY_WX_ACCESS_TOKEN);
        $res = $this->db->querySingleRow($sql);
        $accessToken = $res['key_value'];
        $remain_time = strtotime($res['last_update_time'])+WX_ACCESS_TOKEN_REFRESH_TIME - strtotime($res['curtime']);
        if($remain_time < WX_PRE_TIME_IN_SECONDS || !is_valid($accessToken)){
            // update access token
            $json = file_get_contents(self::$getAccessTokenURL);
            $json = json_decode($json);
            $newAccessToken = $json->access_token;
            $success = self::updateWxAccessToken($newAccessToken);
            if($success==true){
                $accessToken =  $newAccessToken;
            }else{
                throw new Exception('Get Wx Access Token Error!');
            }
        }
        return $accessToken;
    }

    public function updateWxAccessToken($accessToken){
        if(!is_valid($accessToken)){
            throw new Exception('Access Token Invalid Error!');
        }
        $sql = 'update '.getTable('wx_config').' set key_value = '.to_db_str($accessToken)
            .' , last_update_time = now() '
            .' where key_name = '.to_db_str(DICT_KEY_WX_ACCESS_TOKEN);
        $success = $this->db->executeSql($sql);
        if($success != true){
            return false;
        }
        return true;
    }
    public function getWxJsTicket(){
        $sql = "select *,now() as curtime from mcc_wx_config where key_name=".to_db_str(DICT_KEY_WX_JS_TICKET);
        $res = $this->db->querySingleRow($sql);
        $jsTicket = $res['key_value'];
        $remain_time = strtotime($res['last_update_time'])+WX_JS_TICKET_REFRESH_TIME - strtotime($res['curtime']);
        if($remain_time < WX_PRE_TIME_IN_SECONDS || !is_valid($jsTicket)){
            // update JS Ticket
            $accessToken = self::getWxAccessToken();
            if(!is_valid($accessToken)){
                throw new Exception('Invalid Access Token Error!');
            }
            $json = file_get_contents(self::$getJsApiTicketURL.'&access_token='.$accessToken);
            $json = json_decode($json);
            $newJsTicket = $json->ticket;
            $success = self::updateWxJsTicket($newJsTicket);
            if($success==true){
                $jsTicket =  $newJsTicket;
            }else{
                throw new Exception('Get Wx Access Token Error!');
            }
        }
        return $jsTicket;
    }

    public function updateWxJsTicket($jsTicket){
        if(!is_valid($jsTicket)){
            throw new Exception('JS Ticket Invalid Error!');
        }
        $sql = 'update '.getTable('wx_config').' set key_value = '.to_db_str($jsTicket)
            .' , last_update_time = now() '
            .' where key_name = '.to_db_str(DICT_KEY_WX_JS_TICKET);
        $success = $this->db->executeSql($sql);
        if($success != true){
            return false;
        }
        return true;
    }
/*
 * wx.config({
    debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: '', // 必填，公众号的唯一标识
    timestamp: , // 必填，生成签名的时间戳
    nonceStr: '', // 必填，生成签名的随机串
    signature: '',// 必填，签名，见附录1
    jsApiList: [] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });
*/
    public function signature($url){
        if(!is_valid($url)){
            return [];
        }
        $noncestr = getRandomStr(32);
        $jsapi_ticket = $this->getWxJsTicket();
        $timestamp = time();
        $str  = 'jsapi_ticket='.$jsapi_ticket.'&noncestr='.$noncestr.'&timestamp='.$timestamp.'&url='.$url;
        $signature = sha1($str);
        $json = [
            'nonceStr'=>$noncestr,
            'signature' => $signature,
            'timestamp'=>$timestamp,
            'appId'=>WX_APP_ID
        ];
        return $json;
    }


}