<?php

require './example.php';

use Thenbsp\Wechat\Message\Entity\Text;
use Thenbsp\Wechat\Message\Entity\Image;
use Thenbsp\Wechat\Message\Entity\Voice;
use Thenbsp\Wechat\Message\Entity\Video;
use Thenbsp\Wechat\Message\Entity\Music;
use Thenbsp\Wechat\Message\Entity\Article;
use Thenbsp\Wechat\Message\Entity\ArticleItem;

/**
 * 文字消息
 */
$text = new Text();
$text->setContent('你好');

print_r($text->getBody());

/**
 * 图片消息
 */
$image = new Image();
$image->setMediaId('media id');

print_r($image->getBody());

/**
 * 语音消息
 */
$voice = new Voice();
$voice->setMediaId('media id');

print_r($voice->getBody());

/**
 * 视频消息
 */
$video = new Video();
$video->setMediaId('media id');
$video->setTitle('video title');
$video->setDescription('video description');

print_r($video->getBody());

/**
 * 音乐消息
 */
$music = new Music();
$music->setTitle('music title');
$music->setDescription('music description');
$music->setMusicUrl('music url');
$music->setHQMusicUrl('HQ music url');
$music->setThumbMediaId('thumb media id');

print_r($music->getBody());

/**
 * 图文消息
 */
$item1 = new ArticleItem();
$item1->setTitle('article 1 title');
$item1->setDescription('article 1 description');
$item1->setPicUrl('article 1 pic url');
$item1->setUrl('article 1 url');

$item2 = new ArticleItem();
$item2->setTitle('article 2 title');
$item2->setDescription('article 2 description');
$item2->setPicUrl('article 2 pic url');
$item2->setUrl('article 2 url');

$item3 = new ArticleItem();
$item3->setTitle('article 3 title');
$item3->setDescription('article 3 description');
$item3->setPicUrl('article 3 pic url');
$item3->setUrl('article 3 url');

$article = new Article();
$article->addItem($item1);
$article->addItem($item2);
$article->addItem($item3);

print_r($article->getBody());
