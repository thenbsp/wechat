<?php

require './example.php';

use Thenbsp\Wechat\Payment\Query;

$query = new Query(APPID, MCHID, MCHKEY);
// $query->set('transaction_id', '1009660380201506130728806387');

try {
    // 根据 transaction_id 查询
    $result = $query->fromTransactionId('1005130236201511211701198549');
    // 根据 out_trade_no 查询
    // $result = $query->fromOutTradeNo('1511210818846691');
} catch (\Exception $e) {
    exit($e->getMessage());
}

var_dump($result->toArray());
