<?php

namespace Thenbsp\Wechat\Wechat\Qrcode;

use Thenbsp\Wechat\Bridge\Http;
use Thenbsp\Wechat\Bridge\CacheTrait;
use Thenbsp\Wechat\Wechat\AccessToken;

class Ticket
{
    /**
     * Cache Trait
     */
    use CacheTrait;

    /**
     * http://mp.weixin.qq.com/wiki/18/167e7d94df85d8389df6c94a7a8f78ba.html
     */
    const TICKET_URL = 'https://api.weixin.qq.com/cgi-bin/qrcode/create';

    /**
     * 二维码类型
     */
    const QR_SCENE              = 'QR_SCENE';
    const QR_LIMIT_SCENE        = 'QR_LIMIT_SCENE';
    const QR_LIMIT_STR_SCENE    = 'QR_LIMIT_STR_SCENE';

    /**
     * Thenbsp\Wechat\Wechat\AccessToken
     */
    protected $accessToken;

    /**
     * 二维码类型
     */
    protected $type;

    /**
     * 二维码场景值
     */
    protected $scene;

    /**
     * 永久二维码因场景值类型不同，发送的 Key 也不同
     */
    protected $sceneKey;

    /**
     * 二维码有效期（临时二维码可用）
     */
    protected $expire;

    /**
     * 构造方法
     */
    public function __construct(AccessToken $accessToken, $type, $scene, $expire = 2592000)
    {
        $constraint = array(
            static::QR_SCENE            => 'integer',
            static::QR_LIMIT_SCENE      => 'integer',
            static::QR_LIMIT_STR_SCENE  => 'string'
        );

        $type = strtoupper($type);

        if( !array_key_exists($type, $constraint) ) {
            throw new \InvalidArgumentException(sprintf('Invalid Qrcode Type: %s', $type));
        }

        $callback = sprintf('is_%s', $constraint[$type]);

        if( !call_user_func($callback, $scene) ) {
            throw new \InvalidArgumentException(sprintf(
                'parameter "scene" must be %s, %s given', $constraint[$type], gettype($scene)));
        }

        $this->type         = $type;
        $this->scene        = $scene;
        $this->sceneKey     = (is_int($scene) ? 'scene_id' : 'scene_str');
        $this->expire       = $expire;
        $this->accessToken  = $accessToken;
    }

    /**
     * 获取 Qrcode 票据（调用缓存，返回 String）
     */
    public function getTicketString()
    {
        $cacheId = $this->getCacheId();

        if( $this->cache && $data = $this->cache->fetch($cacheId) ) {
            return $data['ticket'];
        }

        $response = $this->getTicketResponse();

        if( $this->cache ) {
            $this->cache->save($cacheId, $response, $response['expire_seconds'] ?: 0);
        }

        return $response['ticket'];
    }

    /**
     * 获取 Qrcode 票据（不缓存，返回原始数据）
     */
    public function getTicketResponse()
    {
        $response = Http::request('POST', static::TICKET_URL)
            ->withAccessToken($this->accessToken)
            ->withBody($this->getRequestBody())
            ->send();

        if( $response['errcode'] != 0 ) {
            throw new \Exception($response['errmsg'], $response['errcode']);
        }

        return $response;
    }

    /**
     * 获取请求内容
     */
    public function getRequestBody()
    {
        $options = array(
            'action_name'   => $this->type,
            'action_info'   => array(
                'scene'     => array($this->sceneKey=>$this->scene)
            )
        );

        if( $options['action_name'] === static::QR_SCENE ) {
            $options['expire_seconds'] = $this->expire;
        }

        return $options;
    }

    /**
     * 从缓存中清除
     */
    public function clearFromCache()
    {
        return $this->cache
            ? $this->cache->delete($this->getCacheId())
            : false;
    }

    /**
     * 获取缓存 ID
     */
    public function getCacheId()
    {
        return implode('_', array($this->accessToken['appid'], $this->type, $this->sceneKey, $this->scene));
    }
}
