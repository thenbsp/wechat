<?php

namespace Thenbsp\Wechat\OAuth;

use Thenbsp\Wechat\OAuth\AccessToken;
use Thenbsp\Wechat\Util\Http;
use Thenbsp\Wechat\Util\Option;
use Thenbsp\Wechat\Util\OptionValidator;

class User extends Option
{
    /**
     * 网页授权获取用户信息
     */
    const USERINFO_URL = 'https://api.weixin.qq.com/sns/userinfo';

    /**
     * 构造方法
     */
    public function __construct(AccessToken $accessToken)
    {
        if( !$accessToken->isValid() ) {
            $accessToken->refresh();
        }

        $request = Http::get(self::USERINFO_URL, array(
            'query' => array(
                'access_token' => $accessToken['access_token'],
                'openid' => $accessToken['openid'],
            )
        ));

        $response = $request->json();

        if( array_key_exists('errcode', $response) &&
            array_key_exists('errmsg', $response) ) {
            throw new \Exception($response['errcode'].': '.$response['errmsg']);
        }

        $required   = array('openid', 'nickname', 'sex', 'language', 'city', 'province', 'country', 'headimgurl');
        $defined    = array_merge($required, array('privilege', 'unionid'));

        $validator = new OptionValidator();
        $validator
            ->setDefined($defined)
            ->setRequired($required);

        $validated = $validator->validate($response);

        parent::__construct($validated);
    }
}
