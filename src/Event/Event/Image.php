<?php

namespace Thenbsp\Wechat\Event\Event;

use Thenbsp\Wechat\Event\Event;

class Image extends Event
{
    public function isValid()
    {
        return ($this['MsgType'] === 'image');
    }
}
