<?php

require_once '../yhsd_api/core/YhsdAppPublicAbstract.php';
class YhsdAppPublicAbstractTest extends PHPUnit_Framework_TestCase
{
    protected $app;
    protected $shop_key = '495b32ff465b7e75a808ce95afd2400c';

    public function setUp()
    {
        $stub = $this->getMockForAbstractClass('YhsdAppPublicAbstract');

        $stub->expects($this->any())
            ->method('get_shop_redirect_uri')
            ->will($this->returnValue('http://localhost/yhsd/demo/index.php?code=1234'));

        $stub->expects($this->any())
            ->method('get_shop_code')
            ->will($this->returnValue('63501f7cbc2d4b9baf7e3cd5e2e256f7'));
        $stub->expects($this->any())
            ->method('get_app_token')
            ->will($this->returnValue('8e294c52e967491f8d6e595da66413ee'));
        $config = include ('config.public.php');
        $stub->set_attributes($config);

        $this->app = $stub;
    }

    /**
     *验证当前请求是否来自于友好速搭
     */
    public function test_hmac_verify()
    {
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
        $this->assertTrue($data);
    }

    /**
     * 用户授权的跳转网址
     */
    public function test_authorize_url()
    {
        $data = $this->app->authorize_url('http://localhost/yhsd/demo/index.php',$this->shop_key);
        $ans = 'https://apps.youhaosuda.com/oauth2/authorize/?response_type=code&client_id=516b18605e7b48ed9e05396ec30a5059&shop_key=495b32ff465b7e75a808ce95afd2400c&scope=read_basic%2Cread_products%2Cwrite_products&redirect_uri=http%253A%252F%252Flocalhost%252Fyhsd%252Fdemo%252Findex.php%253Fcode%253D1234&state=';
        $this->assertEquals($ans,$data);
    }

    /**
     * 获取店铺访问token方法
     */
    public function test_generate_token()
    {
        $ans = '8e294c52e967491f8d6e595da66413ee';
        $app = $this->app;
        /** @var $app YhsdAppPublicAbstract */
        $code = '63501f7cbc2d4b9baf7e3cd5e2e256f7';
        $redirect_uri = 'http://localhost/yhsd/demo/index.php?code=1234';
        //通过方法获取
        $this->assertEquals($ans,$app->generate_token($code,$redirect_uri));
        //通过属性获取
        $this->assertEquals($ans,$app->token);

    }

    /**
     * get
     * @param $path
     * @dataProvider getData
     */
    public function test_get($path)
    {
        list($code,$body,$header) = array_values($this->app->get($path));
        $this->assertEquals('200',$code);
    }
    /**
     * post
     */
    public function test_post()
    {
        $rand = mt_rand(100,200);
        $params = array(
            'product' =>
                array(
                    'name' => 'API商品样例' . $rand,
                    'variants' =>
                        array(
                            0 =>
                                array(
                                    'price' => $rand,
                                ),
                        ),
                ));
        list($code,$body,$header) = array_values($this->app->post('products',$params));
        $this->assertEquals('200',$code);
    }

    /**
     * put
     */
    public function test_put()
    {
        $rand = mt_rand(100,200);
        $params = array(
            'product' =>
                array(
                    'visibility' => false,
                ));
        list($code,$body,$header) = array_values($this->app->put('products/22951',$params));
        $this->assertEquals('200',$code);
    }



    /**
     * delete
     */
    public function test_delete()
    {
        list($code,$body,$header) = array_values($this->app->delete('products/22954'));
        //商品未找到
        $this->assertEquals('422',$code);
    }

    public function getData() {
        return array(
            '获取店铺信息'=> array('shop'),
            '获取商品列表'=>array('products'),
            '全路径网址'=>array('https://api.youhaosuda.com/v1/shop'),
            '获取指定商品'=>array('products/22951'),
        );
    }


}

