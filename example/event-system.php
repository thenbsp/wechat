<?php

require './example.php';

use Thenbsp\Wechat\Event\Entity\Text;
use Thenbsp\Wechat\Event\Entity\Image;
use Thenbsp\Wechat\Event\Entity\Voice;
use Thenbsp\Wechat\Event\Entity\Video;
use Thenbsp\Wechat\Event\Entity\Shortvideo;
use Thenbsp\Wechat\Event\Entity\Location;
use Thenbsp\Wechat\Event\Entity\Link;

use Thenbsp\Wechat\Event\EventHandler;
use Thenbsp\Wechat\Event\EventListener;

$callable = function($event) {
    return $event;
    var_dump(get_class($event));
};

$listener = new EventListener();
$listener
    ->addListener(Text::class,          $callable)
    ->addListener(Image::class,         $callable)
    ->addListener(Voice::class,         $callable)
    ->addListener(Video::class,         $callable)
    ->addListener(Shortvideo::class,    $callable)
    ->addListener(Location::class,      $callable)
    ->addListener(Link::class,          $callable);

$handler = new EventHandler();
$handler->handle($listener);
