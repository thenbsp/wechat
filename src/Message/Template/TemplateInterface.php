<?php

namespace Thenbsp\Wechat\Message\Template;

interface TemplateInterface
{
    /**
     * 获取模板 ID
     */
    public function getId();

    /**
     * 设置链接
     */
    public function setUrl($url);

    /**
     * 获取逻接
     */
    public function getUrl();

    /**
     * 设置用户 Openid
     */
    public function setOpenid($openid);

    /**
     * 获取用户 Openid
     */
    public function getOpenid();

    /**
     * 添加模板参数
     */
    public function add($key, $value, $color = null);

    /**
     * 移除模板参数
     */
    public function remove($key);

    /**
     * 获取请求内容
     */
    public function getRequestBody();
}
