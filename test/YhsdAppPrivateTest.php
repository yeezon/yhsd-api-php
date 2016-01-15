<?php

/**
 * Created by PhpStorm.
 * User: s
 * Date: 2016/1/8
 * Time: 18:52
 */
require_once '../yhsd_api/core/YhsdAppPrivate.php';
class YhsdAppPrivateTest extends PHPUnit_Framework_TestCase
{
    protected $app;
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
        $this->app = new YhsdAppPrivate($this->config);
    }
    /**
     * 获取店铺访问token方法
     */
    public function test_generate_token()
    {
        $ans = '954981882d7d4c71b753ee8fccd28b96';
        //通过方法获取
        $this->assertEquals($ans,$this->app->generate_token());
        //通过属性获取
        $this->assertEquals($ans,$this->app->token);

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
