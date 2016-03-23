<?php

namespace Thenbsp\Wechat\User;

use Thenbsp\Wechat\Bridge\Http;
use Thenbsp\Wechat\Wechat\AccessToken;
use Doctrine\Common\Collections\ArrayCollection;

class Group
{
    const SELECT            = 'https://api.weixin.qq.com/cgi-bin/groups/get';
    const CREATE            = 'https://api.weixin.qq.com/cgi-bin/groups/create';
    const UPDAET            = 'https://api.weixin.qq.com/cgi-bin/groups/update';
    const DELETE            = 'https://api.weixin.qq.com/cgi-bin/groups/delete';
    const QUERY_USER_GROUP  = 'https://api.weixin.qq.com/cgi-bin/groups/getid';
    const UPDATE_USER_GROUP = 'https://api.weixin.qq.com/cgi-bin/groups/members/update';
    const BETCH_UPDATE_USER_GROUP = 'https://api.weixin.qq.com/cgi-bin/groups/members/batchupdate';

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
     * 查询全部分组
     */
    public function query()
    {
        $response = Http::request('GET', static::SELECT)
            ->withAccessToken($this->accessToken)
            ->send();

        if( $response['errcode'] != 0 ) {
            throw new \Exception($response['errmsg'], $response['errcode']);
        }

        return new ArrayCollection($response['groups']);
    }

    /**
     * 创建新分组
     */
    public function create($name)
    {
        $body = array(
            'group' => array('name'=>$name)
        );

        $response = Http::request('POST', static::CREATE)
            ->withAccessToken($this->accessToken)
            ->withBody($body)
            ->send();

        if( $response['errcode'] != 0 ) {
            throw new \Exception($response['errmsg'], $response['errcode']);
        }

        return new ArrayCollection($response['group']);
    }

    /**
     * 修改分组名称
     */
    public function update($id, $newName)
    {
        $body = array(
            'group' => array(
                'id'    => $id,
                'name'  => $newName
            )
        );

        $response = Http::request('POST', static::UPDAET)
            ->withAccessToken($this->accessToken)
            ->withBody($body)
            ->send();

        if( $response['errcode'] != 0 ) {
            throw new \Exception($response['errmsg'], $response['errcode']);
        }

        return true;
    }

    /**
     * 删除分组
     */
    public function delete($id)
    {
        $body = array(
            'group' => array('id'=>$id)
        );

        $response = Http::request('POST', static::DELETE)
            ->withAccessToken($this->accessToken)
            ->withBody($body)
            ->send();

        if( $response['errcode'] != 0 ) {
            throw new \Exception($response['errmsg'], $response['errcode']);
        }

        return true;
    }

    /**
     * 查询指定用户所在分组
     */
    public function queryUserGroup($openid)
    {
        $body = array('openid'=>$openid);

        $response = Http::request('POST', static::QUERY_USER_GROUP)
            ->withAccessToken($this->accessToken)
            ->withBody($body)
            ->send();

        if( $response['errcode'] != 0 ) {
            throw new \Exception($response['errmsg'], $response['errcode']);
        }

        return $response['groupid'];
    }

    /**
     * 移动用户分组
     */
    public function updateUserGroup($openid, $newId)
    {
        $key = is_array($openid)
            ? 'openid_list'
            : 'openid';

        $api = is_array($openid)
            ? static::BETCH_UPDATE_USER_GROUP
            : static::UPDATE_USER_GROUP;

        $body = array($key=>$openid, 'to_groupid'=>$newId);

        $response = Http::request('POST', $api)
            ->withAccessToken($this->accessToken)
            ->withBody($body)
            ->send();

        if( $response['errcode'] != 0 ) {
            throw new \Exception($response['errmsg'], $response['errcode']);
        }

        return true;
    }
}
