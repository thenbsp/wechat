<?php

require './example.php';

use Thenbsp\Wechat\Menu\Query;

/**
 * 查询接口
 */
$query = new Query($accessToken);

/**
 * 获取查询结果
 */
try {
    $response = $query->doQuery();
} catch (\Exception $e) {
    exit($e->getMessage());
}

echo '<pre>';
print_r($response->toArray());
echo '</pre>';
