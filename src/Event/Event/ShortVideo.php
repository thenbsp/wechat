<?php

namespace Thenbsp\Wechat\Event\Event;

use Thenbsp\Wechat\Event\Event;

class ShortVideo extends Event
{
    public function isValid()
    {
        return ($this['MsgType'] === 'shortvideo');
    }
}
