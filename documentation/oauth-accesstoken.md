# 网页授权 - AccessToken 对象

该 AccessToken 为用户接口调用凭证（不要和全局 AccessToken 混淆），包括以下字段：

- access_token
- expires_in
- refresh_token
- openid
- scope
- unionid

AccessToken 对象需要从网页授权接口获取：

```php
$accessToken = $client->getAccessToken($_GET['code']);
```

详细请查看 [oauth.md](documentation/oauth.md)

### 判断 AccessToken 是否有效
 
```php

var_dump($accessToken->isValid());

```

### 刷新 AccessToken

```php
$accessToken->refresh();
````