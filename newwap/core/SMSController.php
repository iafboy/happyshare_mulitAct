<?php
class SMSController
{
    private $db;
    private $log;
    public function __construct($registry) {
        $this->db=$registry->get('db');
        $this->log=$registry->get('log');
    }
    public function pushSMS2SoapClient($mobile,$message){
		$http = 'http://api.sms.cn/mt/';		//短信接口
		$uid = 'hjfx2016';							//用户账号
		$pwd = 'hjfx123a';							//密码
		$mobileids	 = $mobile.'1112345666688';
		$time='';
		$mid='';
		$data = array
		(
		'uid'=>$uid,					//用户账号
		'pwd'=>md5($pwd.$uid),			//MD5位32密码,密码和用户名拼接字符
		'mobile'=>$mobile,				//号码
		'content'=> iconv("UTF-8", "GBK//IGNORE",$message),			//内容
		'mobileids'=>$mobileids,		//发送唯一编号
		);
	$re= $this->postSMS($http,$data);			//POST方式提交
		//echo $re;
	if( strstr($re,'stat=100')){
		return "发送成功!";
	}
	else if( strstr($re,'stat=101')){
		return "验证失败! 状态：".$re;
	}
	else {
		return "发送失败! 状态：".$re;
        $p = $client->__soapCall('SendMes',array('parameters' => $param));
    	}
	}
	//POST方式
	function postSMS($url,$data='')
	{
		$row = parse_url($url);
		$host = $row['host'];
		$port = $row['port'] ? $row['port']:80;
		$file = $row['path'];
        $post='';
		while (list($k,$v) = each($data))
		{
			$post .= rawurlencode($k)."=".rawurlencode($v)."&";	//转URL标准码
		}
		$post = substr( $post , 0 , -1 );
		$len = strlen($post);
		$fp = @fsockopen( $host ,$port, $errno, $errstr, 10);
		if (!$fp) {
			return "$errstr ($errno)\n";
		} else {
			$receive = '';
			$out = "POST $file HTTP/1.1\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Content-type: application/x-www-form-urlencoded\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Content-Length: $len\r\n\r\n";
			$out .= $post;
			fwrite($fp, $out);
			while (!feof($fp)) {
				$receive .= fgets($fp, 128);
			}
			fclose($fp);
			$receive = explode("\r\n\r\n",$receive);
			unset($receive[0]);
			return implode("",$receive);
		}
	}

	function pushAdvSMS($mobile,$message){
        header("Content-Type: text/html;charset=utf-8");
        $url='http://121.199.48.186:1210/services/msgsend.asmx/SendMsg?userCode=XXXXX&userPass=XXXXX&DesNo='.$mobile.'&Msg='.$message.'&Channel=0';
        $html = file_get_contents($url);
    }
    function httpSendActiveCodeSMS($mobile,$message){
        $url='http://120.26.69.248/msg/HttpSendSM?account=002002&pswd=Sy123002&mobile='.$mobile.'&msg=您好，您的验证码：'.$message.'&needstatus=true';
        $response=$html = file_get_contents($url);
    }

}