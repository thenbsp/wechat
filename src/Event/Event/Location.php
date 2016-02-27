<?php

namespace Thenbsp\Wechat\Event\Event;

use Thenbsp\Wechat\Event\Event;

class Location extends Event
{
    public function isValid()
    {
        return ($this['MsgType'] === 'location');
    }
}
