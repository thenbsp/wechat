<?php

require './example.php';

use Thenbsp\Wechat\Message\Text;

$text = new Text();
$text->set('content', 'test content...');

var_dump($text->toArray());
