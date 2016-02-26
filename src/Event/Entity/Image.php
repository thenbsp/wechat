<?php

namespace Thenbsp\Wechat\Event\Entity;

use Thenbsp\Wechat\Event\Entity;

class Image extends Entity
{
    public function isValid()
    {
        return (strtolower($this['MsgType']) === 'image');
    }
}
