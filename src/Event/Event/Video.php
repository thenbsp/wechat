<?php

namespace Thenbsp\Wechat\Event\Event;

use Thenbsp\Wechat\Event\Event;

class Video extends Event
{
    public function isValid()
    {
        return ($this['MsgType'] === 'video');
    }
}
