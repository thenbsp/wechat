<?php

namespace Thenbsp\Wechat\Event\Entity;

use Thenbsp\Wechat\Event\Entity;

class EventUnsubscribe extends Entity
{
    public function isValid()
    {
        return ($this['MsgType'] === 'event')
            && ($this['Event'] === 'unsubscribe');
    }
}
