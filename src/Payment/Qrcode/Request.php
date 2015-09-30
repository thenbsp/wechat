<?php

namespace Thenbsp\Wechat\Payment\Qrcode;

use Thenbsp\Wechat\Util\Serialize;
use Thenbsp\Wechat\Util\OptionAccess;

class Request extends OptionAccess
{
    /**
     * 请求原始内容 rawContent
     */
    protected $content;

    /**
     * 检测是否有效
     */
    protected $valid;

    /**
     * 如果检测失败，则包含错误消息
     */
    protected $error;

    /**
     * 构造方法
     */
    public function __construct()
    {
        $this->content = file_get_contents('php://input');

        if( !empty($this->content) ) {
            $data = Serialize::decode($this->content, 'xml');
            try {
                parent::__construct($data);
                $this->valid = true;
            } catch (\InvalidArgumentException $e) {
                $this->error = $e->getMessage();
            }
        }
    }

    /**
     * 获取请求数据
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * 检测是否有效
     */
    public function isValid()
    {
        return (true === $this->valid);
    }

    /**
     * 获取错误消息
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 配置选项
     */
    protected function configureOptions($resolver)
    {
        $defined = array('appid', 'openid', 'mch_id', 'is_subscribe', 'nonce_str', 'product_id', 'sign');

        $resolver
            ->setDefined($defined)
            ->setRequired($defined);
    }
}
