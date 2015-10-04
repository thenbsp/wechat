# Ticket 对象

api_ticket 是用于调用微信 JSAPI 的临时票据，有效期为7200 秒，分为 JSAPI Ticket 和 WX_card Ticket，因此需要全局缓存维护，Ticket 对象需要注入 ``AccessToken`` 对象。

```php
use Thenbsp\Wechat\Ticket;

$ticket = new Ticket($accessToken);
```


获取 JSAPI Ticket （默认）

```php
var_dump($ticket->getTicket());
```

获取卡券 Ticket

```php
var_dump($ticket->getTicket('wx_card'));
```