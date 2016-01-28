<?php

use Thenbsp\Wechat\Wechat\AccessToken;
use Doctrine\Common\Cache\FilesystemCache;

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
$cacheDriver = new FilesystemCache('./cache');

/**
 * AccessToken 管理对象
 */
$accessToken = new AccessToken(APPID, APPSECRET);
$accessToken->setCacheDriver($cacheDriver);
