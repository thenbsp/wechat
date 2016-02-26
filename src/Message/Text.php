<?php

namespace Thenbsp\Wechat\Message;

class Text extends Entity
{
    public function getType()
    {
        $namespace = explode('\\', __CLASS__);
        
        return strtolower(end($namespace));
    }
}
