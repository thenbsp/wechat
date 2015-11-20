<?php

namespace Thenbsp\Wechat\Message\Event;

use Thenbsp\Wechat\Message\Event;
use Thenbsp\Wechat\Util\OptionValidator;

class EventUnsubscribe extends Event
{
    /**
     * 构造方法
     */
    public function __construct(array $options = array())
    {
        $required = array('ToUserName', 'FromUserName', 'CreateTime', 'MsgType', 'Event');
        $defined    = array_merge($required, array('EventKey'));

        $validator = new OptionValidator();
        $validator
            ->setRequired($required)
            ->setDefined($defined);

        $validtated = $validator->validate($options);

        $this->setOptions($validtated);
    }
}
