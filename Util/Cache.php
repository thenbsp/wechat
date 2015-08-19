<?php

namespace Thenbsp\Wechat\Util;

/**
 * Cache File Adapter
 * Created by thenbsp (thenbsp@gmail.com)
 * Created at 2015/07/23
 */
class Cache
{
    /**
     * 寻缓存文件存储路径
     */
    protected $cacheDirName;

    /**
     * 构造方法
     */
    public function __construct($cacheDirName)
    {
        if( !is_dir($cacheDirName) ) {
            if( !@mkdir($cacheDirName, 0777) ) {
                throw new \Exception(sprintf('Invalid Cache Dir "%s"',
                    str_replace($_SERVER['DOCUMENT_ROOT'], '', $cacheDirName)
                ));
            }
        }

        if( !is_writable($cacheDirName) ) {
            throw new \Exception(sprintf('Cache Dir "%s" Unwritable',
                str_replace($_SERVER['DOCUMENT_ROOT'], '', $cacheDirName)
            ));
        }

        $this->cacheDirName = rtrim($cacheDirName, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
    }

    /**
     * 设置缓存内容
     */
    public function set($key, $value)
    {
        $fileName = $this->getCacheName($key);

        return @file_put_contents($fileName, serialize($value));
    }

    /**
     * 获取缓存内容
     */
    public function get($key, $default = null)
    {
        $fileName = $this->getCacheName($key);

        if( @is_file($fileName) &&
            ($data = @file_get_contents($fileName)) ) {
            return unserialize($data);
        }

        return $default;
    }

    /**
     * 生成缓存文件名称
     */
    public function getCacheName($key, $hashKey = false)
    {
        if( $hashKey ) {
            $key = md5($key);
        }

        $fileName = $this->cacheDirName.$key;

        return str_replace(array(' ', '-'), '_', $fileName);
    }

}