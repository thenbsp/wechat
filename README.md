# 简介

微信公众平台第三方 SDK 开发包，优雅、健壮，可扩展，遵循 [PSR](http://www.php-fig.org/) 开发规范。

# 安装

```
composer require thenbsp/wechat
```

# 功能

- 基础支持
    - [数据缓存] (/wiki/wechat_cache)
    - [日志](wechat_logger)
    - [全局 AccessToken](wechat_access_token)
    - [生成 Jsapi 配置](wechat_jsapi)
    - [生成带参数的二维码](wechat_qrcode)
    - [获取微信服务器 IP](wechat_server_ip)
    - [生成短链接](wechat_short_url)
- 网页授权
    - [网页授权获取用户信息](oauth_client)
    - [用户 AccessToken](oauth_access_token)
- 自定义菜单
    - [创建菜单](menu_create)
    - [查询菜单](menu_query)
    - [删除菜单](menu_delete)
- 微信支付
    - [统一下单](payment_unifiedorder)
    - [公众号支付](payment_jsapi)
    - [扫码支付（临时模式）](payment_temporary)
    - [扫码支付（永久模式）](payment_forever)
    - [支付通知](payment_notify)
    - [现金红包](payment_coupon_cash)
    - [共享收货地址组件](payment_address)
- 消息管理
    - [消息实体](message_entity)
    - [模板消息](message_template)
- 事件系统
    - [事件监听器](event_listener)
    - [事件处理](event_handler)
    - [被动回复消息](event_response)