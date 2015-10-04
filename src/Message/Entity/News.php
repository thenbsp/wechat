<?php

namespace Thenbsp\Wechat\Message\Entity;

use Thenbsp\Wechat\Message\Entity;
use Thenbsp\Wechat\Util\OptionValidator;
use Symfony\Component\OptionsResolver\Options;

class News extends Entity
{
    /**
     * 构造方法
     */
    public function __construct(array $options = array())
    {
        $required = array('ToUserName', 'FromUserName', 'CreateTime', 'ArticleCount', 'Articles');

        $validator = new OptionValidator();
        $validator
            ->setDefined($required)
            ->setRequired($required)
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

        $validtated = $validator->validate($options);

        parent::__construct($validtated);
    }

    /**
     * 获取消息类型
     */
    public function getType()
    {
        $namespace = explode('\\', __CLASS__);
        
        return strtolower(end($namespace));
    }
}
