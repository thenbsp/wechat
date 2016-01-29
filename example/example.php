<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Doctrine\Common\Cache\FilesystemCache;
use Thenbsp\Wechat\Wechat\AccessToken;

/**
 * Composer 自动加载
 */
require '../vendor/autoload.php';

/**
 * 错误消息等级
 */
error_reporting(E_ALL);

/**
 * 是否显示错误
 */
ini_set('display_errors', 1);

/**
 * 配置公众号
 */
define('APPID',     'your appid');
define('APPSECRET', 'your appsecret');

/**
 * 数据缓存驱动
 */
$cache = new FilesystemCache('./cache');

/**
 * 日志驱动
 */
$logger = new Logger('wechat');
$logger->pushHandler(new StreamHandler('./logs/wechat.log', Logger::DEBUG));

/**
 * AccessToken 管理对象
 */
$accessToken = new AccessToken(APPID, APPSECRET);
// 推荐使用缓存（可选）
$accessToken->setCacheBridge($cache);
