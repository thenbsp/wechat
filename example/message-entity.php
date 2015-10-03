<?php

require './example.php';

use Thenbsp\Wechat\Message\Entity\News;
use Thenbsp\Wechat\Message\Entity\Text;
use Thenbsp\Wechat\Message\Entity\Image;
use Thenbsp\Wechat\Message\Entity\Voice;
use Thenbsp\Wechat\Message\Entity\Video;
use Thenbsp\Wechat\Message\Entity\Music;

// 文本消息
$text = new Text(array(
    'ToUserName'    => 'toUser',
    'FromUserName'  => 'fromUser',
    'CreateTime'    => 'CreateTime',
    'Content'       => '你好'
));

print_r($text->getOptions());

// 图像消息
$image = new Image(array(
    'ToUserName'    => 'toUser',
    'FromUserName'  => 'fromUser',
    'CreateTime'    => 'CreateTime',
    'Image'         => array(
        'MediaId'   => 'Image MediaId'
    )
));

print_r($image->getOptions());

// 语音消息
$voice = new Voice(array(
    'ToUserName'    => 'toUser',
    'FromUserName'  => 'fromUser',
    'CreateTime'    => 'CreateTime',
    'Voice'         => array(
        'MediaId'   => 'Voice MediaId'
    )
));

print_r($voice->getOptions());

// 视频消息
$video = new Video(array(
    'ToUserName'    => 'toUser',
    'FromUserName'  => 'fromUser',
    'CreateTime'    => 'CreateTime',
    'Video'         => array(
        'MediaId'       => 'Video MediaId',
        'Title'         => 'Video Title',
        'Description'   => 'Video Description'
    )
));

print_r($video->getOptions());

// 音乐消息
$music = new Music(array(
    'ToUserName'    => 'toUser',
    'FromUserName'  => 'fromUser',
    'CreateTime'    => 'CreateTime',
    'Music'         => array(
        'Title'         => 'Music Title',
        'Description'   => 'Music Description',
        'MusicUrl'      => 'Music MusicUrl',
        'HQMusicUrl'    => 'Music HQMusicUrl',
        'ThumbMediaId'  => 'Music ThumbMediaId'
    )
));

print_r($music->getOptions());

// 图文消息
$news = new News(array(
    'ToUserName'    => 'toUser',
    'FromUserName'  => 'fromUser',
    'CreateTime'    => 'CreateTime',
    // 'ArticleCount'  => 2,
    'Articles'      => array(
        'item' => array(
            array(
                'Title'         => 'News 1 Title',
                'Description'   => 'News 1 Description',
                'PicUrl'        => 'News 1 Title',
                'Url'           => 'News 1 Title',
            ),
            array(
                'Title'         => 'News 2 Title',
                'Description'   => 'News 2 Description',
                'PicUrl'        => 'News 2 Title',
                'Url'           => 'News 2 Title'
            ),
            array(
                'Title'         => 'News 3 Title',
                'Description'   => 'News 3 Description',
                'PicUrl'        => 'News 3 Title',
                'Url'           => 'News 3 Title'
            )
        )
    )
));

print_r($news->getOptions());


/**
 * 最后输出 XMl 可以使用 getData 方法
 */
// var_dump($news->getData());