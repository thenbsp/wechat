<?php

namespace Thenbsp\Wechat\Event;

class Image extends Events
{
    public function isValid()
    {
        return (strtolower($this->options['MsgType']) === 'image');
    }
}
