<?php

namespace Thenbsp\Wechat\Message;

use Thenbsp\Wechat\Util\Option;
use Thenbsp\Wechat\Util\Response;
use Thenbsp\Wechat\Util\Serialize;


abstract class Entity extends Option
{
    /**
     * 构造方法
     */
    public function __construct(array $options = array())
    {
        $options['MsgType'] = $this->getType();

        parent::__construct($options);
    }

    /**
     * 获取实体数据
     */
    public function getData()
    {
        return Serialize::encode($this->options, 'xml');
    }

    /**
     * 发送消息实体
     */
    public function send()
    {
        $headers = array(
            'Content-Type' => 'application/xml'
        );

        $response = new Response($this->getData(), $headers);
        $response->send();
    }

    /**
     * 获取实体类型
     */
    abstract function getType();
}
