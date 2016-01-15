<?php
/**
 * 友好速搭API HTTP请求
 */
class YhsdApiHttp {

	/**
	 * GET
	 *
	 * @param   string  $url
	 * @param   array   $data 数据
	 * @param array $header header
	 * @param   int     $timeout 请求超时时间
	 * @return  string
	 */
	public static function get($url, $header=array(), $data = array(),$timeout=30) {
		return self::exec($url,$data,$header,$timeout,'GET');
	}



	/**
	 * POST
	 *
	 * @param   string  $url
	 * @param   array   $data 数据
	 * @param array $header header
	 * @param   int     $timeout 请求超时时间
	 * @return  string
	 */
	public static function  post($url,  $header = array(),$data = array(),$timeout = 30){
		return self::exec($url,$data,$header,$timeout,'POST',false);
	}

	/**
	 * PUT
	 *
	 * @param   string  $url
	 * @param   array   $data 数据
	 * @param array $header header
	 * @param   int     $timeout 请求超时时间
	 * @return  string
	 */
	public static function put($url,  $header = array(),$data = array(),$timeout = 30) {
		return self::exec($url,$data,$header,$timeout,'PUT');
	}

	/**
	 * DELETE
	 *
	 * @param   string  $url
	 * @param   array   $data 数据
	 * @param array $header header
	 * @param   int     $timeout 请求超时时间
	 * @return  string
	 */
	public static function delete($url,$header=array(),$data = array(), $timeout=30) {
		return self::exec($url,$data,$header,$timeout,'DELETE');
	}
	/**
	 * POST
	 *
	 * @param   string  $url
	 * @param   array   $data 数据
	 * @param array $header header
	 * @param   int     $timeout 请求超时时间
	 * @param string $method 请求方式
	 * @param boolean $CA 是否验证证书
	 * @return  string
	 */
	public static function exec($url,$data = array(),$header=array(),$timeout = 30,$method='POST',$CA = false)
	{
		$cacert = getcwd() . '/cacert.pem'; //CA根证书
		$SSL = substr($url, 0, 8) == "https://" ? true : false;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		$header = array_merge(array("X-HTTP-Method-Override: $method",'Expect:'),$header);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		if ($SSL && $CA) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);   // 只信任CA颁布的证书
			curl_setopt($ch, CURLOPT_CAINFO, $cacert); // CA根证书（用来验证的网站证书是否是CA颁布）
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true); // 检查证书中是否设置域名，并且是否与提供的主机名匹配
		} else if ($SSL && !$CA) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false); // 检查证书中是否设置域名
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$method = self::validate_method($method);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

		if ($data) {
			$data = !is_array($data)?$data:http_build_query($data);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data); //data with URLEncode
		}

		$data = curl_exec($ch);
		if (curl_errno($ch)) {
			return curl_error($ch);
		}
		$ret = self::parse($ch,$data);


		curl_close($ch);


		return $ret;
	}


	/**
	 * 验证Url合法性
	 * @param $url
	 * @return bool
     */
	private static function validate_url($url) {
		$ret = strcmp('http',$url) === 0 ?false:true;
		return $ret;
	}

	/**
	 * 验证请求方式合法性
	 * @param $method
	 * @return string
	 * @throws Exception
     */
	private static function validate_method($method) {
		$method = strtoupper($method);
		if (!in_array($method,array('GET','POST','PUT','DELETE'))) {
			throw new Exception('非法的请求方式');
		}
		return $method;
	}

	/**
	 * 处理返回结果
	 * @param $ch
	 * @param string $data
	 * @return array
     */
	protected static function parse($ch, $data='') {
		$data = curl_multi_getcontent($ch);
		$ans = explode("\r\n\r\n", $data, 2);
		$body = isset($ans[1])?$ans[1]:"{}";

		$info = curl_getinfo($ch);
		$code = $info['http_code'];
		$ret = array(
			'code'=>$code,
			'body'=>json_decode($body,true),
			'header'=>$info,
		);
		return $ret;

	}


}
