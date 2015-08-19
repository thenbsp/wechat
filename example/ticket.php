<?php

require './config.php';

use Thenbsp\Wechat\Wechat;
use Thenbsp\Wechat\Exception\TicketException;

/**
 * 获取众公号 Ticket（ticket 分为 jsapi 和 wx_card，getTicket 方法可传入一个可选参数）
 */
$o = new Wechat(APPID, APPSECRET);

try {
    $ticket = $o->getTicket();
} catch (TicketException $e) {
    exit($e->getMessage());
}

var_dump($ticket);