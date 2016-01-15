<?php

require_once '../yhsd_api/core/YhsdAppPublic.php';
class YhsdAppPublicTest extends PHPUnit_Framework_TestCase
{
    protected $app;
    protected $shop_key = '495b32ff465b7e75a808ce95afd2400c';

    public function setUp() {
        $config = include ('config.public.php');
        $this->app = new YhsdAppPublic($config);
    }

    public function test_generate_token()
    {
        $code = '63501f7cbc2d4b9baf7e3cd5e2e256f7';
        $redirect_uri = 'http://localhost/yhsd/demo/index.php?code=1234';
        $ans = '8e294c52e967491f8d6e595da66413ee';
        $this->assertEquals($ans,$this->app->generate_token($code,$redirect_uri));
    }

    /**
     * get
     * @param $path
     * @dataProvider getData
     */
    public function test_get($path)
    {
        $token = '8e294c52e967491f8d6e595da66413ee';
        list($code,$body,$header) = array_values($this->app->get($path,$token));
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
        $token = '8e294c52e967491f8d6e595da66413ee';
        list($code,$body,$header) = array_values($this->app->post('products',$params,$token));
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
        $token = '8e294c52e967491f8d6e595da66413ee';
        list($code,$body,$header) = array_values($this->app->put('products/22951',$params,$token));
        $this->assertEquals('200',$code);
    }



    /**
     * delete
     */
    public function test_delete()
    {
        $token = '8e294c52e967491f8d6e595da66413ee';
        list($code,$body,$header) = array_values($this->app->delete('products/22954',$token));
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

    /**
     * 测试配置文件中配置app_scope是，用了换行符
     * @dataProvider prepare_scope_test
     */
    public function test_scope_with_EOL($config) {

        $app = new YhsdAppPublic($config);
        $ans = 'read_basic,read_products,write_products';
        $this->assertEquals($ans,$app->app_scope);

    }

    public function prepare_scope_test()
    {
        return array(
           array(array(
               'app_scope' => 'read_basic,
                            read_products,
                            write_products',
            )) ,
        );
    }
}
