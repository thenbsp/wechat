<?php

namespace Thenbsp\Wechat;

use Thenbsp\Wechat\Util\Option;
use Thenbsp\Wechat\Util\OptionValidator;

class Wechat extends Option
{
    /**
     * 构造方法
     */
    public function __construct(array $options)
    {
        $certNormalizer = function($options, $value) {
            // authenticate_cert 为一个 包含 cert 和 key 的数组
            if( !(array_key_exists('cert', $value) &&
                array_key_exists('key', $value)) ) {
                throw new \InvalidArgumentException('Authenticate_cert cert/key is required');
            }
            // authenticate_cert 中的 cert 和 key 必需为已存在的文件
            if( !(file_exists($value['cert']) &&
                file_exists($value['key'])) ) {
                throw new \InvalidArgumentException('Authenticate_cert cert/key is invalid file');
            }
            return $value;
        };

        $validator = new OptionValidator();
        $validator
            ->setDefined(array('appid', 'appsecret', 'mchid', 'mchkey', 'authenticate_cert'))
            ->setRequired(array('appid', 'appsecret'))
            ->setAllowedTypes('authenticate_cert', 'array')
            ->setNormalizer('authenticate_cert', $certNormalizer);;

        $options = $validator->validate($options);

        parent::__construct($options);
    }
}
