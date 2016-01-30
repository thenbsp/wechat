<?php

namespace Thenbsp\Wechat\OAuth;

use Thenbsp\Wechat\Bridge\Http;
use Thenbsp\Wechat\OAuth\AccessToken;
use Thenbsp\Wechat\OAuth\Exception\OAuthUserException;
use Doctrine\Common\Collections\ArrayCollection;

class Userinfo extends ArrayCollection
{
    /**
     * 网页授权获取用户信息
     */
    const USERINFO = 'https://api.weixin.qq.com/sns/userinfo';

    /**
     * 构造方法
     */
    public function __construct(AccessToken $accessToken, $lang = 'zh_CN')
    {
        if( !$accessToken->isValid() ) {
            $accessToken->refresh();
        }

        $query = array(
            'access_token'  => $accessToken['access_token'],
            'openid'        => $accessToken['openid'],
            'lang'          => $lang
        );

        $response = Http::request('GET', static::USERINFO)
            ->withQuery($query)
            ->send();

        if( array_key_exists('errcode', $response)
            && ($response['errcode'] != 0) ) {
            throw new OAuthUserException($response['errmsg'], $response['errcode']);
        }

        parent::__construct($response);
    }
}
