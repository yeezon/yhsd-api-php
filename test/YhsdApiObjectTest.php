<?php
require_once '../yhsd_api/core/YhsdApiObjct.php';

class YhsdApiObjectTest extends PHPUnit_Framework_TestCase
{

    protected $obj;
    protected $config = array(
        'app_key' => '1234',
        'app_secret' => '',
    );

    public function setUp() {
        $this->obj = new YhsdApiObject($this->config);
    }

    /**
     * 测试构造函数
     */
    public function testConstructor()
    {
        $this->assertArraySubset($this->obj->attributes,$this->config);
    }
    /**
     * 测试__get()
     * @expectedException Exception
     */
    public function testGet()
    {
        $set = '22334455';
        $this->obj->app_key = $set;
        $this->assertEquals($set,$this->obj->app_key);
        //测试访问未定义的属性，应该抛出异常
        $a = $this->obj->app_not_set;
    }

    /**
     * 测试__set（）
     *
     */
    public function testSet() {
        $this->obj->attributes = array_merge(array('app_new_attribute'=>'new'));
        $this->assertArrayHasKey('app_new_attribute',$this->obj->attributes);
        $this->assertEquals('new',$this->obj->app_new_attribute);

        $set = '22334455';
        $this->obj->app_key = $set;
        $this->assertEquals($set,$this->obj->app_key);
        //当设置未定义属性时，抛出异常
        $this->obj->not_set = 'none';
    }

    public function testIsSet() {
        $this->assertTrue(isset($this->obj->app_secret));
        $this->assertNotTrue(isset($this->obj->app_not_set));
    }

    public function testHasProperty() {
        $this->assertTrue($this->obj->hasProperty('attributes'));
    }

    public function testCanGetProperty() {
        $this->assertTrue($this->obj->canGetProperty('attributes'));
    }
    public function testCanSetProperty() {
        $this->assertTrue($this->obj->canSetProperty('attributes'));
    }

    public function testHasMethod() {
        $this->assertTrue($this->obj->hasMethod('set_attributes'));
    }
}
