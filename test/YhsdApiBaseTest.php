<?php

/**
 * Class YhsdApiBaseTest
 * 友好速搭公共部分测试用例
 */
require_once '../yhsd_api/core/YhsdApiBase.php';
class YhsdApiBaseTest extends PHPUnit_Framework_TestCase
{
    protected $public;
    protected $private;
    protected $config = array(
        /*------------必填项--------*/
        'app_key' => '8ed4cfdd1d4843fb811ee837220c1f9d',
        'app_secret' => '1358360b0c244811839a2729b7cf2442',
        /*------------选填项--------*/
        'auth_url' => 'https://apps.youhaosuda.com/oauth2/authorize/',
        'token_url' => 'https://apps.youhaosuda.com/oauth2/token/',
        'api_url' => 'https://api.youhaosuda.com/',
        'api_version' => 'v1/',
        'call_limit_protect' => false,
    );

    public function setUp()
    {
        $stub = $this->getMockForAbstractClass('YhsdApiBase');

        $stub->expects($this->any())
            ->method('get_token')
            ->will($this->returnValue('954981882d7d4c71b753ee8fccd28b96'));


        $config = include('config.private.php');
        $stub->set_attributes($this->config);

        $this->private = $stub;
        $privateConfig = include('config.private.php');
        $this->private = new YhsdApiBase($privateConfig);
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
        $ret = $this->private->thirdapp_aes_encrypt($key,$params);

       $this->assertEquals($correct,$ret);
    }

    /**
     * 验证 Webhook 通知来源
     */
    public function test_webhook_verify()
    {
        $parmas = "{\"created_at\":\"2014-08-28T17:28:13.301+08:00\",\"domain\":\"www.example.com\",\"enable_email_regist\":true,\"enable_mobile_regist\":true,\"enable_username_regist\":true,\"name\":\"TEST\",\"page_description\":\"\",\"page_title\":\"\",\"updated_at\":\"2015-07-27T13:58:14.607+08:00\",\"url\":\"http://www.example.com\",\"webhook_token\":\"906155047ff74a14a1ca6b1fa74d3390\"}";
        $hmac = "NS0Wcz2CDgzI4+L9/UYdwaXpPI4As7VD+wKCRgKqNUo=";
        $secret = '906155047ff74a14a1ca6b1fa74d3390';
        $data = $this->private->webhook_verify($parmas,$hmac,$secret);
        return $this->assertTrue($data);
    }

    /**
     * 友好速搭开放支付回调验证
     */
    public function test_openpayment_verify()
    {
        $parmas = "{\"created_at\":\"2014-08-28T17:28:13.301+08:00\",\"domain\":\"www.example.com\",\"enable_email_regist\":true,\"enable_mobile_regist\":true,\"enable_username_regist\":true,\"name\":\"TEST\",\"page_description\":\"\",\"page_title\":\"\",\"updated_at\":\"2015-07-27T13:58:14.607+08:00\",\"url\":\"http://www.example.com\",\"webhook_token\":\"906155047ff74a14a1ca6b1fa74d3390\"}";
        $hmac = "NS0Wcz2CDgzI4+L9/UYdwaXpPI4As7VD+wKCRgKqNUo=";
        $app = $this->private;
        $token = '906155047ff74a14a1ca6b1fa74d3390';
        $app->token = $token;
        var_dump($app->token);
        $data = $app->openpayment_verify($parmas,$hmac);
        $this->assertTrue($data);
    }



}
