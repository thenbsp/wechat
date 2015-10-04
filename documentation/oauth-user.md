# 网页授权 - User 对象

User 对象是指通过网页授权后得到的用户信息，通常包含以下字段：

- openid
- nickname
- sex
- language
- city
- province
- country
- headimgurl
- privilege
- unionid

User 对象需要从网页授权接口获取：

```php
$user = $client->getUser();
```

查看用户所有信息

```php
var_dump($user->getOptions());
```

查看指定字段

```php
var_dump($user['openid']);
var_dump($user['nickname']);
var_dump($user['headimgurl']);
// ...
```

