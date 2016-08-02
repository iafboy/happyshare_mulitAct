<?php

//---------------------------------------------------------
//֧���ɹ��ص�����,�Ƹ�ͨ��̨���ô˵�ַ
//---------------------------------------------------------

require_once("./classes/PayResponse.class.php");
require_once("tenpay_config.php");


/* ����֧�����������Ӧ����֧����ת�ӿ�Ϊ�첽���أ��û��ڲƸ�ͨ���֧���󣬲Ƹ�ͨͨ���ص�return_url��notify_url��Ƹ�ͨAPP����֧������� */
$resHandler = new PayResponse($key);
//��ȡ֪ͨid:֧�����֪ͨid��֧���ɹ�����֪ͨid��Ҫ��ȡ������ϸ������ô�ID����֪ͨ��֤�ӿڡ�
echo $resHandler->getNotifyId();

// ��֪�Ƹ�֪ͨͨ���ͳɹ����粻�������д���ᵼ�²Ƹ�ͨ��ͣ��֪ͨ�Ƹ�ͨapp������ͣ����òƸ�ͨapp��notify_url����֪ͨ
$resHandler->acknowledgeSuccess();
// ��ʼ֪ͨ��֤�����巽����ο�notify_query.php�Ľ���


?>