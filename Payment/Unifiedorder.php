<?php

namespace Thenbsp\Wechat\Payment;

use Thenbsp\Wechat\Util\Util;
use Thenbsp\Wechat\Util\Request;
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
     * 订单数据
     */
    protected $params;

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
    protected $required = array('appid', 'mch_id', 'mch_key', 'nonce_str', 'body', 'out_trade_no', 'total_fee', 'notify_url');

    /**
     * 选填项目
     */
    protected $optional = array(
        'device_info', 'detail', 'attach', 'fee_type', 'time_start', 'time_expire', 'goods_tag',
        'product_id', 'limit_pay', 'nonce_str', 'spbill_create_ip', 'trade_type', 'openid'
    );

    /**
     * 构造方法
     */
    public function __construct(array $optionsResolver = null)
    {
        if( !empty($optionsResolver) ) {
            foreach( $optionsResolver AS $k=>$v ) {
                $this->addParams($k, $v);
            }
        }
    }

    /**
     * 魔术方法
     */
    public function __call($method, $arguments)
    {
        if( !count($arguments) ) {
            throw new PaymentException(sprintf('Missing argument 1 for %s::%s()',
                __CLASS__, $method
            ));
        }

        $override = (isset($arguments[1]) && ($arguments[1] === false)) ? false : true;

        return $this->addParams($method, $arguments[0], $override);
    }

    /**
     * 添加参数
     */
    public function addParams($key, $value, $override = true)
    {
        if( !in_array($key, array_merge($this->required, $this->optional)) ) {
            throw new PaymentException('Invalid argument: '. $key);
        }

        if( $override ) {
            $this->params[$key] = $value;
        } else {
            if( !$this->hasParams($key) ) {
                $this->params[$key] = $value;
            }
        }

        return $this;
    }

    /**
     * 获取参数
     */
    public function getParams($paramName = null, $default = null)
    {
        if( null !== $paramName ) {
            return $this->hasParams($paramName) ?
                $this->params[$paramName] : $default;
        }
        return $this->params;
    }

    /**
     * 检测是否包含指定参数
     */
    public function hasParams($paramName)
    {
        return array_key_exists($paramName, $this->params);
    }

    /**
     * 验证参数值是否有效
     */
    public function validateParams()
    {
        if( !$this->hasParams('nonce_str') ) {
            $this->addParams('nonce_str', Util::randomString());
        }

        if( !$this->hasParams('spbill_create_ip') ) {
            $this->addParams('spbill_create_ip', Util::clientIP());
        }
            
        if( !$this->hasParams('trade_type') ) {
            $this->addParams('trade_type', 'JSAPI');
        }

        foreach($this->required AS $paramName) {
            if( !$this->hasParams($paramName) ) {
                throw new PaymentException(sprintf('"%s" is required', $paramName));
            }
        }

        // trade_type 为 JSAPI 时，必需包含 Openid
        if( $this->getParams('trade_type') === 'JSAPI' ) {
            if( !$this->hasParams('openid') ) {
                throw new PaymentException('"Openid" is required');
            }
        }
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
     * 获取统一下单结果
     */
    public function getResponse()
    {
        $this->validateParams();

        $params = $this->getParams();

        // remove mch_key
        unset($params['mch_key']);

        $signGenerator = new SignGenerator($params);
        $signGenerator->addParams('key', $this->getParams('mch_key'));

        $params['sign'] = $signGenerator->getResult();

        $body       = Util::array2XML($params);
        $response   = Request::post(static::UNIFIEDORDER_URL, $body, false);
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