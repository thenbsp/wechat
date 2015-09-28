<?php

namespace Thenbsp\Wechat;

use Thenbsp\Wechat\AccessToken;
use Thenbsp\Wechat\Util\Http;

class Ticket
{
    /**
     * Ticket 接口地址
     */
    const TICKET_URL = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket';

    /**
     * AccessToken 对象
     */
    protected $accessToken;

    /**
     * 构造方法
     */
    public function __construct(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * 获取 Ticket
     */
    public function getTicket($type = 'jsapi')
    {
        $type = strtolower($type);

        if( !in_array($type, array('jsapi', 'wx_card'), true) ) {
            throw new \InvalidArgumentException(sprintf('Invalid Ticket Type: %s', $type));
        }

        $key    = $this->_getCacheName($type);
        $cache  = $this->accessToken->getCache();

        if( $value = $cache->get($key) ) {
            if( array_key_exists('ticket', $value) &&
                array_key_exists('expires_in', $value) ) {
                return $value['ticket'];
            }
        }

        $value = $this->_getTicket($type);

        // set cache
        $cache->set($key, $value, $value['expires_in']);

        return $value['ticket'];
    }

    /**
     * 获取 Ticket（从 API 获取）
     */
    protected function _getTicket($type)
    {
        $request = Http::get(self::TICKET_URL, array(
            'query' => array(
                'access_token'  => $this->accessToken->getAccessToken(),
                'type'          => $type
            )
        ));

        $response = $request->json();

        if( array_key_exists('ticket', $response) &&
            array_key_exists('expires_in', $response) ) {
            return $response;
        }

        throw new \Exception($response['errcode'].': '.$response['errmsg']);
    }

    /**
     * 获取缓存名称
     */
    protected function _getCacheName($type)
    {
        $wechat = $this->accessToken->getWechat();
        
        return $wechat['appid'].'_ticket_'.$type;
    }
}
