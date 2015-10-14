<?php

namespace Thenbsp\Wechat\Util;

/**
 * Cache File Adapter
 * Created by thenbsp (thenbsp@gmail.com) at 2015/07/23
 */
class Cache
{
    /**
     * 寻缓存文件存储路径
     */
    protected $cacheDirName;

    /**
     * 不允许使用的 Key 名
     */
    protected $disallowedCharacterPatterns = array(
        '/\-/', // replaced to disambiguate original `-` and `-` derived from replacements
        '/[^a-zA-Z0-9\-_\[\]]/' // also excludes non-ascii chars (not supported, depending on FS)
    );

    /**
     * 可替换的 Key 名
     */
    protected $replacementCharacters = array('__', '-');

    /**
     * 构造方法
     */
    public function __construct($cacheDirName)
    {
        if( !is_dir($cacheDirName) ) {
            if( !@mkdir($cacheDirName, 0777) ) {
                throw new \InvalidArgumentException(sprintf('Invalid Cache Dir "%s"',
                    str_replace($_SERVER['DOCUMENT_ROOT'], '', $cacheDirName)
                ));
            }
        }

        if( !is_writable($cacheDirName) ) {
            throw new \InvalidArgumentException(sprintf('Cache Dir "%s" Unwritable',
                str_replace($_SERVER['DOCUMENT_ROOT'], '', $cacheDirName)
            ));
        }

        $this->cacheDirName = rtrim($cacheDirName, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
    }

    /**
     * 设置缓存内容
     */
    public function set($key, $value, $ttl = 0)
    {
        $fileName = $this->getCacheName($key);
        $value = array(
            '_ttl_'     => ((int) $ttl ? ($ttl += time()) : 0),
            '_value_'   => $value
        );

        @file_put_contents($fileName, serialize($value));

        return true;
    }

    /**
     * 获取缓存内容
     */
    public function get($key, $defaultValue = null)
    {
        $fileName = $this->getCacheName($key);

        if( !file_exists($fileName) ) {
            return $defaultValue;
        }

        if( !($value = @file_get_contents($fileName)) ) {
            return $defaultValue;
        }

        $value = unserialize($value);

        if( $value['_ttl_'] === 0 ) {
            return $value['_value_'];
        }

        if( $value['_ttl_'] < time() ) {
            $this->delete($key);
            return $defaultValue;
        }

        return $value['_value_'];
    }

    /**
     * 检测缓存是否存在
     */
    public function has($key)
    {
        return (null !== $this->get($key));
    }

    /**
     * 删除缓存
     */
    public function delete($key)
    {
        $fileName = $this->getCacheName($key);

        if( file_exists($fileName) ) {
            return @unlink($fileName);
        }

        return true;
    }

    /**
     * 生成缓存文件名称
     */
    public function getCacheName($key)
    {
        $key = preg_replace($this->disallowedCharacterPatterns,
            $this->replacementCharacters, $key);

        return $this->cacheDirName.$key;
    }

    /**
     * 获取缓存目录
     */
    public function getCacheDir()
    {
        return $this->cacheDirName;
    }
}
