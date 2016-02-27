<?php

require './example.php';

use Thenbsp\Wechat\Event\Event\Text;
use Thenbsp\Wechat\Event\Event\Image;
use Thenbsp\Wechat\Event\Event\Voice;
use Thenbsp\Wechat\Event\Event\Video;
use Thenbsp\Wechat\Event\Event\ShortVideo;
use Thenbsp\Wechat\Event\Event\Location;
use Thenbsp\Wechat\Event\Event\Link;
use Thenbsp\Wechat\Event\Event\Subscribe;
use Thenbsp\Wechat\Event\Event\Unsubscribe;
use Thenbsp\Wechat\Event\Event\ScanSubscribe;
use Thenbsp\Wechat\Event\Event\ScanSubscribed;
use Thenbsp\Wechat\Event\Event\UserLocation;
use Thenbsp\Wechat\Event\Event\MenuClick;
use Thenbsp\Wechat\Event\Event\MenuView;

use Thenbsp\Wechat\Event\EventHandler;
use Thenbsp\Wechat\Event\EventListener;

/**
 * 事件调用方法
 */
$callable = function($event) {

    $entity = new Thenbsp\Wechat\Message\Entity\Text();
    $entity->setContent('你好！（接口测试回复消息）');

    $event->setResponse($entity);
};

/**
 * 注册事件监听
 */
$listener = new EventListener();
$listener
    ->addListener(Text::class,                  $callable)
    ->addListener(Image::class,                 $callable)
    ->addListener(Voice::class,                 $callable)
    ->addListener(Video::class,                 $callable)
    ->addListener(ShortVideo::class,            $callable)
    ->addListener(Location::class,              $callable)
    ->addListener(Link::class,                  $callable)
    ->addListener(Subscribe::class,             $callable)
    ->addListener(Unsubscribe::class,           $callable)
    ->addListener(ScanSubscribe::class,         $callable)
    ->addListener(ScanSubscribed::class,        $callable)
    ->addListener(UserLocation::class,          $callable)
    ->addListener(MenuClick::class,             $callable)
    ->addListener(MenuView::class,              $callable);

/**
 * 处理事件监听
 */
$handler = new EventHandler();
$handler->handle($listener);
