<?php

namespace Thenbsp\Wechat\Payment\Jsapi;

use Thenbsp\Wechat\Bridge\Util;
use Thenbsp\Wechat\Bridge\Serializer;
use Thenbsp\Wechat\Payment\Unifiedorder;
use Doctrine\Common\Collections\ArrayCollection;

abstract class ConfigGenerator extends ArrayCollection
{
    /**
     * 构造方法
     */
    public function __construct(Unifiedorder $unifiedorder, array $defaults = array())
    {
        $res = $unifiedorder->getResponse();
        $key = $unifiedorder->getKey();

        $config = array(
            'appId'     => $unifiedorder['appid'],
            'timeStamp' => Util::getTimestamp(),
            'nonceStr'  => Util::getRandomString(),
            'package'   => 'prepay_id='.$res['prepay_id'],
            'signType'  => 'MD5'
        );

        // 如果需要指定以上参数，可以通过 $defaults 变量传入
        $options = array_replace($config, $defaults);

        ksort($options);

        $queryString    = urldecode(http_build_query($options));
        $paySign        = strtoupper(md5($queryString.'&key='.$key));

        $options['paySign'] = $paySign;

        parent::__construct($options);
    }

    /**
     * 获取配置
     */
    public function getConfig($asArray = false)
    {
        $config = $this->resolveConfig();

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
     * 分解配置
     */
    abstract function resolveConfig();
}
