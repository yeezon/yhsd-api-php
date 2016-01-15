# yhsd-api-php

友好速搭应用开发 PHP SDK

## 安装
下载SDK压缩包，并解压到项目目录。


## 使用方法

###1，私有应用

配置私有应用的app_key, app_secret，参见yhsd_api/config.private.php

```php
	return array(
	    'app_key' => '配置你的app_key',
	    'app_secret' => '配置你的app_secret',
	);
```

使用私有应用，实例化私有应用并加载配置文件
```php
	$config = include('config.php');
	$app = new YhsdAppPrivate($config);
```

获取友好速搭token

```php
	$app->generate_token();
```
或直接使用属性token
```php
	$app->token;
```

调用友好速搭api接口

```php
	/**
	 *get 接口调用
	 * @param string $url 需要访问的api接口的地址
	 * @return array 返回结果为数组,包含：
	 * code :200
	 * body hash 数据
	 * header hash 数据
	 */
	public function get($url)

	/**
	 * put 接口调用
	 * @param string @url 需要访问的api接口的地址
	 * @param array $params 参数数组
	 * @return array 返回结果为数组，包含：
	 * code :200
	 * body hash 数据
	 * header hash 数据
	 */
	public function put($url, $params)

	/**
	 * post 接口调用
	 * @param string @url 需要访问的api接口的地址
	 * @param array $params 参数数组
	 * @return array 返回结果为数组，包含：
	 * code :200
	 * body hash 数据
	 * header hash 数据
	 */
	public function post($url, $params)

	/**
	 * delete 接口调用
	 * @param string $url 需要访问的api接口的地址
	 * @return array 返回结果为数组,包含：
	 * code :200
	 * body hash 数据
	 * header hash 数据
	 */
	public function delete($url)
```

例子
```php

	//get 接口调用
	list($code, $body, $header) = array_values($app->get("shop"));

	//put 接口调用
	$params = array(
	  "redirect"=> array(
	    "path"=> "/12345",
	    "target"=> "/blogs"
	  )
	)
	list($code, $body, $header) = array_values($app->put("redirects/1", params));

	//post 接口调用
	$params = array(
	  "redirect"=> array(
	    "path"=> "/12345",
	    "target"=> "/blogs"
	  )
	)
	list($code, $body, $header) = array_values($app->put("redirects", params));

	//delete 接口调用
	list($code, $body, $header) = array_values($app->delete("redirects/1"));

```

###2，公有应用

配置公有应用的app_key, app_secret, app_scope, 参见yhsd_api/config.public.php

```php
	return array(
	    'app_key' => '配置你的app_key',
	    'app_secret' => '配置你的app_secret',
	    'app_scope' => '配置你的应用scope',
	);
```
使用公有应用，实例化公有应用并加载配置文件
```php
	$config = include('config.php');
	$app = new YhsdAppPublic($config);
```

获取友好速搭授权url

```php
	/**
	 * 生成用户授权的跳转网址，便于应用执行跳转
	 * @param string $redirect_uri 应用的跳转地址
	 * @param string $shop_key 友好速搭安装应用的店铺唯一key
	 * @param string $state 自定义的参数
	 * @return string 用户授权的回调地址
	 * @throws Exception
	 */
	public function authorize_url($redirect_uri, $shop_key, $state = '')
```
友好速搭hmac验证，获取到参数后调用

```php
	/**
	 * 通过app secret和友好速搭回传的参数，验证当前请求是否来自于友好速搭
	 * @param array $params 获取到的所有参数
	 * @return bool true表示验证正确，false表示错误
	 * @throws InvalidArgumentException
     */
	public function hmac_verify(array $params = [])
```



调用友好速搭api接口

友好速搭店铺token获取

```php
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
```
例子：
```php
	$code = '用户授权后返回给回调网址的code';
	$redirect_uri = '发起用户授权的回调地址'; //与获取友好速搭授权的回调地址一致
	$token = $app->generate_token($code,$redirect_uri);
```

调用友好速搭api接口

```php
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
```

例子
```php
	$token = $app->generate_token();

	//get 接口调用
	list($code, $body, $header) = array_values($app->get("shop"));

	//put 接口调用
	$params = array(
	  "redirect"=> array(
	    "path"=> "/12345",
	    "target"=> "/blogs"
	  )
	)
	list($code, $body, $header) = array_values($app->put("redirects/1", params));

	//post 接口调用
	$params = array(
	  "redirect"=> array(
	    "path"=> "/12345",
	    "target"=> "/blogs"
	  )
	)
	list($code, $body, $header) = array_values($app->put("redirects", params));

	//delete 接口调用
	list($code, $body, $header) = array_values($app->delete("redirects/1"));
```
友好速搭公有应用的每个店铺都有固定的token,不会过期，获取token后，应该与相应的商铺信息永久保存下来。

###3,公共部分

第三方App接入参数生成函数
```php
	/**
	 * 第三方App接入参数生成函数
	 * @param $key
	 * @param array $data
	 * @return string
	 * @throws Exception
     */
	public  function thirdapp_aes_encrypt($key, $data = [])
```

验证 Webhook 通知来源
```php

	/**
	 * 验证 Webhook 通知来源
	 * @param $params
	 * @param $hmac
	 * @return bool
	 * @throws Exception
     */
	public function webhook_verify($params, $hmac,$secret)
```
友好速搭开放支付回调验证
```php
	/**
	 * 友好速搭开放支付回调验证
	 * @param array $data
	 * @param string $hmac
	 * @return bool
	 * @throws Exception
	 */
	public function openpayment_verify($data, $hmac)
```
## 贡献

1. Fork it ( https:*github.com/yeezon/yhsd-api-php/fork )
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create a new Pull Request
