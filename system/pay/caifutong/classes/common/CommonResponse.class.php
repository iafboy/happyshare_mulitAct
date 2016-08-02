<?php
//---------------------------------------------------------
//��Ӧ�����࣬������ز���������
//---------------------------------------------------------

include_once("SDKRuntimeException.class.php");
include_once("util/CommonUtil.php");
include_once("util/MD5SignUtil.php");
class CommonResponse {
	var $RETCODE = "retcode";
	var $RETMSG = "retmsg";
	var $TRADE_STATE = "trade_state";
	var $TRADE_STATE_SUCCESS = "0";
	/** ��Կ */
	var $secretKey;
	var $parameters = array();
	
	function __construct($paraMap,$secretKey) {
		try {
			unset($this->parameters);
			$this->secretKey = $secretKey;
			$this->parameters = $paraMap;
			if(!$this->isRetCodeOK()){
				throw new SDKRuntimeException("��������쳣:" . $this->getPayInfo(). "<br>");
			}
			
			$this->verifySign();
		}catch (SDKRuntimeException $e)
		{
			die($e->errorMessage());
		}
		
	}
	
	function CommonResponse() {
	}
	
	protected function verifySign(){
		try {
		if (null == $this->parameters) {
			throw new SDKRuntimeException("parametersΪ��!". "<br>");
		}
		
		$sign = $this->getParameter("sign");
		if (null == $sign) {
			throw new SDKRuntimeException("signΪ��!". "<br>");
		}
		$charSet = $this->getParameter("input_charset");
		if (null == $charSet) {
			$charSet = Constants::DEFAULT_CHARSET;
		}
		$signStr = CommonUtil::formatQueryParaMap($this->parameters, false);
		if (null == $this->secretKey) {
			throw new SDKRuntimeException("ǩ��keyΪ��!". "<br>");
		}
		if(!MD5SignUtil::verifySignature($signStr,$sign,$this->secretKey)){
			throw new SDKRuntimeException("����ֵǩ����֤ʧ��!". "<br>");
		}
		return true;
		}catch (SDKRuntimeException $e)
		{
			die($e->errorMessage());
		}
	}
	/**
	 * ��ȡ��Կ
	 */
	function getSecretKey(){
		return $this->key;
	}
	/**
	 * ������Կ
	 * 
	 * @param secretKey
	 *            ��Կ
	 */
	function setSecretKey($secretKey){
		$this->key = $secretKey;
	}
	/**
	*��ȡ����ֵ
	*/
	function getParameter($parameter) {
		return $this->parameters[$parameter];
	}
	
	/**
	*���ò���ֵ
	*/
	function setParameter($parameter, $parameterValue) {
		$this->parameters[$parameter] = $parameterValue;
	}
	
	/**
	 * �ӿڵ����Ƿ�ɹ�
	 */
	function isRetCodeOK(){
		return "0"==$this->getRetCode();
	}
	
	function isPayed(){
		return $this->isRetCodeOK() && $this->TRADE_STATE_SUCCESS == $this->getParameter($this->TRADE_STATE);
	}
	/**
	 * ��ȡ�ӿڷ�����
	 */
	function getRetCode(){
		return $this->getParameter($this->RETCODE);
	}
	/**
	 * ��ȡ������Ϣ
	 */
	function getPayInfo(){
	    $info = $this->getParameter($this->RETMSG);
		if(null == CommonUtil::trimString($info) && !$this->isPayed()){
		   $info = "������δ֧���ɹ�";
		}
		return $info;
	}
	
	
}


?>