<?php

require_once 'YhsdApiObjct.php';
require_once 'YhsdApiHttp.php';
require_once 'YhsdApiMultiPass.php';
require_once 'YhsdApiHelper.php';
/**
 * 友好速搭Api公共部分.
 *
 * Class YhsdApiConfigBase
 * @property string $app_key
 * @property string $app_secret
 * @property string $auth_url
 * @property string $token_url
 * @property string $api_url
 * @property string $api_version
 * @property string $app_scope
 * @property string $call_limit_protect
 * @property string $api_real
 * @property string $token
 * @property string $redirect_uri
 * @property string $code
 * @property string $app_token
 * @property string $third_app_key 第三方支持密钥
 */

class YhsdApiBase extends YhsdApiObject {

	protected $_attributes = array(
		'app_key' => '',
		'app_secret' => '',
		'auth_url' => 'https://apps.youhaosuda.com/oauth2/authorize/',
		'token_url' => 'https://apps.youhaosuda.com/oauth2/token/',
		'api_url' => 'https://api.youhaosuda.com/',
		'api_version' => 'v1/',
		'call_limit_protect' => false,
		);
	protected $_token;
	protected $_api_real; //api全路径

//	/**
//	 * 通过友好速搭token_url获取token
//	 * @return string
//	 */
//	abstract public function generate_token() ;

	/**
	 * 获取token
	 * @return string
     */
	public function get_token() {
		return $this->_token;
	}

	/**
	 * 每个应用的Token是不变的，获取token后应永久保存下来。
	 * 子类应该重载此方法提供永久保留的Token，减少token服务器的访问
	 * @return string
     */
	public function get_app_token() {
		return '';
	}
	/**
	 * 设置token
	 * @return string
	 */
	public function set_token($value) {
		$this->_token = $value;
	}

	/**
	 * @return string
     */
	public function get_api_real() {
		$this->_api_real =  http_build_url($this->api_url,array('path'=>$this->api_version),HTTP_URL_JOIN_PATH);
		return $this->_api_real;
	}


	/**
	 * 发起访问
	 * @param string $method
	 * @param string $url
	 * @param array $params
	 * @param array $header
	 * @return array
	 * @throws Exception
     */
	protected function exec($method = 'get', $url, $params = array(), $header = array())
	{
		$this->validate();
		$data = array();
		if (!$this->begin_request()) return $data;
		$header =$this->prepare_header($header);
		if ($params) {
			$params = is_string($params)?$params:json_encode($params,true);
		}
		$url = $this->validate_url($url);
		$method = $this->validate_method($method);

		$data = YhsdApiHttp::$method($url,$header,$params);
		$this->end_request($data);

		return $data;
	}

	/**
	 * @param $method
	 * @return string
	 * @throws Exception
     */
	private function validate_method($method)
	{
		$method = strtolower($method);
		$ok = in_array($method,array('get','post','delete','put'));
		if (!$ok) {
			throw new Exception('无效的请求方式');
		}
		return $method;
	}

	/**
	 * @param array $header
	 * @return array
     */
	protected function prepare_header($header = array()) {
		$pre = array(
			"X-API-ACCESS-TOKEN:".$this->token,
			"Content-Type:application/json",
		);
		$header = array_merge($pre,$header);
		return $header;
	}

	/**
	 * 验证Url合法性
	 * @param $url
	 * @return string
     */
	protected function validate_url($url) {

		if (empty($url) || !(substr($url,0,5) == 'http:' || substr($url,0,6) == 'https:')) {
			$paths = array($this->api_version,$url);
			$path = str_replace('//','/',implode('/',$paths));
			$url = http_build_url($this->api_url,array('path'=>'/'.$path));
		}
		return $url;
	}



	/**
	 * 验证token
	 */
	public function validate_token() {
		return isset($this->token);
	}

	/**
	 * @param array $params
	 * @return string
     */
	protected function build_pramas($params = [])
	{
		return http_build_query($params);
	}

	/**
	 * 验证属性是否设置
	 * 参数：$key
	 * 需要验证的属性名称，如果验证多个属性用','隔开，ex:'app_key,app_secrete',属性名称应包含于$this->_attributes或配置文件数组定义的键值里。
	 * @param string|array $key
	 * @throws Exception
     */
	public function validate($key = '') {

		$keys = !$key?array_keys($this->attributes):explode(',',$key);
		$result = true;

		if (!is_array($keys)) throw new Exception('参数错误');

		foreach ($keys as $key) {
			if (!isset($this->$key) || is_null($this->$key) || $this->$key === '') {
				$result = false;
				break;
			}
		}
		if (!$result) {
			throw new Exception('未配置参数：'.$key);
		}
	}

	/**
	 * @param $ch
     */
	protected function begin_request() {
		return true;
	}

	/**
	 * @param $ch
	 * @param $ret
     */
	protected function end_request($ret) {
		return;
	}

	/**
	 * @param array $header
	 * @param array $req_body
	 * @return mixed
	 * @throws Exception
     */
	protected function exec_token(array $header = array(), $req_body = array())
	{
		$this->validate('app_key,app_secret,token_url');
		$header = array_merge(array(
			"Content-Type:application/x-www-form-urlencoded",
		),$header);

		$data = YhsdApiHttp::post($this->token_url,$header,$req_body);

		list($code,$body,$header)=array_values($data);
		if ($code == 200 && isset($body['token'])) {
			$this->token = $body['token'];
		} else {
			throw new Exception($body['error']);
		}
		return $this->_token;
	}






	/**
	 * 第三方App接入参数生成函数
	 * @param $key
	 * @param array $data
	 * @return string
	 * @throws Exception
     */
	public  function thirdapp_aes_encrypt($key, $data = []) {
		if (empty($key)) throw new Exception('密钥不能为空');
		$data = !is_array($data)?$data: addslashes(json_encode($data));

		$encoder = new YhsdApiMultiPass($key);
		return $encoder->aes_encrypt($data);

	}


	/**
	 * 验证 Webhook 通知来源
	 * @param $params
	 * @param $hmac
	 * @return bool
	 * @throws Exception
     */
	public function webhook_verify($params, $hmac,$secret) {
		$pass = new YhsdApiMultiPass($secret);
		return $pass->verify_sha_256($params,$hmac);
	}

	/**
	 * 友好速搭开放支付回调验证
	 * @param array $data
	 * @param string $hmac
	 * @return bool
	 * @throws Exception
	 */
	public function openpayment_verify($data, $hmac)
	{
		if (!$this->token) throw new Exception("没有获取合法的token");
		$pass = new YhsdApiMultiPass($this->token);
		return $pass->verify_sha_256($data,$hmac);
	}

	/**
	 * 生成验证字符串
	 * @param $key
	 * @param $secret
	 * @return string
     */
	public function authorization($key, $secret){
		$str = $key.":".$secret;
		$encoded = YhsdApiHelper::urlsafe_b64encode($str);
		$basic = "Basic ".$encoded;
		return $basic;
	}



}
