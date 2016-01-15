<?php


class YhsdApiObject
{
    protected $_attributes = [];

    public function get_attributes() {
        if (!$this->_attributes) return $this->_attributes;
        foreach ($this->_attributes as $attribute=>$value)
        {
            $attributes[$attribute] = $this->$attribute;
        }
        return $attributes;
    }

    public function set_attributes(array $values) {

        foreach ($values as $attribute=>$value) {
            $this->$attribute = $value;
        }
    }
    /**
     * YhsdApiObject constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {

        if (!empty($config)) {
            $this->attributes = $config;
        }
        $this->init();
    }

    /**
     * 初始化
     */
    public function init() {

    }
    /**
     * 返回属性值
     *
     * @param string $name 属性名称
     * @return mixed 属性值
     * @throws Exception 属性未定义
     * @throws Exception 属性只能写人
     */
    public function __get($name)
    {
        $getter = 'get_' . $name;
        if (method_exists($this, $getter)) {
            // read property, e.g. getName()
            return $this->$getter();
        }  else {
            if (array_key_exists($name,$this->_attributes)) {
                if (method_exists($this,$method = $this->generate_method_name($name,'get'))) {
                     return $this->$method();
                }
                return $this->_attributes[$name];
            }
        }
        if (method_exists($this, 'set_' . $name)) {
            throw new Exception('尝试获取只写属性: ' . get_class($this) . '::' . $name);
        } else {
            throw new Exception('属性未定义: ' . get_class($this) . '::' . $name);
        }
        return '';
    }



    /**
     * 设置属性值
     * @param string $name 属性名称
     * @param mixed $value 属性值
     * @throws Exception 属性未定义
     * @throws Exception 属性只能写人
     */
    public function __set($name, $value)
    {

        $setter = 'set_' . $name;
        if (method_exists($this, $setter)) {
            // set property
            $this->$setter($value);

            return;
        }  else {

                if (method_exists($this,$method = $this->generate_method_name($name,'set'))) {

                    $this->$method($value);
                    return;
                }
                $this->_attributes[$name] = $value;
                return;
        }

    }

    /**
     * 生成属性get/set的方法名
     * 属性名去除下划线_,首字母大写
     * 例如：app_key
     * 方法名为：getAppKey/setAppKey
     * 用于支持属性get/set方法的自动调用
     * $a = $this->app_key  等同于 $a = $this->getAppKey()
     * $this->app_key = $a 等同于 $this->setAppKey($a)
     * @param $name 属性名
     * @param $type 方法类型：get/set
     * @return string
     */
    protected function generate_method_name($name, $type) {
        $nameArray = explode('_',$name);
        if (!is_array($nameArray)) return $name;
        $method = $type?:'get';
        foreach ($nameArray as $part) {
            $method .= ucfirst($part);
        }
        return $method;
    }

    /**
     * 检查属性是否设置.
     * @param string $name 属性名
     * @return boolean 是否已定义
     */
    public function __isset($name)
    {
        $getter = 'get_' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter() !== null;
        } else {
            if (array_key_exists($name,$this->_attributes)) {
                return isset($this->_attributes[$name]);
            }
        }
        return false;
    }

    /**
     * 设置属性值为null
     * @param string $name 属性名
     * @throws Exception 属性只读.
     */
    public function __unset($name)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter(null);
            return;
        }
        throw new Exception('属性只读: ' . get_class($this) . '::' . $name);
    }



    /**
     * 判断属性是否存在
     *
     * @param string $name 属性名
     * @param boolean $checkVars 是否同时判断类属性
     * @return boolean 属性是否定义
     * @see canGetProperty()
     * @see canSetProperty()
     */
    public function hasProperty($name, $checkVars = true)
    {
        return $this->canGetProperty($name, $checkVars) || $this->canSetProperty($name, false);
    }

    /**
     * 属性是否可读
     *
     * @param string $name 属性名
     * @param boolean $checkVars 是否包括类属性
     * @return boolean 属性是否可读
     * @see canSetProperty()
     */
    public function canGetProperty($name, $checkVars = true)
    {
        if (method_exists($this, 'get_' . $name) || $checkVars && property_exists($this, $name)) {
            return true;
        }
        return false;
    }

    /**
     * 判断属性是否可写
     *
     * @param string $name 属性名
     * @param boolean $checkVars 是否包括类属性
     * @return boolean 属性是否可写
     * @see canGetProperty()
     */
    public function canSetProperty($name, $checkVars = true)
    {
        if (method_exists($this, 'set_' . $name) || $checkVars && property_exists($this, $name)) {
            return true;
        }
        return false;
    }

    /**
     * 判断方法是否存在
     *
     * @param string $name 方法名称
     * @return boolean 方法是否存在
     */
    public function hasMethod($name)
    {
        return method_exists($this,$name);
    }


}