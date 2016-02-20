<?php

namespace Thenbsp\Wechat\Message;

use Doctrine\Common\Collections\ArrayCollection;

abstract class Entity extends ArrayCollection
{
    /**
     * 获取实体类型
     */
    abstract function getType();
}
