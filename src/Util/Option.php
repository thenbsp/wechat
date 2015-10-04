<?php

namespace Thenbsp\Wechat\Util;

class Option implements \ArrayAccess, \Countable
{
    /**
     * 参数选项
     */
    protected $options = array();

    /**
     * 构造方法
     */
    public function __construct(array $options = array())
    {
        $this->setOptions($options);
    }

    /**
     * 设置选项
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * 获取全部选项
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * 检测偏移是否存在
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->options);
    }

    /**
     * 获取指定偏移
     */
    public function offsetGet($offset, $default = null)
    {
        return $this->offsetExists($offset) ?
            $this->options[$offset] : $default;
    }

    /**
     * 设置指定偏移
     */
    public function offsetSet($offset, $value)
    {
        $this->options[$offset] = $value;
    }

    /**
     * 移除指定偏移
     */
    public function offsetUnset($offset)
    {
        if( $this->offsetExists($offset) ) {
            unset($this->options[$offset]);
        }
    }

    /**
     * 选项数量
     */
    public function count()
    {
        return count($this->options);
    }
}
