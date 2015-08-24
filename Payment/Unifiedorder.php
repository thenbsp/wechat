<?php

namespace Thenbsp\Wechat\Payment;

use Thenbsp\Wechat\Util\Bag;
use Thenbsp\Wechat\Util\Util;
use Thenbsp\Wechat\Util\Http;
use Thenbsp\Wechat\Util\SignGenerator;
use Thenbsp\Wechat\Exception\PaymentException;

class Unifiedorder
{
    /**
     * 统一下单接口
     * https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=9_1
     */
    const UNIFIEDORDER_URL = 'https://api.mch.weixin.qq.com/pay/unifiedorder';

    /**
     * 参数包
     */
    protected $bag;

    /**
     * 商户 Key
     */
    protected $key;

    /**
     * 商户证书 cert
     */
    protected $clientCert;

    /**
     * 商户证书 key
     */
    protected $clientKey;

    /**
     * 必填项目
     */
    protected $required = array('appid', 'mch_id', 'nonce_str', 'body',
        'out_trade_no', 'total_fee', 'notify_url');

    /**
     * 选填项目
     */
    protected $optional = array(
        'device_info', 'detail', 'attach', 'fee_type', 'time_start',
        'time_expire', 'goods_tag', 'product_id', 'limit_pay', 'nonce_str',
        'spbill_create_ip', 'trade_type', 'openid');

    /**
     * 构造方法
     */
    public function __construct(Bag $bag, $key)
    {
        if( !$bag->has('trade_type') ) {
            $bag->set('trade_type', 'JSAPI');
        }

        if( !$bag->has('nonce_str') ) {
            $bag->set('nonce_str', Util::randomString());
        }

        if( !$bag->has('spbill_create_ip') ) {
            $bag->set('spbill_create_ip', Util::getClientIp());
        }

        // 检测必填字段
        foreach($this->required AS $paramName) {
            if( !$bag->has($paramName) ) {
                throw new PaymentException(sprintf('"%s" is required', $paramName));
            }
        }

        // trade_type 为 JSAPI 时，必需包含 Openid
        if( $bag->get('trade_type') === 'JSAPI' ) {
            if( !$bag->has($paramName) ) {
                throw new PaymentException('"Openid" is required');
            }
        }

        $this->bag = $bag;
        $this->key = $key;
    }

    /**
     * 设置商户证书 cert
     */
    public function setClientCert($filepath)
    {
        if( !file_exists($filepath) ) {
            throw new PaymentException(sprintf('client_cert "%s" is not found', $filepath));
        }
        $this->clientCert = $filepath;
    }

    /**
     * 获取商户证书 cert
     */
    public function getClientCert()
    {
        return $this->clientCert;
    }

    /**
     * 设置商户证书 key
     */
    public function setClientKey($filepath)
    {
        if( !file_exists($filepath) ) {
            throw new PaymentException(sprintf('client_key "%s" is not found', $filepath));
        }
        $this->clientKey = $filepath;
    }

    /**
     * 获取商户证书 key
     */
    public function getClientKey()
    {
        return $this->clientKey;
    }

    /**
     * 获取参数包
     */
    public function getBag()
    {
        return $this->bag;
    }

    /**
     * 获取商户 Key
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * 获取统一下单结果
     */
    public function getResponse()
    {
        $signGenerator = new SignGenerator($this->bag);
        $signGenerator->onSortAfter(function($bag) {
            $bag->set('key', $this->key);
        });

        // 生成签名
        $sign = $signGenerator->getResult();
        // 生成签名后移除 Key
        $this->bag->remove('key');
        // 调置签名
        $this->bag->set('sign', $sign);

        $body       = Util::array2XML($this->bag->all());
        $response   = Http::post(static::UNIFIEDORDER_URL, $body, false);
        $response   = Util::XML2Array($response);

        if( isset($response['result_code']) &&
            ($response['result_code'] === 'FAIL') ) {
            throw new PaymentException($response['err_code'].': '.$response['err_code_des']);
        }

        if( isset($response['return_code']) &&
            $response['return_code'] === 'FAIL' ) {
            throw new PaymentException($response['return_code'].': '.$response['return_msg']);
        }

        return $response;
    }

}