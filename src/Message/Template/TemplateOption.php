<?php

namespace Thenbsp\Wechat\Message\Template;

use Thenbsp\Wechat\Util\Serialize;

class TemplateOption implements TemplateOptionInterface
{
    /**
     * 选项容器
     */
    protected $container = array();

    /**
     * 检测是否存在指定选项
     */
    public function has($data)
    {
        return array_key_exists($data, $this->container);
    }

    /**
     * 添加选项
     */
    public function add($data, $value, $color = null)
    {
        $array = array('value' => $value);

        if( !is_null($color) ) {
            $array['color'] = $color;
        }

        $this->container[$data] = $array;
    }

    /**
     * 移除指定选项
     */
    public function remove($data)
    {
        if( $this->has($data) ) {
            unset($this->container[$data]);
        }
    }

    /**
     * 转为 Array
     */
    public function toArray()
    {
        return $this->container;
    }

    /**
     * 转为 JSON
     */
    public function toJSON()
    {
        return Serialize::encode($this->toArray(), 'json');
    }

    /**
     * 检测选项上否为空
     */
    public function isEmpty()
    {
        return empty($this->container);
    }
}