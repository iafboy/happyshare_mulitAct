<?php
//---------------------------------------------------------
//����֧������ʾ��,��������֧������
//---------------------------------------------------------

require_once("./classes/PayRequest.class.php");
require_once("tenpay_config.php");


$curDateTime = date("YmdHis");
$randNum = rand(1000, 9999);
  /* �̼ҵĶ����� */
$out_trade_no = $curDateTime . $randNum;

/* ����֧��������� */
$reqHandler = new PayRequest($key);

// ������ɳ�������У���ʽ����������Ϊfalse
$reqHandler->setInSandBox(true);
//----------------------------------------
//����ҵ��������Ʋο�����ƽ̨sdk�ĵ�-PHP
//----------------------------------------

// ���òƸ�ͨappid: �Ƹ�ͨappע��ʱ���ɲƸ�ͨ����
$reqHandler->setAppid($appid);

// �����̻�ϵͳ�����ţ��Ƹ�ͨAPPϵͳ�ڲ��Ķ�����,32���ַ��ڡ��ɰ�����ĸ,ȷ���ڲƸ�ͨAPPϵͳΨһ
$reqHandler->setParameter("out_trade_no", $out_trade_no);           

// ���ö����ܽ���λΪ��
$reqHandler->setParameter("total_fee", "1");					

// ����֪ͨurl�����ղƸ�ͨ��̨֪ͨ��URL���û��ڲƸ�ͨ���֧���󣬲Ƹ�ͨ��ص���URL����Ƹ�ͨAPP����֧�������
// ��URL���ܻᱻ��λص�������ȷ��������ҵ���߼�����δ������������·�������磺http://wap.isv.com/notify.asp
$reqHandler->setParameter("notify_url", $notify_url);				

// ���÷���url���û����֧������ת��URL���Ƹ�ͨAPPӦ�ڴ�ҳ���ϸ�����ʾ��Ϣ�������û����֧����Ĳ�����
// �Ƹ�ͨAPP��Ӧ�ڴ�ҳ������������ҵ������������û�����ˢ��ҳ�浼�¶�δ���ҵ���߼���ɲ���Ҫ����ʧ��
// �������·�������磺http://wap.isv.com/after_pay.asp��ͨ����·��ֱ�ӽ�֧�������Get�ķ�ʽ����
$reqHandler->setParameter("return_url", $return_url);				

// ������Ʒ����:��Ʒ����������ʾ�ڲƸ�֧ͨ��ҳ����
$reqHandler->setParameter("body", "֧������");	            

// �����û��ͻ���ip:�û�IP��ָ�û��������IP�����ǲƸ�ͨAPP������IP
$reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);
// **********************end*************************

//֧�������URL
$reqUrl = $reqHandler->getURL();


?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=gbk">
	<title>�Ƹ�ͨ����ƽ̨֧����ʾ</title>
</head>
<body>
<br/><a href="<?php echo $reqUrl ?>" target="_blank">ȥ����</a>
</body>
</html>
