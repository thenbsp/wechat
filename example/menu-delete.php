<?php

require './example.php';

use Thenbsp\Wechat\Menu\Delete;

/**
 * 删除接口
 */
$delete = new Delete($accessToken);

/**
 * 执行删除
 */
try {
    $delete->doDelete();
} catch (Exception $e) {
    exit($e->getMessage());
}

var_dump('菜单删除建成功');
