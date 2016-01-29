<?php

namespace Thenbsp\Wechat\Wechat;

use Thenbsp\Wechat\Bridge\Http;
use Thenbsp\Wechat\Bridge\CacheBridge;
use Thenbsp\Wechat\Bridge\CacheBridgeInterface;
use Thenbsp\Wechat\Wechat\AccessToken;
use Thenbsp\Wechat\Wechat\Exception\QrcodeException;

class Qrcode implements CacheBridgeInterface
{
    /**
     * Cache Bridge
     */
    use CacheBridge;

    /**
     * http://mp.weixin.qq.com/wiki/18/167e7d94df85d8389df6c94a7a8f78ba.html
     */
    const TICKET_URL = 'https://api.weixin.qq.com/cgi-bin/qrcode/create';

    /**
     * 二维码地址
     */
    const QRCODE_URL = 'https://mp.weixin.qq.com/cgi-bin/showqrcode';

    /**
     * 二维码类型
     */
    const QR_SCENE              = 'QR_SCENE';
    const QR_LIMIT_SCENE        = 'QR_LIMIT_SCENE';
    const QR_LIMIT_STR_SCENE    = 'QR_LIMIT_STR_SCENE';

    /**
     * Thenbsp\Wechat\AccessToken\AccessToken
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
        $this->expire       = $expire;
        $this->accessToken  = $accessToken;
    }

    /**
     * 获取二维码票据
     */
    public function getTicket()
    {
        if( $data = $this->getFromCache() ) {
            return $data;
        }

        $response = Http::request('POST', static::TICKET_URL)
            ->withAccessToken($this->accessToken)
            ->withBody($this->getRequestBody())
            ->send();

        if( array_key_exists('errcode', $response) ) {
            throw new QrcodeException($response['errmsg'], $response['errcode']);
        }

        $expire = array_key_exists('expire_seconds', $response)
            ? $response['expire_seconds']
            : 0;

        $this->saveToCache($response, $expire);

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
                'scene'     => array($this->getSceneKey()=>$this->scene)
            )
        );

        if( $options['action_name'] === static::QR_SCENE ) {
            $options['expire_seconds'] = $this->expire;
        }

        return $options;
    }

    /**
     * 永久二维码因场景值类型不同，发送的 Key 也不同
     */
    public function getSceneKey()
    {
        return  (is_int($this->scene) ? 'scene_id' : 'scene_str');
    }

    /**
     * 获取二维码资源链接
     */
    public function getResourceUrl()
    {
        $ticket = $this->getTicket();

        return $this->buildResourceUrl($ticket['ticket']);
    }

    /**
     * 创建二维码资源链接
     */
    public function buildResourceUrl($ticket)
    {
        return static::QRCODE_URL.'?'.http_build_query(array('ticket'=>$ticket));
    }

    /**
     * 获取缓存 ID
     */
    public function getCacheId()
    {
        return implode('_', array($this->accessToken['appid'], $this->type, $this->getSceneKey(), $this->scene));
    }
}
