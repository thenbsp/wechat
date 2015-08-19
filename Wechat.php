<?php

namespace Thenbsp\Wechat;

use Thenbsp\Wechat\Util\Cache;
use Thenbsp\Wechat\Util\Request;
use Thenbsp\Wechat\Exception\WechatException;
use Thenbsp\Wechat\Exception\TicketException;
use Thenbsp\Wechat\Exception\AccessTokenException;

/**
 * 微信 SDK 核心类，提供访问公众号自身数据的能力，比如 AccessToken、Ticket 等
 * Created by thenbsp (thenbsp@gmail.com)
 * Created at 2015/07/23
 */
class Wechat
{
    /**
     * 公众号 AccessToken 接口地址
     */
    const ACCESS_TOKEN_URL = 'https://api.weixin.qq.com/cgi-bin/token';

    /**
     * 公众号 ticket 接口地址
     */
    const TICKET_URL = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket';

    /**
     * 获取公众号 IP 地址列表
     */
    const SERVERIP_URL = 'https://api.weixin.qq.com/cgi-bin/getcallbackip';

    /**
     * Cache 文件缓存
     */
    protected $cache;

    /**
     * 公众号 AppId
     */
    protected $appid;

    /**
     * 公众号 AppSecret
     */
    protected $appsecret;

    /**
     * 构造方法
     */
    public function __construct($appid, $appsecret, $cacheDir = null)
    {
        $this->appid        = $appid;
        $this->appsecret    = $appsecret;

        try {
            $this->cache = new Cache($cacheDir ?: __DIR__.DIRECTORY_SEPARATOR.'Storage');
        } catch (\Exception $e) {
            exit($e->getMessage()); 
        }
    }

    /**
     * 获取 Appid
     */
    public function getAppid()
    {
        return $this->appid;
    }

    /**
     * 获取 Appsecret
     */
    public function getAppsecret()
    {
        return $this->appsecret;
    }

    /**
     * 获取 AccessToken
     */
    public function getAccessToken()
    {
        $key    = $this->appid.'_access_token';
        $cache  = $this->cache->get($key);

        if( $cache && !(empty($cache->access_token) ||
            empty($cache->expires_in)) ) {
            if( $cache->expires_in > time() ) {
                // echo 'from cache';
                return $cache->access_token;
            }
        }

        // echo 'form api';
        $response = $this->getAccessTokenResponse();
        $response->expires_in += time();
 
        $this->cache->set($key, $response);

        return $response->access_token;
    }

    /**
     * 获取 Ticket
     */
    public function getTicket($type = 'jsapi')
    {
        $key    = $this->appid.'_ticket_'.$type;
        $cache  = $this->cache->get($key);

        if( $cache && !(empty($cache->ticket) ||
            empty($cache->expires_in)) ) {
            if( $cache->expires_in > time() ) {
                // echo 'from cache';
                return $cache->ticket;
            }
        }

        // echo 'form api';
        $response = $this->getTicketResponse($type);
        $response->expires_in += time();
 
        $this->cache->set($key, $response);

        return $response->ticket;
    }

    /**
     * 获取 AccessToken（从接口获取）
     */
    public function getAccessTokenResponse()
    {
        $params = array(
            'grant_type'    => 'client_credential',
            'appid'         => $this->appid,
            'secret'        => $this->appsecret
        );

        $response = Request::get(static::ACCESS_TOKEN_URL, $params);

        if( isset($response->access_token) &&
            isset($response->expires_in) ) {
            return $response;
        }

        throw new AccessTokenException($response->errcode.': '.$response->errmsg);
    }

    /**
     * 获取 Ticket（从接口获取）
     */
    public function getTicketResponse($type)
    {
        try {
            $accessToken = $this->getAccessToken();
        } catch (AccessTokenException $e) {
            throw new TicketException($e->getMessage());
        }

        $params = array(
            'access_token'  => $accessToken,
            'type'          => $type,
        );

        $response = Request::get(static::TICKET_URL, $params);

        if( isset($response->ticket) &&
            isset($response->expires_in) ) {
            return $response;
        }

        throw new TicketException($response->errcode.': '.$response->errmsg);
    }

    /**
     * 获取微信服务器 IP
     */
    public function getServerIp()
    {
        try {
            $accessToken = $this->getAccessToken();
        } catch (AccessTokenException $e) {
            throw new WechatException($e->getMessage());
        }

        $params     = array('access_token' => $accessToken);
        $response   = Request::get(static::SERVERIP_URL, $params);

        if( isset($response->ip_list) ) {
            return $response->ip_list;
        }

        throw new WechatException($response->errcode.': '.$response->errmsg);
    }

}