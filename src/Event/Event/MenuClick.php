<?php

namespace Thenbsp\Wechat\Event\Event;

use Thenbsp\Wechat\Event\Event;

class MenuClick extends Event
{
    public function isValid()
    {
        return ('event' === $this['MsgType'])
            && ('CLICK' === $this['Event']);
    }
}
