<?php
//---------------------------------------------------------
//�����¼���յ�ǰ�û���Ϣ
//---------------------------------------------------------
require_once("./classes/ShareLoginState.class.php");
require_once("tenpay_config.php");


/* ���������½̬�������ת���Ƹ�ͨAPP��ʱ��ϵͳ����ҵ�ID����APP�������û��������ɡ��û�״̬���µ���ز����� */
$shareLogin = new ShareLoginState($key);

// ��ȡ�û�id
echo $shareLogin->getUserId();

?>