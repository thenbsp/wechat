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

        $this->setRequest($request);
    }

    /**
     * set from request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * get from rquest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * handle event via request
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
            $event = new $namespace($options);
            if( $event->isValid() ) {
                $listener->trigger($namespace, $event);
                break;
            }
        }
    }
}
