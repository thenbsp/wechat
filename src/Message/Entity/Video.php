<?php

namespace Thenbsp\Wechat\Message\Entity;

use Thenbsp\Wechat\Message\Entity;

class Video extends Entity
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
        $options = array('ToUserName', 'FromUserName', 'CreateTime', 'MsgType', 'Video');

        $resolver
            ->setDefined($options)
            ->setRequired($options)
            ->setAllowedTypes('Video', array('array'))
            ->setAllowedValues('Video', function($value) {
                if( !array_key_exists('MediaId', $value) ) {
                    throw new \InvalidArgumentException('Undefined index: MediaId');
                }
                return true;
            });
    }
}
