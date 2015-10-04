<?php

namespace Thenbsp\Wechat\OAuth;

use Thenbsp\Wechat\Wechat;
use Thenbsp\Wechat\Util\Http;
use Thenbsp\Wechat\Util\Option;
use Thenbsp\Wechat\Util\OptionValidator;

class AccessToken extends Option
{
    /**
     * 刷新
     */
    const REFRESH_URL= 'https://api.weixin.qq.com/sns/oauth2/refresh_token';

    /**
     * 检测是否有效
     */
    const CHECK_URL = 'https://api.weixin.qq.com/sns/auth';

    /**
     * Wechat 对象
     */
    protected $wechat;

    /**
     * 构造方法
     */
    public function __construct(Wechat $wechat, array $options)
    {
        $this->wechat = $wechat;

        $required   = array('access_token', 'expires_in', 'refresh_token', 'openid', 'scope');
        $defined    = array_merge($required, array('unionid'));

        $validator = new OptionValidator();
        $validator
            ->setDefined($defined)
            ->setRequired($required);

        $validated = $validator->validate($options);

        parent::__construct($validated);
    }

    /**
     * 刷新
     */
    public function refresh()
    {
        $request = Http::get(self::REFRESH_URL, array(
            'query' => array(
                'appid'         => $this->wechat['appid'],
                'grant_type'    => 'refresh_token',
                'refresh_token' => $this->options['refresh_token']
            )
        ));

        $response = $request->json();

        if( array_key_exists('access_token', $response) &&
            array_key_exists('openid', $response) ) {
            parent::__construct($response);
            return $this;
        }

        throw new \Exception($response['errcode'].': '.$response['errmsg']);
    }

    /**
     * 检测是否有效
     */
    public function isValid()
    {
        $request = Http::get(self::CHECK_URL, array(
            'query' => array(
                'access_token'  => $this->options['access_token'],
                'openid'        => $this->options['openid']
            )
        ));

        $response = $request->json();

        return ($response['errmsg'] === 'ok');
    }
}
