<?php
//---------------------------------------------------------
//֧���ص���Ӧ
//---------------------------------------------------------

require_once("common/CommonResponse.class.php");
class PayResponse extends CommonResponse{
	//֪ͨID
	var $NOTIFYID = "notify_id";
	
	/**
	 * �����request��respone�Լ�secretKey
	 */ 
	function PayResponse($secretKey) {
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
			$this->setParameter($this->RETCODE, null);
		}catch (SDKRuntimeException $e)
		{
			die($e->errorMessage());
		}

		$this->verifySign();
		
	}
	/**
	 * ��֪�Ƹ�ͨ�ص�����ɹ�
	 */
	function acknowledgeSuccess(){
		echo "success";
		return true;
	}
	
	/**
	 * ��ȡ֪ͨ��ѯID
	 * 
	 * @return ֪ͨ��ѯID
	 */
	function getNotifyId(){
		return $this->getParameter("notify_id");
	}
	
}


?>