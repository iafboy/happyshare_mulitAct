<?php
//---------------------------------------------------------
//֧������
//---------------------------------------------------------

require_once("common/CommonRequest.class.php");
class PayRequest extends CommonRequest {
	
	
	/**
	 * ����֧����ת����
	 */
	function getURL(){
		$paraString = $this->genParaStr();
		$domain = $this->getDomain();
		return $domain . $this->PAY_OPPOSITE_ADDRESS . "?" . $paraString;
	}
	
	function send(){
		return null;
	}
	
}


?>