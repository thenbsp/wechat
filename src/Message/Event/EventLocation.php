<?php

namespace Thenbsp\Wechat\Message\Event;

use Thenbsp\Wechat\Message\Event;
use Thenbsp\Wechat\Util\OptionValidator;

class EventLocation extends Event
{
    /**
     * 构造方法
     */
    public function __construct(array $options = array())
    {
        $required = array('ToUserName', 'FromUserName', 'CreateTime', 'MsgType', 'Event', 'Latitude', 'Longitude', 'Precision');

        $validator = new OptionValidator();
        $validator
            ->setRequired($required);

        $validtated = $validator->validate($options);

        parent::__construct($validtated);
    }
}
