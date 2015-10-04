# 自定义菜单 - 删除菜单

删除自定义菜单需要注入 AccessToken。

```php
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
```