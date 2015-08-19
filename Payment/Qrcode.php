<?php

namespace Thenbsp\Wechat\Payment;

use Thenbsp\Wechat\Util\Util;
use Thenbsp\Wechat\Util\SignGenerator;
use Thenbsp\Wechat\Exception\PaymentException;

class Qrcode
{
    /**
     * 支付路径
     */
    const WXPAY_URL = 'weixin://wxpay/bizpayurl';

    /**
     * 订单数据
     */
    protected $params;

    /**
     * 必填项目
     */
    protected $required = array('appid', 'mch_id', 'mch_key', 'time_stamp', 'nonce_str', 'product_id');

    /**
     * 构造方法
     */
    public function __construct($params) {

        if( !isset($params['time_stamp']) ) {
            $params['time_stamp'] = time();
        }

        if( !isset($params['nonce_str']) ) {
            $params['nonce_str'] = Util::randomString();
        }

        foreach($this->required AS $paramName) {
            if( !isset($params[$paramName]) ) {
                throw new PaymentException(sprintf('"%s" is required', $paramName));
            }
        }

        // remove mch_key
        $key = $params['mch_key'];
        unset($params['mch_key']);

        $signGenerator = new SignGenerator($params);
        $signGenerator->addParams('key', $key);

        $params['sign'] = $signGenerator->getResult();

        $this->params = $params;
    }

    /**
     * 获取参数
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * 获取支付 URL
     */
    public function getPayURL()
    {
        $query = http_build_query($this->params);
        return urlencode(static::WXPAY_URL.'?'.$query);
    }
}