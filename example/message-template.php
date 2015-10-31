<?php

require './example.php';

use Thenbsp\Wechat\Message\Template\Template;
use Thenbsp\Wechat\Message\Template\TemplateOption;

$templateOption = new TemplateOption();
$templateOption->add('name', '张三', '#ff0000');
$templateOption->add('remark', '李四', 'green');

$template = new Template($accessToken);
$template->setTouser('oWY-5jjLjo7pYUK86JPpwvcnF2Js');
$template->setTemplateId('K2M_za8UDzSMfvKMupZmgOBTpO1yJHXYdYDDY3aQl_8');
$template->setUrl('http://cn.bing.com/');
$template->setOptions($templateOption);

try {
   $msgid = $template->send(); 
} catch (\Exception $e) {
   exit($e->getMessage()); 
}

var_dump("发送成功：#{$msgid}");
