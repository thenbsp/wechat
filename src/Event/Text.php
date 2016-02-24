<?php

namespace Thenbsp\Wechat\Event;

class Text extends Events
{
    public function isValid()
    {
        return (strtolower($this->options['MsgType']) === 'text');
    }
}
