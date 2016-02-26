<?php

namespace Thenbsp\Wechat\Event\Entity;

use Thenbsp\Wechat\Event\Entity;

class ShortVideo extends Entity
{
    public function isValid()
    {
        return (strtolower($this['MsgType']) === 'shortvideo');
    }
}
