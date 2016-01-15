<?php
/**
 * 私有应用App
 */
require_once 'YhsdApiBase.php';

class YhsdAppPrivate extends YhsdApiBase {

	/**
	 * 获取token
	 * @return string
	 */
	public function get_token() {

		if (empty($this->_token)) {
			$this->_token = $this->get_app_token()?:$this->generate_token();
		}
		return $this->_token;
	}
	/**
	 * 友好速搭私有App 获取API调用token 方法
	 */
	public function generate_token() {
		$headers = array(
			'Authorization:'. $this->authorization($this->app_key,$this->app_secret),
		);
		$req_body = array(
			"grant_type" => 'client_credentials',
		);
		return $this->exec_token($headers,$req_body);
	}

	/**
	 *get 接口调用
	 * @param string $url 需要访问的api接口的地址
	 * @return array 返回结果为数组,包含：
	 * code :200
	 * body hash 数据
	 * header hash 数据
	 */
	public function get($url) {

		return $this->exec('get',$url);
	}

	/**
	 * put 接口调用
	 * @param string @url 需要访问的api接口的地址
	 * @param array $params 参数数组
	 * @return array 返回结果为数组，包含：
	 * code :200
	 * body hash 数据
	 * header hash 数据
	 */
	public function put($url, $params) {
		return $this->exec('put',$url,$params);
	}


	/**
	 * put 接口调用
	 * @param string @url 需要访问的api接口的地址
	 * @param array $params 参数数组
	 * @return array 返回结果为数组，包含：
	 * code :200
	 * body hash 数据
	 * header hash 数据
	 */
	public function post($url, $params = []) {
		return $this->exec('post',$url,$params);
	}

	/**
	 * delete 接口调用
	 * @param string $url 需要访问的api接口的地址
	 * @return array 返回结果为数组,包含：
	 * code :200
	 * body hash 数据
	 * header hash 数据
	 */
	public function delete($url) {
		return  $this->exec('delete',$url);
	}

}
