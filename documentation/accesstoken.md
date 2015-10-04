# AccessToken 对象

AccessToken 是公众号的全局唯一票据，有效期为 7200 秒，因此需要全局缓存维护，AccessToken 对象需要注入 ``Wechat`` 和 ``Cache`` 对象。

```php
use Thenbsp\Wechat\AccessToken;

$accessToken = new AccessToken($wechat, $cache);

var_dump($accessToken->getAccessToken());
```
