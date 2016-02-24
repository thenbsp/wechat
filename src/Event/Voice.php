<?php

namespace Thenbsp\Wechat\Event;

class Voice extends Events
{
    public function isValid()
    {
        return (strtolower($this->options['MsgType']) === 'voice');
    }
}
