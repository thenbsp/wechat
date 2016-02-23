<?php

require './example.php';

use Thenbsp\Wechat\Payment\Notify;
use Symfony\Component\HttpFoundation\Request;

$request    = Request::createFromGlobals();
$notify     = new Notify($request);

/**
 * 返回错误标识
 */
// $notify->fail('error message');

/**
 * 查看订单信息
 */
$logger->debug($notify['out_trade_no'], $notify->toArray());

/**
 * 返回成功标识
 */
$notify->success('OK');

