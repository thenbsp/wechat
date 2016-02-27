<?php

namespace Thenbsp\Wechat\Event\Event;

use Thenbsp\Wechat\Event\Event;

class Link extends Event
{
    public function isValid()
    {
        return ($this['MsgType'] === 'link');
    }
}
