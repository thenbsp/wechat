<?php

namespace Thenbsp\Wechat\Message\Template;

use Thenbsp\Wechat\Bridge\Http;
use Thenbsp\Wechat\Bridge\Serializer;
use Thenbsp\Wechat\Wechat\AccessToken;

class Sender
{
    /**
     * 发送接口 URL
     */
    const SENDER = 'https://api.weixin.qq.com/cgi-bin/message/template/send';

    /**
     * Thenbsp\Wechat\Wechat\AccessToken
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
     * 发送模板消息
     */
    public function send(TemplateInterface $template)
    {
        $response = Http::request('POST', static::SENDER)
            ->withAccessToken($this->accessToken)
            ->withBody($template->getRequestBody())
            ->send();

        if( $response['errcode'] != 0 ) {
            throw new \Exception($response['errmsg'], $response['errcode']);
        }

        return $response['msgid'];
    }
}
