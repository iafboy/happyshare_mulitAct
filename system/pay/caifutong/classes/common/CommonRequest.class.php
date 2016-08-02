<?php
//---------------------------------------------------------
//��������࣬������ز���������
//---------------------------------------------------------

require_once("Constants.class.php");
require_once("SDKRuntimeException.class.php");
require_once("util/CommonUtil.php");
require_once("util/MD5SignUtil.php");
class CommonRequest {
	var $SIGN_TYPE = "sign_type";
	var $SERVICE_VERSION = "service_version";
	var $INPUT_CHARSET = "input_charset";
	var $APPID = "appid";
	var $SIGN_KEY_INDEX = "sign_key_index";
	var $SIGN = "sign";
	
	var $SANDBOX_ADDRESS = "https://sandbox.tenpay.com/api";
	var $API_ADDRESS = "https://api.tenpay.com";
	
	//֧����Ե�ַ
	var $PAY_OPPOSITE_ADDRESS = "/gateway/pay.htm";
	//������ѯ��Ե�ַ
	var $NORMALQUERY_OPPOSITE_ADDRESS = "/gateway/normalorderquery.xml";
	//֪ͨ��֤��Ե�ַ
	var $VERIFY_NOTIFY_OPPOSITE_ADDRESS = "/gateway/verifynotifyid.xml";
	
	var $secretKey;
	var $inSandBox = false;
	
	var $timeout = 10000;
	//����
	var $parameters = array();
	
	function __construct($secretKey) {
		$this->secretKey = $secretKey;
	}
	
	
	protected function genParaStr(){
		try {
			if (null == $this->getAppid()) {
				throw new SDKRuntimeException("appid����Ϊ�գ�" . "<br>");
			}
			
			if (null == $this->getSecretKey()) {
				throw new SDKRuntimeException("��Կ����Ϊ�գ�" . "<br>");
			}
			$commonUtil = new CommonUtil();
			ksort($this->parameters);
			$unSignParaString = $commonUtil->formatQueryParaMap($this->parameters, false);
			$paraString = $commonUtil->formatQueryParaMap($this->parameters, true);

			$md5SignUtil = new MD5SignUtil();
			return $paraString . "&sign=" . $md5SignUtil->sign($unSignParaString,$commonUtil->trimString($this->getSecretKey()));
		}catch (SDKRuntimeException $e)
		{
			//echo $e->errorMessage();
			die($e->errorMessage());
		}

	}
	/**
	*��ȡҵ�����ֵ
	*/
	function getParameter($parameter) {
		return $this->parameters[$parameter];
	}
	
	/**
	*����ҵ�����ֵ
	*/
	function setParameter($parameter, $parameterValue) {
		$this->parameters[CommonUtil::trimString($parameter)] = CommonUtil::trimString($parameterValue);
	}
	
	function getTimeout(){
		return $this->timeout;
	}
	
	function setTimeout($timeout){
		$this->timeout = $timeout;
	}
	
	function getSignType(){
		return $this->getParameter($this->SIGN_TYPE);
	}
	
	function setSignType($signType){
		$this->setParameter($this->SIGN_TYPE, $signType);
	}
	
	function getServiceVersion(){
		return $this->getParameter($this->SERVICE_VERSION);
	}
	
	function setServiceVersion($serviceVersion){
		$this->setParameter($this->SERVICE_VERSION, $serviceVersion);
	}
	
	function getInputCharset(){
		$charSet = $this->getParameter($this->INPUT_CHARSET);
		if(null == $charSet){
			$constants = new Constants();
			// Ĭ��ΪGBK
			$charSet = $constants->DEFAULT_CHARSET;
		}
		return $charSet;
	}
	
	function setInputCharset($inputCharset){
		$this->setParameter($this->INPUT_CHARSET, $inputCharset);
	}
	
	function getSign(){
		return $this->getParameter($this->SIGN);
	}
	
	function setSign($sign){
		$this->setParameter($this->SIGN, $sign);
	}
	
	function getAppid(){
		return $this->getParameter($this->APPID);
	}
	
	/**
	 * ����Ӧ��ID
	 * 
	 * @param appid
	 *            Ӧ��ID
	 */
	function setAppid($appid){
		$this->setParameter($this->APPID, $appid);
	}
	
	function getSignKeyIndex(){
		return $this->getParameter($this->SIGN_KEY_INDEX);
	}
	
	function setSignKeyIndex($signKeyIndex){
		$this->setParameter($this->SIGN_KEY_INDEX, $signKeyIndex);
	}
	
	/**
	 * ��ȡ��Կ
	 */
	function getSecretKey(){
		return $this->secretKey;
	}
	/**
	 * ������Կ
	 * 
	 * @param secretKey
	 *            ��Կ
	 */
	function setSecretKey($secretKey){
		$this->secretKey = $secretKey;
	}
	
	/**
	 * ��ȡ�Ƿ���ɳ�价��
	 */
	function isInSandBox() {
		return $this->inSandBox;
	}
	
	/**
	 * �����Ƿ���ɳ�价��
	 * 
	 * @param inSandBox
	 *            true��ʾ�����͵�ɳ�价����false��ʾ�����͵���ʽ����
	 */
	function setInSandBox($inSandBox) {
		$this->inSandBox = $inSandBox;
	}
	
	/**
	 * ��ȡ������ַ
	 *
	 * @return �ӿڵ�ַ
	 */
	function getDomain(){
		$domain;
		if($this->isInSandBox()) {
			$domain = $this->SANDBOX_ADDRESS;
		}else{
			$domain = $this->API_ADDRESS;
		}
		return $domain;
	}

	protected function send(){
		
	}

}

?>