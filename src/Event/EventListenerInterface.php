<?php

namespace Thenbsp\Wechat\Event;

interface EventListenerInterface
{
    /**
     * trigger event
     */
    public function trigger($handler, Event $entity);

    /**
     * add listener
     */
    public function addListener($handler, callable $callable);

    /**
     * get listener
     */
    public function getListener($handler);

    /**
     * has listener
     */
    public function hasListener($handler);

    /**
     * remove listener
     */
    public function removeListener($handler);

    /**
     * get listeners
     */
    public function getListeners();
}
