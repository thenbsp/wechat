<?php

namespace Thenbsp\Wechat\Util;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Exception\AccessException;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\NoSuchOptionException;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

abstract class Options implements \ArrayAccess, \Countable
{
    /**
     * 选项参数
     */
    protected $options = array();

    /**
     * 构造方法
     */
    public function __construct(array $options = array())
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        // PHP >= 5.5 used finally
        try {
            $this->options = $resolver->resolve($options);
        } catch (AccessException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        } catch (InvalidOptionsException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        } catch (MissingOptionsException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        } catch (NoSuchOptionException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        } catch (OptionDefinitionException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        } catch (UndefinedOptionsException $e) {
            throw new \InvalidArgumentException($e->getMessage());
        }
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

    /**
     * 配置参数
     */
    abstract protected function configureOptions($resolver);
}
