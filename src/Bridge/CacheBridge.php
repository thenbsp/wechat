<?php

namespace Thenbsp\Wechat\Bridge;

use Doctrine\Common\Cache\Cache;

trait CacheBridge
{
    /**
     * Doctrine\Common\Cache\Cache
     */
    protected $cacheBridge;

    /**
     * 设置缓存驱动
     */
    public function setCacheBridge(Cache $cache)
    {
        $this->cacheBridge = $cache;
    }

    /**
     * 获取缓存驱动
     */
    public function getCacheBridge()
    {
        return $this->cacheBridge;
    }

    /**
     * 将数据写入到缓存
     */
    public function saveToCache($data, $expires = 0)
    {
        if( $this->cacheBridge ) {
            $this->cacheBridge->save($this->getCacheId(), $data, $expires);
        }
    }

    /**
     * 从缓存中获取
     */
    public function getFromCache()
    {
        $cacheId = $this->getCacheId();

        return ($this->cacheBridge && ($data = $this->cacheBridge->fetch($cacheId)))
            ? $data
            : false;
    }

    /**
     * 从缓存中清除
     */
    public function clearFromCache()
    {
        if( $this->cacheBridge ) {
            $this->cacheBridge->delete($this->getCacheId());
        }
    }
}
