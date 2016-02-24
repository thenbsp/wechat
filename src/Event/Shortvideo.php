<?php

namespace Thenbsp\Wechat\Event;

class Shortvideo extends Events
{
    public function isValid()
    {
        return (strtolower($this->options['MsgType']) === 'shortvideo');
    }
}
