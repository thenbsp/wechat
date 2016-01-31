<?php

namespace Thenbsp\Wechat\Payment\Jsapi;

use Thenbsp\Wechat\Bridge\Serializer;
use Thenbsp\Wechat\Payment\Unifiedorder;

abstract class ConfigGenerator
{
    /**
     * Thenbsp\Wechat\Payment\Unifiedorder
     */
    protected $unifiedorder;

    /**
     * 构造方法
     */
    public function __construct(Unifiedorder $unifiedorder)
    {
        $this->unifiedorder = $unifiedorder;
    }

    /**
     * 获取配置文件
     */
    public function getConfig($asArray = false)
    {
        $config = $this->generateConfig();

        return $asArray ? $config : Serializer::jsonEncode($config);
    }

    /**
     * 输出对象
     */
    public function __toString()
    {
        return $this->getConfig();
    }

    /**
     * 生成配置文件
     */
    abstract function generateConfig();
}
