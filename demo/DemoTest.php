<?php

class DemoTest
{

    protected $public_config;
    protected $private_config;
    protected $app;
    protected $shop_key = '495b32ff465b7e75a808ce95afd2400c';
    protected $account_id = '9904';
    protected $code = '7408b915e81b483797f1ec54301dcb1c';
    protected $redirect_uri = 'http://localhost/yhsd/demo/index.php';


    public function __construct($type = 'public')
    {

        $type = strtolower($type);
        $configfile = "config.$type.php";
        $config = include($configfile);
        $classfile = "../demo/$type.class.php";
        include($classfile);
        $class = ucfirst($type).'Demo';
        $this->app = new $class($config);

    }


    /**
     * 第三方应用支持加密数据
     * @return mixed
     */
    public function test_thirdapp_aes_encrypt()
    {
        $key = '095AE461E2554EED8D12F19F9662247E';
        $data = array ( 'uid' => 'test@youhaosuda.com', 'type' => 'email', 'name' => 'test');
        $ori = <<<EEE
{\"uid\":\"test@youhaosuda.com\",\"type\":\"email\",\"name\":\"test\"}
EEE;
;
        $params = json_encode($data);

        $correct = 'mJgEpH-ja_sBlYG_W3HcbekE_HP2yQVrlX2hu8AKM8F5JjPFTRYBwc62HGhCZgfyf3FxECC9u-tcnmsZcheENw==';
        $ret = $this->app->thirdapp_aes_encrypt($key,$params);
        echo "验证值：",$correct,'<br/>','返回值：',$ret;


        return $ret == $correct;
    }

    public function test_authorize_url()
    {
        $data = $this->app->authorize_url('http://localhost/yhsd/demo/index.php',$this->shop_key);
        echo '<a href="'.$data.'">授权地址</a>';
        return $data;
    }

    public function test_generate_token()
    {
//        $data = ($_GET['type'] == 'public')?$this->app->generate_token($this->code,$this->redirect_uri):$this->app->generate_token();
        return $this->app->token;
//        return $data;
    }


    public function test_hmac_verify()
    {
//        $test = array('hmac'=>$_GET['hmac'],'time_stamp'=>$_GET['time_stamp']);
        $data = <<<EEE
{
  "shop_key": "a94a110d86d2452eb3e2af4cfb8a3828",
  "code": "a84a110d86d2452eb3e2af4cfb8a3828",
  "account_id": "1",
  "time_stamp": "2013-08-27T13:58:35Z",
  "hmac": "a2a3e2dcd8a82fd9070707d4d921ac4cdc842935bf57bc38c488300ef3960726"
}
EEE;
        $test = json_decode($data,true);
        $this->app->app_secret = 'hush';
        $data = $this->app->hmac_verify($test);
        return $data;
    }

    /**
     * 友好速搭开放支付回调验证
     * @return boolean
     */
    public function test_openpayment_verify()
    {
        $parmas = "{\"created_at\":\"2014-08-28T17:28:13.301+08:00\",\"domain\":\"www.example.com\",\"enable_email_regist\":true,\"enable_mobile_regist\":true,\"enable_username_regist\":true,\"name\":\"TEST\",\"page_description\":\"\",\"page_title\":\"\",\"updated_at\":\"2015-07-27T13:58:14.607+08:00\",\"url\":\"http://www.example.com\",\"webhook_token\":\"906155047ff74a14a1ca6b1fa74d3390\"}";
        $hmac = "NS0Wcz2CDgzI4+L9/UYdwaXpPI4As7VD+wKCRgKqNUo=";
        $app = $this->app;
        $token = '906155047ff74a14a1ca6b1fa74d3390';
        $app->set_token($token);
        $data = $this->app->openpayment_verify($parmas,$hmac);
        return $data;
    }
    public function test_webhook_verify()
    {
        $parmas = "{\"created_at\":\"2014-08-28T17:28:13.301+08:00\",\"domain\":\"www.example.com\",\"enable_email_regist\":true,\"enable_mobile_regist\":true,\"enable_username_regist\":true,\"name\":\"TEST\",\"page_description\":\"\",\"page_title\":\"\",\"updated_at\":\"2015-07-27T13:58:14.607+08:00\",\"url\":\"http://www.example.com\",\"webhook_token\":\"906155047ff74a14a1ca6b1fa74d3390\"}";
        $hmac = "NS0Wcz2CDgzI4+L9/UYdwaXpPI4As7VD+wKCRgKqNUo=";
        $secret = '906155047ff74a14a1ca6b1fa74d3390';
        $data = $this->app->webhook_verify($parmas,$hmac,$secret);
        return $data;
    }


    public function test_get()
    {
        $data = $this->app->find();
        return $data;
    }
    public function test_post()
    {
        $data = $this->app->add();
        return $data;
    }
    public function test_put()
    {
        $data = $this->app->update();
        return $data;
    }
    public function test_delete()
    {
        $data = $this->app->del();
        return $data;
    }

    public function test_authorization(){
        $key = 'a94a110d86d2452eb3e2af4cfb8a3828';
        $secret = 'a84a110d86d2452eb3e2af4cfb8a3828';
        $data = $this->app->authorization($key,$secret);
        $correct = "Basic YTk0YTExMGQ4NmQyNDUyZWIzZTJhZjRjZmI4YTM4Mjg6YTg0YTExMGQ4NmQyNDUyZWIzZTJhZjRjZmI4YTM4Mjg=";
        echo $data,"<br/>",$correct;
        return $data == $correct;
    }

    public function test_auth()
    {
        $data = $this->app->authorization($this->app->app_key,$this->app->app_secret);
        return $data;
    }

    public function test_generate_hmac()
    {
        $params = array(
            'shop_key'=>$this->app->app_key,
//            'account_id' =>$this->app-
        );
       return $this->app->generate_hmac($params);
    }









}