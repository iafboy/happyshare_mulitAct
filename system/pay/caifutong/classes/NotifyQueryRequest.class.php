<?php
//---------------------------------------------------------
//֪ͨ��ѯ����
//---------------------------------------------------------


require_once("common/RetXmlRequest.class.php");
require_once("NotifyQueryResponse.class.php");
class NotifyQueryRequest extends RetXmlRequest{
	
	function send(){
		$respone = new NotifyQueryResponse($this->retXmlHttpCall($this->VERIFY_NOTIFY_OPPOSITE_ADDRESS),$this->getSecretKey());
		return $respone;
	}

}


?>