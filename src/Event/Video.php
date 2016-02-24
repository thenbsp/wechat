<?php

namespace Thenbsp\Wechat\Event;

class Video extends Events
{
    public function isValid()
    {
        return (strtolower($this->options['MsgType']) === 'video');
    }
}
