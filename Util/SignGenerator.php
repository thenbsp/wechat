<?php

namespace Thenbsp\Wechat\Util;

use Thenbsp\Wechat\Util\Bag;
use Thenbsp\Wechat\Util\Accessor;

/**
 * 签名生成器（专门用于生成微信各种签名）
 * Created by thenbsp (thenbsp@gmail.com)
 * Created at 2015/08/06
 */
class SignGenerator
{
    /**
     * 参数包
     */
    protected $bag;

    /**
     * 加密类型
     */
    protected $hashType = 'md5';

    /**
     * 是否转为大写
     */
    protected $isUpper = true;

    /**
     * 排序回调函数
     */
    protected $sortAfterCallback;

    /**
     * 构造方法
     */
    public function __construct(Bag $bag)
    {
        $this->bag = $bag;
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
     * 排序之后调用（事件）
     */
    public function onSortAfter(callable $callback)
    {
        $this->sortAfterCallback = $callback;
    }

    /**
     * 获取签结果
     */
    public function getResult()
    {
        $this->bag->ksort();

        if( is_callable($this->sortAfterCallback) ) {
            call_user_func($this->sortAfterCallback, $this->bag);
        }

        $query = http_build_query($this->bag->all());
        $query = urldecode($query);

        $result = call_user_func($this->hashType, $query);

        return $this->isUpper ? strtoupper($result) : $result;
    }
}