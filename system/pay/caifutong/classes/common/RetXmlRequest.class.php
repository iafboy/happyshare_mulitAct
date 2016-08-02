<?php
//---------------------------------------------------------
//����xml���ݵ�������
//---------------------------------------------------------

require_once("CommonRequest.class.php");
require_once("util/HttpClientUtil.php");
require_once("util/XmlParseUtil.php");
class RetXmlRequest extends CommonRequest {
	
	//��ȡurl
	function  getURL($opposite_address){
		$paraString = $this->genParaStr();
		$domain = $this->getDomain();
		return $domain . $opposite_address . "?" . $paraString;
	}
	
	function retXmlHttpCall($opposite_address){
		
		$queryXml = null;
		$objH = new HttpClientUtil();
		
		try {
		    $queryXml = $objH->httpClientCall($this->getURL($opposite_address),$this->getInputCharset());
		} catch (Exception $e) {
			throw new SDKRuntimeException("http����ʧ��:" + $e.getMessage());
		}catch (SDKRuntimeException $e)
		{
			die($e->errorMessage());
		}
		$xmlParse = new XmlParseUtil();
		return $xmlParse->openapiXmlToMap($queryXml,$this->getInputCharset());
	}
}


?>