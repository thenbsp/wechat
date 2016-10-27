<?php

namespace Thenbsp\Wechat\OAuth;

class StateManager
{
    const SESSION_NAMESPACE = '_thenbsp_oauth_state';

    private $namespace;
    private $isSessionStarted = false;

    public function __construct($namespace = self::SESSION_NAMESPACE)
    {
        $this->namespace = $namespace;
    }

    public function setState($state)
    {
        if (!$this->isSessionStarted) {
            $this->startSession();
        }

        $_SESSION[$this->namespace] = (string) $state;
    }

    public function getState()
    {
        if (!$this->isSessionStarted) {
            $this->startSession();
        }

        return $this->hasState()
            ? (string) $_SESSION[$this->namespace]
            : null;
    }

    public function hasState()
    {
        if (!$this->isSessionStarted) {
            $this->startSession();
        }

        return isset($_SESSION[$this->namespace]);
    }

    public function removeState()
    {
        if (!$this->isSessionStarted) {
            $this->startSession();
        }

        if ($this->hasState()) {
            unset($_SESSION[$this->namespace]);
        }
    }

    public function isValid($state)
    {
        if (!$this->hasState()) {
            return false;
        }

        return ($state === $this->getState());
    }

    private function startSession()
    {
        if (PHP_SESSION_NONE === session_status()) {
            session_start();
        }

        $this->isSessionStarted = true;
    }
}
