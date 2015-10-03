<?php

namespace Thenbsp\Wechat\Message\Event;

use Thenbsp\Wechat\Message\Event;

class Voice extends Event
{
    /**
     * 构造方法
     */
    public function __construct(array $options = array())
    {
        parent::__construct($options);
    }

    /**
     * 配置参数
     */
    protected function configureOptions($resolver)
    {
        $required   = array('ToUserName', 'FromUserName', 'CreateTime', 'MsgType', 'MediaId', 'Format', 'MsgId');
        $defined    = array_merge($required, array('Recognition'));

        $resolver
            ->setDefined($defined)
            ->setRequired($required);
    }
}
