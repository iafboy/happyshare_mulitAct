<?php
//---------------------------------------------------------
//�����¼״̬ ��ȡ�û���Ϣ ��СǮ����ת��Ӧ��ʱ��ʹ��
//---------------------------------------------------------


require_once("common/SDKRuntimeException.class.php");
require_once("common/CommonResponse.class.php");
class ShareLoginState extends CommonResponse {
	//�û�ID
	var $USER_ID = "user_id";	
	
	function ShareLoginState($secretKey) {
		try {
			unset($this->parameters);
			$this->secretKey = $secretKey;
			/* GET */
			foreach($_GET as $k => $v) {
				$this->setParameter($k, $v);
			}
			/* POST */
			foreach($_POST as $k => $v) {
				$this->setParameter($k, $v);
			}
			$this->setParameter($this->RETCODE, "0");
			if(!$this->isRetCodeOK()){
				throw new SDKRuntimeException("��������쳣:" . $this->getPayInfo(). "<br>");
			}
			if(null == $this->getUserId()){
				throw new SDKRuntimeException("�Ƹ�ͨ�û�idδ����!<br>");
			}
		}catch (SDKRuntimeException $e)
		{
			die($e->errorMessage());
		}
		
		$this->setParameter($this->RETCODE, null);
		$this->verifySign();
	}
	
	function getUserId() {
		return $this->getParameter($this->USER_ID);
	}
}
?>