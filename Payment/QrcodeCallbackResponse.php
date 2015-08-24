<?php

namespace Thenbsp\Wechat\Payment;

use Thenbsp\Wechat\Util\Bag;
use Thenbsp\Wechat\Util\Util;
use Thenbsp\Wechat\Util\SignGenerator;
use Thenbsp\Wechat\Exception\PaymentException;

class QrcodeCallbackResponse
{
    /**
     * 参数包
     */
    protected $bag;

    /**
     * 必填项目
     */
    protected $required = array('appid', 'mch_id', 'nonce_str', 'prepay_id', 'result_code');

    /**
     * 选填项目
     */
    protected $optional = array('return_msg', 'err_code_des');

    /**
     * 构造方法
     */
    public function __construct(Bag $bag, $key)
    {
        // 检测必填字段
        foreach($this->required AS $paramName) {
            if( !$bag->has($paramName) ) {
                throw new PaymentException(sprintf('"%s" is required', $paramName));
            }
        }

        // 生成签名
        $signGenerator = new SignGenerator($bag);
        $signGenerator->onSortAfter(function($that) use ($key) {
            $that->set('key', $key);
        });

        $bag->set('sign', $signGenerator->getResult());
        $bag->remove('key');

        $this->bag = $bag;
    }

    /**
     * 发送响应
     */
    public function send()
    {
        $response = $this->bag->all();
        $response = Util::array2XML($response);

        echo $response;
    }
}