<?php

namespace Thenbsp\Wechat\Message\Entity;

use Thenbsp\Wechat\Message\Entity;
use Thenbsp\Wechat\Util\OptionValidator;

class Voice extends Entity
{
    /**
     * 构造方法
     */
    public function __construct(array $options = array())
    {
        $required = array('ToUserName', 'FromUserName', 'CreateTime', 'Voice');

        $validator = new OptionValidator();
        $validator
            ->setDefined($required)
            ->setRequired($required)
            ->setAllowedTypes('Voice', array('array'))
            ->setAllowedValues('Voice', function($value) {
                if( !array_key_exists('MediaId', $value) ) {
                    throw new \InvalidArgumentException('Undefined index: MediaId');
                }
                return true;
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
