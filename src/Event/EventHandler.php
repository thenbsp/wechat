<?php

namespace Thenbsp\Wechat\Event;

use Psr\Log\LoggerAwareTrait;
use Thenbsp\Wechat\Bridge\Serializer;
use Symfony\Component\HttpFoundation\Request;

class EventHandler implements EventHandlerInterface
{
    /**
     * use Logger Trait
     */
    use LoggerAwareTrait;

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
     * set request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * get request
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

        if( $this->logger ) {
            $this->logger->debug($content);
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
