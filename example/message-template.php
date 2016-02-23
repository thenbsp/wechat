<?php

require './example.php';

use Thenbsp\Wechat\Bridge\Util;
use Thenbsp\Wechat\OAuth\Client;
use Thenbsp\Wechat\Message\Template\Sender;
use Thenbsp\Wechat\Message\Template\Template;

/**
 * 只能在微信中打开
 */
if ( Util::isWechat() ) {
    exit('请在微信中打开');
}

/**
 * 获取用户 openid
 */
if( !isset($_SESSION['openid']) ) {

    $client = new Client(APPID, APPSECRET);

    if( !isset($_GET['code']) ) {
        header('Location: '.$client->getAuthorizeUrl());
    }

    try {
        $token = $client->getAccessToken($_GET['code']);
    } catch (\Exception $e) {
        exit($e->getMessage());
    }

    $_SESSION['openid'] = $token['openid'];
}

/**
 * 创建消息模板
 */
$template = new Template('zJ_a1LjbkZrHc9YaWeV7tmuUtI7LvlNvRPFdcmsprr4');
$template
    ->add('result',         '恭喜您，中奖啦！', '#ff0000')
    ->add('totalWinMoney',  '中奖 5 元')
    ->add('issueInfo',      '双色球2013023期')
    ->add('fee',            '39.8 元')
    ->add('betTime',        '2013-10-10 21:30')
    ->add('remark',         '奖金将于 01:00 前到账，请稍候领取');

$template->setUrl('http://github.com/');                // 跳转链接
$template->setOpenid($_SESSION['openid']);   // 发给谁

/**
 * 发送消息模板
 */
$sender = new Sender($accessToken);

try {
    $msgid = $sender->send($template);
} catch (\Exception $e) {
    exit($e->getMessage());
}

var_dump(sprintf('发送成功：#%s', $msgid));
