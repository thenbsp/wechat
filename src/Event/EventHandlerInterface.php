<?php

namespace Thenbsp\Wechat\Event;

use Symfony\Component\HttpFoundation\Request;

interface EventHandlerInterface
{
    /**
     * set from request
     */
    public function fromRequest(Request $request);

    /**
     * handle event
     */
    public function handle(EventListenerInterface $listener);
}
