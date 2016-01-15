<?php

require_once 'YhsdAppPublicAbstract.php';
class YhsdAppPublic extends YhsdAppPublicAbstract
{
    public function get_shop_redirect_uri()
    {
        return $this->_redirect_uri;
    }

    public function get_shop_code()
    {
        return $this->_code;
    }

}