<?php

/**
* 公共应用实例
 */
require_once '../yhsd_api/core/YhsdAppPrivate.php';

class PrivateDemo extends YhsdAppPrivate
{
    public function get_app_token()
    {
        return "954981882d7d4c71b753ee8fccd28b96";
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

