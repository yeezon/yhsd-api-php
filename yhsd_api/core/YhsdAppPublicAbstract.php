<?php
/**
 * 公有应用App
 */
require_once 'YhsdApiBase.php';
require_once 'YhsdApiHttp.php';


abstract class YhsdAppPublicAbstract extends YhsdApiBase {


	protected $_code;
	protected $_redirect_uri;

	//设置店铺授权回调网址
	abstract function get_shop_redirect_uri();
	//获取店铺授权回调参数code
	abstract function get_shop_code();


	public function get_code()
	{
		return $this->_code = $this->_code?:$this->get_shop_code();
	}
	public function set_code($value) {
		$this->_code = $value;
	}

	public function get_redirect_uri(){
		return $this->_redirect_uri = $this->_redirect_uri?:urlencode($this->get_shop_redirect_uri());
	}

	public function set_redirect_uri($value) {
		$this->_redirect_uri = $value;
	}

	public function setAppScope($value)
	{
		 return $this->app_scope = str_replace(array(PHP_EOL,' ',), '', $value);
	}

	/**
	 * 获取token
	 * @return string
	 */
	public function get_token() {

		if (empty($this->_token)) {
			$this->_token = $this->get_app_token()?:$this->generate_token($this->code,$this->redirect_uri);
		}
		return $this->_token;
	}



	/**
	 * 通过app secret和友好速搭回传的参数，验证当前请求是否来自于友好速搭
	 * @param array $params 获取到的所有参数
	 * @return bool true表示验证正确，false表示错误
	 * @throws InvalidArgumentException
	 */
	public function hmac_verify(array $params = [])
	{
		$this->validate('app_secret');
		$secret = $this->app_secret;
		if (!is_array($params) || !isset($params['hmac'])) throw new InvalidArgumentException('参数不正确');
		//获取hmac并从数组中销毁
		$hmac = $params['hmac'];
		unset($params['hmac']);
		//重新生成hmac
		$digest = YhsdApiHelper::generate_hmac($params,$secret);
		//返回比较结果
		return $hmac == $digest;
	}

	/**
	 * 生成用户授权的跳转网址，便于应用执行跳转
	 * @param string $redirect_uri 应用的跳转地址
	 * @param string $shop_key 友好速搭安装应用的店铺唯一key
	 * @param string $state 自定义的参数
	 * @return string 用户授权的跳转网址
	 * @throws Exception
	 */
	public function authorize_url($redirect_uri, $shop_key, $state = '') {
		$this->validate();
		if (empty($this->app_scope)) throw new Exception('未设置scope');
		$params = array(
			'response_type' => 'code',
			'client_id' => $this->app_key,
			'shop_key' => $shop_key,
			'scope' => $this->app_scope,
			'redirect_uri' => $this->redirect_uri,
			'state' => $state,
		);

		$params_str = http_build_query($params);

		$url = http_build_url($this->auth_url,array('query'=>$params_str));
		return $url;

	}

	/**
	 * 通过友好速搭分发的code获取店铺访问token方法
	 * @return mixed
	 * @throws Exception
     */
	protected function _generate_token()
	{
		$code = trim($this->code);
		$redirect_uri = trim($this->redirect_uri);
		if (!$code || !$redirect_uri) throw new Exception('未设置店铺code或回调网址');
		$req_body = array(
			"grant_type" => 'authorization_code',
			"code" => $code,
			"client_id" => $this->app_key,
			"redirect_uri" =>$redirect_uri,
		);
		$header = array();
		return $this->exec_token($header,$req_body);
	}

	/**
	 * 生成token
	 * @param $code 店铺授权确认后返回的code
	 * @param $redirect_uri 店铺授权时设置的网址
	 * @return string
	 */
	public function generate_token($code,$redirect_uri)
	{
		$this->code = $code;
		$this->redirect_uri = $redirect_uri;
		return $this->_generate_token();
	}

	/**
	 *get 接口调用
	 * @param string $url 需要访问的api接口的地址
	 * @param string $token token
	 * @return array 返回结果为数组,包含：
	 * code :200
	 * body hash 数据
	 * header hash 数据
	 */
	public function get($url,$token = '') {
		$this->token = $token?:$this->token;
		if (!$this->token) throw new InvalidArgumentException('token未设置');
		return $this->exec('get',$url);
	}

	/**
	 * put 接口调用
	 * @param string @url 需要访问的api接口的地址
	 * @param array $params 参数数组
	 * @param string $token token
	 * @return array 返回结果为数组，包含：
	 * code :200
	 * body hash 数据
	 * header hash 数据
	 */
	public function put($url, $params,$token='') {
		$this->token = $token?:$this->token;
		if (!$this->token) throw new InvalidArgumentException('token未设置');
		return $this->exec('put',$url,$params);
	}


	/**
	 * put 接口调用
	 * @param string @url 需要访问的api接口的地址
	 * @param array $params 参数数组
	 * @param string $token token
	 * @return array 返回结果为数组，包含：
	 * code :200
	 * body hash 数据
	 * header hash 数据
	 */
	public function post($url, array $params,$token='') {
		$this->token = $token?:$this->token;
		if (!$this->token) throw new InvalidArgumentException('token未设置');
		return $this->exec('post',$url,$params);
	}

	/**
	 * delete 接口调用
	 * @param string $url 需要访问的api接口的地址
	 * @param string $token token
	 * @return array 返回结果为数组,包含：
	 * code :200
	 * body hash 数据
	 * header hash 数据
	 */
	public function delete($url,$token='') {
		$this->token = $token?:$this->token;
		if (!$this->token) throw new InvalidArgumentException('token未设置');
		return  $this->exec('delete',$url);
	}




}
