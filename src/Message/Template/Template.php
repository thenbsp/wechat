<?php

namespace Thenbsp\Wechat\Message\Template;

use Thenbsp\Wechat\Util\Http;
use Thenbsp\Wechat\Util\Serialize;
use Thenbsp\Wechat\AccessToken;
use Thenbsp\Wechat\Message\Template\TemplateOption;

class Template
{
    /**
     * 发送接口 URL
     */
    const SEND_URL = 'https://api.weixin.qq.com/cgi-bin/message/template/send';

    /**
     * 全局 AccessToken
     */
    protected $accessToken;

    /**
     * 用户 ID
     */
    protected $touser;

    /**
     * 模板 ID
     */
    protected $templateId;

    /**
     * 跳转 URL
     */
    protected $url;

    /**
     * 选项参数
     * Thenbsp\Wechat\Message\TemplateOption
     */
    protected $options;

    /**
     * 构造方法
     */
    public function __construct(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * 设置用户 ID
     */
    public function setTouser($touser)
    {
        $this->touser = $touser;
    }

    /**
     * 获取用户 ID
     */
    public function getTouser()
    {
        return $this->touser;
    }

    /**
     * 设置模板 ID
     */
    public function setTemplateId($templateId)
    {
        $this->templateId = $templateId;
    }

    /**
     * 获取模板 ID
     */
    public function getTemplateId()
    {
        return $this->templateId;
    }

    /**
     * 设置跳转 URL
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * 获取跳转 URL
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * 设置选项参数
     */
    public function setOptions(TemplateOption $options)
    {
        $this->options = $options;
    }

    /**
     * 获取选项参数
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * 发送模板消息
     */
    public function send()
    {
        if( empty($this->options) || $this->options->isEmpty() ) {
            throw new \Exception('Options is requried');
        }

        $body = array(
            'touser'        => $this->touser,
            'template_id'   => $this->templateId,
            'url'           => $this->url,
            'data'          => $this->options->toArray()
        );

        $request = Http::post(self::SEND_URL, array(
            'body'  => Serialize::encode($body, 'json'),
            'query' => array(
                'access_token' => $this->accessToken->getAccessToken()
            )
        ));

        $response = $request->json();

        if( array_key_exists('errcode', $response) &&
            ($response['errcode'] != 0) ) {
            throw new \Exception($response['errcode'].': '.$response['errmsg']);
        }

        return $response['msgid'];
    }
}
