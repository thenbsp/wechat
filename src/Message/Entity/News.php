<?php

namespace Thenbsp\Wechat\Message\Entity;

use Thenbsp\Wechat\Message\Entity;
use Symfony\Component\OptionsResolver\Options;

class News extends Entity
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
        $options = array('ToUserName', 'FromUserName', 'CreateTime', 'MsgType', 'ArticleCount', 'Articles');

        $resolver
            ->setDefined($options)
            ->setRequired($options)
            ->setAllowedTypes('Articles', array('array'))
            ->setAllowedValues('Articles', function($value) {
                if( !array_key_exists('item', $value) ) {
                    throw new \InvalidArgumentException('Undefined index: item');
                }
                if( !is_array($value['item']) ) {
                    throw new \InvalidArgumentException('Articles item must be array');
                }
                if( count($value['item']) > 10 ) {
                    throw new \InvalidArgumentException('Articles item must be Less than 10');
                }
                return true;
            })
            ->setDefault('ArticleCount', function (Options $options) {
                return count($options['Articles']['item']);
            });
    }
}
