<?php

namespace Thenbsp\Wechat\Menu;

use Thenbsp\Wechat\Bridge\Http;
use Thenbsp\Wechat\Wechat\AccessToken;

class Create
{
    /**
     * 接口地址
     */
    const CREATE_URL = 'https://api.weixin.qq.com/cgi-bin/menu/create';

    /**
     * 一级菜单不能超过 3 个
     */
    const MAX_COUNT = 3;

    /**
     * Thenbsp\Wechat\Wechat\AccessToken
     */
    protected $accessToken;

    /**
     * 按钮集合
     */
    protected $buttons = array();

    /**
     * 构造方法
     */
    public function __construct(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * 添加按钮
     */
    public function add(ButtonInterface $button)
    {
        if( $button instanceof ButtonCollectionInterface ) {
            if( !$button->getChild() ) {
                throw new \InvalidArgumentException('一级菜单不能为空');
            }
        }

        if( count($this->buttons) > (static::MAX_COUNT - 1) ) {
            throw new \InvalidArgumentException(sprintf(
                '一级菜单不能超过 %d 个', static::MAX_COUNT
            ));
        }

        $this->buttons[] = $button;
    }

    /**
     * 发布菜单
     */
    public function doCreate()
    {
        $response = Http::request('POST', static::CREATE_URL)
            ->withAccessToken($this->accessToken)
            ->withBody($this->getRequestBody())
            ->send();

        if( $response['errcode'] != 0 ) {
            throw new \Exception($response['errmsg'], $response['errcode']);
        }

        return true;
    }

    /**
     * 获取数据
     */
    public function getRequestBody()
    {
        $data = array();

        foreach( $this->buttons AS $k=>$v ) {
            $data['button'][$k] = $v->getData();
        }

        return $data;
    }
}
