<?php

namespace Thenbsp\Wechat\Bridge;

use Psr\Log\LoggerInterface;

trait LoggerBridge
{
    /**
     * Psr\Log\LoggerInterface
     */
    protected $loggerBridge;

    /**
     * 设置日志驱动
     */
    public function setLoggerBridge(LoggerInterface $logger)
    {
        $this->loggerBridge = $logger;
    }

    /**
     * 获取日志驱动
     */
    public function getLoggerBridge()
    {
        return $this->loggerBridge;
    }
}
