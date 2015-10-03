<?php

namespace Thenbsp\Wechat\Message\Entity;

use Thenbsp\Wechat\Message\Entity;

class Music extends Entity
{
    /**
     * 获取消息类型
     */
    public function getType()
    {
        $namespace = explode('\\', __CLASS__);
        
        return strtolower(end($namespace));
    }

    /**
     * 配置参数
     */
    protected function configureOptions($resolver)
    {
        $options = array('ToUserName', 'FromUserName', 'CreateTime', 'MsgType', 'Music');

        $resolver
            ->setDefined($options)
            ->setRequired($options)
            ->setAllowedTypes('Music', array('array'));
    }
}
