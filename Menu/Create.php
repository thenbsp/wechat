<?php

namespace Thenbsp\Wechat\Menu;

use Thenbsp\Wechat\AccessToken;
use Thenbsp\Wechat\Util\Http;
use Thenbsp\Wechat\Util\Serialize;

class Create
{
    /**
     * 接口地址
     */
    const CREATE_URL = 'https://api.weixin.qq.com/cgi-bin/menu/create';

    /**
     * AccessToken 对象
     */
    protected $accessToken;

    /**
     * 按钮集合
     */
    protected $buttons = array();

    /**
     * 一级菜单不能超过 3 个
     */
    protected $count = 3;

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

        if( count($this->buttons) > ($this->count - 1) ) {
            throw new \InvalidArgumentException(sprintf(
                '一级菜单不能超过 %d 个', $this->count
            ));
        }

        $this->buttons[] = $button;
    }

    /**
     * 获取数据
     */
    public function getData($asJSON = false)
    {
        $data = array();

        foreach( $this->buttons AS $k=>$v ) {
            $data['button'][$k] = $v->getData();
        }

        return $asJSON ? Serialize::encode($data, 'json') : $data;
    }

    /**
     * 发布菜单
     */
    public function doCreate()
    {
        $data = Serialize::encode($this->getData(), 'json');

        $request = Http::post(self::CREATE_URL, array(
            'body' => $data,
            'query' => array(
                'access_token' => $this->accessToken->getAccessToken()
            )
        ));

        $response = $request->json();

        if( array_key_exists('errcode', $response) &&
            ($response['errcode'] != 0) ) {
            throw new \Exception($response['errcode'].': '.$response['errmsg']);
        }

        return true;
    }
}
