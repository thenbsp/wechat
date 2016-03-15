### 简介

微信公众平台第三方 SDK 开发包，优雅、健壮，可扩展，遵循 [PSR](http://www.php-fig.org/) 开发规范。

## 安装

```
composer require thenbsp/wechat
```

# 功能

- 基础支持
    - [缓存] (https://github.com/thenbsp/wechat/wiki/wechat_cache)
    - [日志](https://github.com/thenbsp/wechat/wiki/wechat_logger)
    - [全局 AccessToken](https://github.com/thenbsp/wechat/wiki/wechat_access_token)
    - [生成 Jsapi 配置](https://github.com/thenbsp/wechat/wiki/wechat_jsapi)
    - [生成带参数的二维码](https://github.com/thenbsp/wechat/wiki/wechat_qrcode)
    - [获取微信服务器 IP](https://github.com/thenbsp/wechat/wiki/wechat_server_ip)
    - [生成短链接](https://github.com/thenbsp/wechat/wiki/wechat_short_url)
- 网页授权
    - [网页授权获取用户信息](https://github.com/thenbsp/wechat/wiki/oauth_client)
    - [用户 AccessToken](https://github.com/thenbsp/wechat/wiki/oauth_access_token)
- 自定义菜单
    - [创建菜单](https://github.com/thenbsp/wechat/wiki/menu_create)
    - [查询菜单](https://github.com/thenbsp/wechat/wiki/menu_query)
    - [删除菜单](https://github.com/thenbsp/wechat/wiki/menu_delete)
- 微信支付
    - [统一下单](https://github.com/thenbsp/wechat/wiki/payment_unifiedorder)
    - [公众号支付](https://github.com/thenbsp/wechat/wiki/payment_jsapi)
    - [扫码支付（临时模式）](https://github.com/thenbsp/wechat/wiki/payment_temporary)
    - [扫码支付（永久模式）](https://github.com/thenbsp/wechat/wiki/payment_forever)
    - [支付通知](https://github.com/thenbsp/wechat/wiki/payment_notify)
    - [现金红包](https://github.com/thenbsp/wechat/wiki/payment_coupon_cash)
    - [共享收货地址组件](https://github.com/thenbsp/wechat/wiki/payment_address)
- 消息管理
    - [消息实体](https://github.com/thenbsp/wechat/wiki/message_entity)
    - [模板消息](https://github.com/thenbsp/wechat/wiki/message_template)
- 事件系统
    - [事件监听](https://github.com/thenbsp/wechat/wiki/event_system)
    - [被动回复消息](https://github.com/thenbsp/wechat/wiki/event_response)