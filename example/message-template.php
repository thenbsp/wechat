<?php

require './example.php';

use Thenbsp\Wechat\Message\Template\Sender;
use Thenbsp\Wechat\Message\Template\Template;

/**
 * 创建消息模板
 */
$template = new Template('zJ_a1LjbkZrHc9YaWeV7tmuUtI7LvlNvRPFdcmsprr4');

$template->setOpenid('oWY-5jjLjo7pYUK86JPpwvcnF2Js');
$template->setUrl('http://github.com/');

$template
    ->add('result',         '恭喜您，中奖啦！')
    ->add('totalWinMoney',  '中奖 5 元')
    ->add('issueInfo',      '双色球2013023期')
    ->add('fee',            '39.8 元')
    ->add('betTime',        '2013-10-10 21:30')
    ->add('remark',         '奖金将于 01:00 前到账，请稍候领取', '#aaaaaa');

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
