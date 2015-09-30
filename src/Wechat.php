<?php

namespace Thenbsp\Wechat;

use Thenbsp\Wechat\Util\OptionAccess;

class Wechat extends OptionAccess
{
    /**
     * 微信公众号所需参数
     */
    protected $options = array();

    /**
     * 定义所有选项
     */
    protected $defined = array('appid', 'appsecret', 'mchid', 'mchkey', 'authenticate_cert');

    /**
     * 定义必填参数
     */
    protected $required = array('appid', 'appsecret');

    /**
     * 构造方法
     */
    public function __construct(array $options)
    {
        parent::__construct($options);
    }

    /**
     * 配置参数
     */
    protected function configureOptions($resolver)
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
        };

        $resolver
            ->setDefined($this->defined)
            ->setRequired($this->required)
            ->setAllowedTypes('authenticate_cert', 'array')
            ->setNormalizer('authenticate_cert', $certNormalizer);
    }
}
