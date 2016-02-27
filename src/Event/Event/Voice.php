<?php

namespace Thenbsp\Wechat\Event\Event;

use Thenbsp\Wechat\Event\Event;

class Voice extends Event
{
    public function isValid()
    {
        return ($this['MsgType'] === 'voice');
    }
}
