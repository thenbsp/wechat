<?php

require './example.php';

use Thenbsp\Wechat\Payment\Query;

$query = new Query(APPID, MCHID, MCHKEY);
$query->set('transaction_id', '1009660380201506130728806387');

try {
    $result = $query->doQuery();
} catch (\Exception $e) {
    exit($e->getMessage());
}

var_dump($result);
