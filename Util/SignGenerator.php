<?php

namespace Thenbsp\Wechat\Util;

use Thenbsp\Wechat\Util\Accessor;

/**
 * 签名生成器（专门用于生成微信各种签名）
 * Created by thenbsp (thenbsp@gmail.com)
 * Created at 2015/08/06
 */
class SignGenerator
{
    /**
     * 参与签名的 Key=>Value
     */
    protected $params = array();

    /**
     * 加密类型
     */
    protected $hashType = 'md5';

    /**
     * 是否转为大写
     */
    protected $isUpper = true;

    /**
     * 构造方法
     */
    public function __construct(array $params = array())
    {
        $this->params = $params;
    }

    /**
     * 检测是否包含某项
     */
    public function hasParams($key)
    {
        return array_key_exists($key, $this->params);
    }

    /**
     * 获取参数
     */
    public function getParams($key = null, $default = null)
    {
        if( !is_null($key) ) {
            return $this->hasParams($key) ?
                $this->params[$key] : $default;
        }

        return $this->params;
    }

    /**
     * 添加一项（重复添加前者会被覆盖）
     */
    public function addParams($key, $value)
    {
        $this->params[$key] = $value;

        return $this;
    }

    /**
     * 移除一项
     */
    public function removeParams($key)
    {
        if( $this->hasParams($key) ) {
            unset($this->params[$key]);
        }

        return $this;
    }

    /**
     * 设置加密类型
     */
    public function setHashType($hashType)
    {
        $this->hashType = strtolower($hashType);
    }

    /**
     * 是否转为大写
     */
    public function setUpper($value)
    {
        return $this->isUpper = (bool) $value;
    }

    /**
     * 获取签结果
     */
    public function getResult()
    {
        if( $this->hasParams('key') ) {
            $key = $this->getParams('key');
            $this->removeParams('key');
        }

        ksort($this->params);

        if( isset($key) ) {
            $this->addParams('key', $key);
        }

        $query = http_build_query($this->params);
        $query = urldecode($query);

        $result = call_user_func($this->hashType, $query);

        return $this->isUpper ? strtoupper($result) : $result;
    }
}