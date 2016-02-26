<?php

namespace Thenbsp\Wechat\Event\Entity;

use Thenbsp\Wechat\Event\Entity;

class Location extends Entity
{
    public function isValid()
    {
        return (strtolower($this['MsgType']) === 'location');
    }
}
