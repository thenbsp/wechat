<?php

namespace Thenbsp\Wechat\Payment;

use Thenbsp\Wechat\Util\JSON;
use Thenbsp\Wechat\Util\SignGenerator;
use Thenbsp\Wechat\Payment\Unifiedorder;
use Thenbsp\Wechat\Exception\PaymentException;

class Js
{
    /**
     * 统一下单
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
     * 获取配置文件（用于 WeixinJSBridge invoke 方式）
     */
    public function getConfig($asJson = true)
    {
        $config = $this->_generateConfig();

        return $asJson ? JSON::encode($config) : $config;
    }

    /**
     * 获取配置文件（用于 Jssdk chooseWXPay 方式）
     */
    public function getConfigJssdk($asJson = true)
    {
        $config = $this->_generateConfig();
        $params = array(
            'timestamp' => $config['timeStamp'],
            'nonceStr'  => $config['nonceStr'],
            'package'   => $config['package'],
            'signType'  => $config['signType'],
            'paySign'   => $config['paySign']
        );

        return $asJson ? JSON::encode($params) : $params;
    }

    /**
     * 生成配置
     */
    private function _generateConfig($asJson = true)
    {
        $response   = $this->unifiedorder->getResponse();
        $params     = $this->unifiedorder->getParams();

        $config = array(
            'appId'     => $params['appid'],
            'timeStamp' => time(),
            'nonceStr'  => $response['nonce_str'],
            'package'   => 'prepay_id='.$response['prepay_id'],
            'signType'  => 'MD5'
        );

        $signGenerator = new SignGenerator($config);
        $signGenerator->addParams('key', $params['mch_key']);

        $config['paySign'] = $signGenerator->getResult();

        return $config;
    }
}
