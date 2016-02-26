<?php

namespace Thenbsp\Wechat\Message;

use Thenbsp\Wechat\Bridge\Util;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class Entity extends ArrayCollection
{
    protected $event;

    public function __construct(Event $event = null)
    {
        if( !is_null($event) ) {
            $this->set('ToUserName',    $event['ToUserName']);
            $this->set('FromUserName',  $event['FromUserName']);
        }

        $this->set('CreateTime', Util::getTimestamp());
    }

    abstract public function getType();
}
