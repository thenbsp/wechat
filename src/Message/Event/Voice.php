<?php

namespace Thenbsp\Wechat\Message\Event;

use Thenbsp\Wechat\Message\Event;
use Thenbsp\Wechat\Util\OptionValidator;

class Voice extends Event
{
    /**
     * 构造方法
     */
    public function __construct(array $options = array())
    {
        $required   = array('ToUserName', 'FromUserName', 'CreateTime', 'MsgType', 'MediaId', 'Format', 'MsgId');
        $defined    = array_merge($required, array('Recognition'));

        $validator = new OptionValidator();
        $validator
            ->setDefined($defined)
            ->setRequired($required);

        $validtated = $validator->validate($options);

        parent::__construct($validtated);
    }
}
