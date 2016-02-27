<?php

namespace Thenbsp\Wechat\Event;

class EventListener implements EventListenerInterface
{
    /**
     * list of listeners
     */
    protected $listeners = array();

    /**
     * trigger event
     */
    public function trigger($handler, Event $event)
    {
        if( $listener = $this->getListener($handler) ) {
            return call_user_func_array($listener, array($event));
        }
    }

    /**
     * add listener
     */
    public function addListener($handler, callable $callable)
    {
        if( !class_exists($handler) ) {
            throw new \InvalidArgumentException(sprintf('Invlaid Handler "%s"', $handler));
        }

        if( !is_subclass_of($handler, Event::class) ) {
            throw new \InvalidArgumentException(sprintf(
                'The Handler "%s" must be extends "%s"', $handler, Event::class));
        }

        $this->listeners[$handler] = $callable;

        return $this;
    }

    /**
     * get listener
     */
    public function getListener($handler)
    {
        if( $this->hasListener($handler) ) {
            return $this->listeners[$handler];
        }
    }

    /**
     * has listener
     */
    public function hasListener($handler)
    {
        return array_key_exists($handler, $this->listeners);
    }

    /**
     * remove listener
     */
    public function removeListener($handler)
    {
        if( $this->hasListener($handler) ) {
            unset($this->listeners[$handler]);
        }
    }

    /**
     * get listeners
     */
    public function getListeners()
    {
        return $this->listeners;
    }
}
