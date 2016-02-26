<?php

namespace Thenbsp\Wechat\Event\Entity;

use Thenbsp\Wechat\Event\Entity;

class Voice extends Entity
{
    public function isValid()
    {
        return (strtolower($this['MsgType']) === 'voice');
    }
}
