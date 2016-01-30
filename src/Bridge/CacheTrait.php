<?php

namespace Thenbsp\Wechat\Bridge;

trait CacheTrait
{
    /**
     * Doctrine\Common\Cache\Cache
     */
    protected $cache;

    /**
     * 设置缓存驱动
     */
    public function setCache(\Doctrine\Common\Cache\Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * 获取缓存驱动
     */
    public function getCache()
    {
        return $this->cache;
    }
}
