<?php

/**
* 公共应用实例
 */
require_once '../yhsd_api/core/YhsdAppPublicAbstract.php';

class PublicDemo extends YhsdAppPublic
{
    public function get_shop_code()
    {
        return '63501f7cbc2d4b9baf7e3cd5e2e256f7';
    }
    public function get_shop_redirect_uri()
    {
        return  'http://localhost/yhsd/demo/index.php?code=1234';
    }


    public function get_app_token()
    {
        return "8e294c52e967491f8d6e595da66413ee";
    }


    public function find()
    {
        $url = $this->getUrl();
        return $this->get($url);
    }

    protected function getUrl() {
        $url = isset($_GET['url']) ? $_GET['url'] : 'shop';
        $id = isset($_GET['id'])?$_GET['id']:'';
        $id = empty($id)?null:"/".$id;
        $url = $this->api_real . $url.$id;
        return $url;
    }

    public function add() {
        $rand = mt_rand(0,30);
        $data = $this->createProduct();
        $url = $this->getUrl();
        return $this->post($url,$data);
    }



    public function update() {

        $url = $this->getUrl();
        $data = $this->editProduct();
        return $this->put($url,$data);

    }

    public function del()
    {
        $url = $this->getUrl();
        return $this->delete($url);
    }

    public function createProduct()
    {
        $rand = mt_rand(0,30);
        $data = array(
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
        return $data;
    }
    public function editProduct()
    {
        $rand = mt_rand(0,1);
        $id = isset($_GET['id'])?intval($_GET["id"]):22954;
        $data = array(
            'product' =>
                array(
                    'visibility' => false,
                ));
        return $data;

    }
}