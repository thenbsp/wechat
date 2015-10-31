# 模板消息

定义模板参数：

```php
use Thenbsp\Wechat\Message\Template\TemplateOption;

$templateOption = new TemplateOption();
$templateOption->add('参数名称', '参数值', '参数颜色');
```

发送模板消息（需要注入全局 AccessToken）：

```php
use Thenbsp\Wechat\Message\Template\Template;
use Thenbsp\Wechat\Message\Template\TemplateOption;

$templateOption = new TemplateOption();
$templateOption->add('name', '张三', '#ff0000');
$templateOption->add('remark', '李四', '#0000ff');

// 注入 AccessToken
$template = new Template($accessToken);
// 注入参数
$template->setOptions($templateOption);
// 用户 Openid
$template->setTouser('接收者用户 OpenID');
// 模板 ID
$template->setTemplateId('模板 ID');
// 消息跳转链接
$template->setUrl('消息链接');
// 发送
$template->send();
```

如果发送失败将抛出 \Exception 异常，成功将返回 msgid

```php
try {
   $msgid = $template->send(); 
} catch (\Exception $e) {
   exit($e->getMessage()); 
}
var_dump($msgid);
```

TemplateOption::toArray 方法可查看参数 Array：

```php
var_dump($templateOption->toArray());
```

TemplateOption::toJSON 方法可查看参数 JSON：

```php
var_dump($templateOption->toJSON());
```

示例：

[/example/message-template.php](/example/message-template.php)