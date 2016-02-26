<?php

namespace Thenbsp\Wechat\Event;

use Thenbsp\Wechat\Bridge\Serializer;
use Thenbsp\Wechat\Bridge\XmlResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;

abstract class Entity extends ArrayCollection
{
    /**
     * send entity
     */
    public function send()
    {
        $response = new XmlResponse($this->toArray());
        $response->send();
    }

    /**
     * check entity options is valid event
     */
    abstract public function isValid();
}
