<?php

/**
 * Class YhsdApiHelperTest
 * 测试友好速搭帮助类
 */
require_once '../yhsd_api/core/YhsdApiHelper.php';

class YhsdApiHelperTest extends PHPUnit_Framework_TestCase
{

    public function getData() {
        return array(
            array('YTk0YTExMGQ4NmQyNDUyZWIzZTJhZjRjZmI4YTM4Mjg6YTg0YTExMGQ4NmQyNDUyZWIzZTJhZjRjZmI4YTM4Mjg=','a94a110d86d2452eb3e2af4cfb8a3828:a84a110d86d2452eb3e2af4cfb8a3828'),
            array('OGVkNGNmZGQxZDQ4NDNmYjgxMWVlODM3MjIwYzFmOWQ6MTM1ODM2MGIwYzI0NDgxMTgzOWEyNzI5YjdjZjI0NDI=','8ed4cfdd1d4843fb811ee837220c1f9d:1358360b0c244811839a2729b7cf2442')
        );
    }
    /**
     * @dataProvider getData
     */
    public function test_urlsafe_b64encode($ans,$params) {
        $this->assertEquals($ans,YhsdApiHelper::urlsafe_b64encode($params));
    }

    /**
     * @dataProvider getData
     */
    public function test_urlsafe_b64decode($params,$ans) {
        $this->assertEquals($ans,YhsdApiHelper::urlsafe_b64decode($params));
    }


}
