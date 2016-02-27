<?php

namespace Thenbsp\Wechat\Event;

use Symfony\Component\HttpFoundation\Request;

interface EventHandlerInterface
{
    /**
     * set request
     */
    public function setRequest(Request $request);

    /**
     * get request
     */
    public function getRequest();

    /**
     * handle event via request
     */
    public function handle(EventListenerInterface $listener);
}
