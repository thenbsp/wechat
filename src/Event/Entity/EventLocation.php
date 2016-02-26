<?php

namespace Thenbsp\Wechat\Event\Entity;

use Thenbsp\Wechat\Event\Entity;

class EventLocation extends Entity
{
    public function isValid()
    {
        return ($this['MsgType'] === 'event')
            && ($this['Event'] === 'LOCATION');
    }
}
