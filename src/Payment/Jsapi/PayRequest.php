<?php

namespace Thenbsp\Wechat\Payment\Jsapi;

class PayRequest extends ConfigGenerator
{
    /**
     * 分解配置文件
     */
    public function resolveConfig()
    {
        return $this->toArray();
    }
}
