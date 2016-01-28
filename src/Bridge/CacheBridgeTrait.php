<?php

namespace Thenbsp\Wechat\Bridge;

use Doctrine\Common\Cache\Cache;

trait CacheBridgeTrait
{
    /**
     * Doctrine\Common\Cache\Cache
     */
    protected $cacheDriver;

    /**
     * 设置缓存驱动
     */
    public function setCacheDriver(Cache $cacheDriver)
    {
        $this->cacheDriver = $cacheDriver;
    }

    /**
     * 获取缓存驱动
     */
    public function getCacheDriver()
    {
        return $this->cacheDriver;
    }

    /**
     * 清除缓存
     */
    public function clearFromCache()
    {
        if( $this->cacheDriver ) {
            $this->cacheDriver->delete($this->getCacheId());
        }
    }
}
