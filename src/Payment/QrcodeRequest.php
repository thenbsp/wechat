<?php

namespace Thenbsp\Wechat\Payment;

use Thenbsp\Wechat\Util\Request;
use Thenbsp\Wechat\Util\Serialize;
use Thenbsp\Wechat\Util\Option;
use Thenbsp\Wechat\Util\OptionValidator;

class QrcodeRequest extends Option
{
    /**
     * 请求对象
     */
    protected $request;

    /**
     * 消误消息
     */
    protected $error;

    /**
     * 构造方法
     */
    public function __construct()
    {
        $request = Request::createFromGlobals();
        $content = $request->getContent();

        if( !empty($content) ) {
            $options = Serialize::decode($content, 'xml');
            $this->setOptions($options);
        }

        $this->request = $request;
    }

    /**
     * 获取请求对象
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * 获取请求原内容
     */
    public function getContent()
    {
        return $this->request->getContent();
    }

    /**
     * 获取错误消息
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 检测本次请求是有有效
     */
    public function isValid()
    {
        if( empty($this->options) ) {
            $this->error = 'Invalid Request';
            return false;
        }

        $required = array('appid', 'openid', 'mch_id', 'is_subscribe', 'nonce_str', 'product_id', 'sign');

        $validator = new OptionValidator();
        $validator
            ->setRequired($required);

        try {
            $validator->validate($this->options);
        } catch (\InvalidArgumentException $e) {
            $this->error = $e->getMessage();
            return false;
        }

        return true;
    }
}