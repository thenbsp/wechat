<?php

namespace Thenbsp\Wechat\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;

abstract class Events extends Event
{
    /**
     * Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * Doctrine\Common\Collections\ArrayCollection
     */
    protected $options;

    /**
     * initialize options from request
     */
    public function __construct(Request $request, array $options)
    {
        $this->request = $request;
        $this->options = new ArrayCollection($options);
    }

    /**
     * get Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * get options
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * check options
     */
    abstract public function isValid();
}
