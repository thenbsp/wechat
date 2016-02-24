<?php

namespace Thenbsp\Wechat\Event;

use Thenbsp\Wechat\Bridge\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventManager
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
        $this->request = $request ?: Request::createFromGlobals();
    }

    /**
     * handle event
     */
    public function handle(EventDispatcherInterface $dispatcher)
    {
        $content = $this->request->getContent();

        try {
            $options = Serializer::parse($content);
        } catch (\InvalidArgumentException $e) {
            $options = array();
        }

        $listeners = $dispatcher->getListeners();

        foreach( $listeners as $namespace => $callable ) {
            $class = new $namespace($this->request, $options);
            if( $class->isValid() ) {
                $dispatcher->dispatch($namespace, $class);
                break;
            }
        }
    }
}
