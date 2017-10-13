<?php

namespace Thenbsp\Wechat\Event\Event;

use Thenbsp\Wechat\Event\Event;

class UserLocation extends Event
{
    public function isValid()
    {
        return ('event' === $this['MsgType'])
            && ('LOCATION' === $this['Event']);
    }
}
