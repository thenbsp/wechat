<?php

/**
 * 错误消息等级
 */
error_reporting(E_ALL);

/**
 * 是否显示错误
 */
ini_set('display_errors', 1);

/**
 * 自动加载
 */
require '../vendor/autoload.php';

/**
 * ====================================================
 * 以下为（_example）示例目录下所需代码
 * ====================================================
 */
use Thenbsp\Wechat\Wechat;
use Thenbsp\Wechat\AccessToken;
use Thenbsp\Wechat\Util\Cache;

/**
 * 演示时输出强制字符集
 */
header('Content-Type: text/html; charset=utf-8');

/**
 * 演示 URL（请换上相应 URL 地址）
 */
define('EXAMPLE_URL', '----------------YOUR_BASE_URL------------------');
/**
 * 配置公众号信息
 */
$options = array(
     // 公众号 appid/appsecret
    'appid'     => 'your appid',
    'appsecret' => 'your appsecret',
    // 商户 ID/key
    'mchid'     => 'your mchid',
    'mchkey'    => 'your mch key',
    // 商户授权证书（退款时必需）
    // 'authenticate_cert' => array(
    //     'cert'  => '/path/to/apiclient_cert.pem',
    //     'key'   => '/path/to/apiclient_key.pem'
    // )
);


/**
 * 定义 Cache 对象
 */
$cache = new Cache('./Storage');

/**
 * 定义 Wechat 对象（appid 和 appsecret 必填）
 */
$wechat = new Wechat($options);

/**
 * AccessToken 对象是用来调用全局接口所需的凭证
 */
$accessToken = new AccessToken($wechat, $cache);
