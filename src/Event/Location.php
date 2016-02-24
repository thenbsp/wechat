<?php

namespace Thenbsp\Wechat\Event;

class Location extends Events
{
    public function isValid()
    {
        return (strtolower($this->options['MsgType']) === 'location');
    }
}
