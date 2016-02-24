<?php

namespace Thenbsp\Wechat\Event;

class Link extends Events
{
    public function isValid()
    {
        return (strtolower($this->options['MsgType']) === 'link');
    }
}
