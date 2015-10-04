# Cache 对象

Cache 对象提供数据缓存服务，比如缓存 AccessToken、Ticket 等数据。

```php
use Thenbsp\Wechat\Util\Cache;

// ./Storage 需可写权限
$cache = new Cache('./Storage');
```