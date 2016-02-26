<?php

require './example.php';

use Thenbsp\Wechat\Event\Entity;
use Thenbsp\Wechat\Event\Entity\Text;
use Thenbsp\Wechat\Event\Entity\Image;
use Thenbsp\Wechat\Event\Entity\Voice;
use Thenbsp\Wechat\Event\Entity\Video;
use Thenbsp\Wechat\Event\Entity\ShortVideo;
use Thenbsp\Wechat\Event\Entity\Location;
use Thenbsp\Wechat\Event\Entity\Link;
use Thenbsp\Wechat\Event\Entity\EventSubscribe;
use Thenbsp\Wechat\Event\Entity\EventUnsubscribe;
use Thenbsp\Wechat\Event\Entity\EventScanSubscribe;
use Thenbsp\Wechat\Event\Entity\EventScanSubscribed;
use Thenbsp\Wechat\Event\Entity\EventLocation;
use Thenbsp\Wechat\Event\Entity\EventMenuClick;
use Thenbsp\Wechat\Event\Entity\EventMenuView;
use Thenbsp\Wechat\Event\Entity\EventCardPassCheck;
use Thenbsp\Wechat\Event\Entity\EventCardUserGet;

use Thenbsp\Wechat\Event\EventHandler;
use Thenbsp\Wechat\Event\EventListener;

$callable = function(Entity $event) {
    var_dump($event->toArray());
};

$listener = new EventListener();
$listener
    ->addListener(Text::class,                  $callable)
    ->addListener(Image::class,                 $callable)
    ->addListener(Voice::class,                 $callable)
    ->addListener(Video::class,                 $callable)
    ->addListener(ShortVideo::class,            $callable)
    ->addListener(Location::class,              $callable)
    ->addListener(Link::class,                  $callable)
    ->addListener(EventSubscribe::class,        $callable)
    ->addListener(EventUnsubscribe::class,      $callable)
    ->addListener(EventScanSubscribe::class,    $callable)
    ->addListener(EventScanSubscribed::class,   $callable)
    ->addListener(EventLocation::class,         $callable)
    ->addListener(EventMenuClick::class,        $callable)
    ->addListener(EventMenuView::class,         $callable);

$handler = new EventHandler();
$handler->handle($listener);
