<?php

namespace Thenbsp\Wechat\Event;

use Thenbsp\Wechat\Bridge\Serializer;
use Symfony\Component\HttpFoundation\Request;

class EventHandler implements EventHandlerInterface
{
    /**
     * Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * initialize request
     */
    public function __construct(Request $request = null)
    {
        $request = $request ?: Request::createFromGlobals();

        $this->fromRequest($request);
    }

    /**
     * set from request
     */
    public function fromRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * handle event
     */
    public function handle(EventListenerInterface $listener)
    {
        if( !$listener->getListeners() ) {
            return;
        }

        $content = $this->request->getContent();

        try {
            $options = Serializer::parse($content);
        } catch (\InvalidArgumentException $e) {
            $options = array();
        }

        foreach( $listener->getListeners() as $namespace => $callable ) {
            $handler = new $namespace($options);
            if( $handler->isValid() ) {
                $callback = $listener->trigger($namespace, $handler);
                if( $callback instanceof Entity ) {
                    $callback->send();
                }
                break;
            }
        }
    }
}
