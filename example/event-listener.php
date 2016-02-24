<?php

require './example.php';

use Thenbsp\Wechat\Event\Text;
use Thenbsp\Wechat\Event\Image;
use Thenbsp\Wechat\Event\Voice;
use Thenbsp\Wechat\Event\Video;
use Thenbsp\Wechat\Event\Shortvideo;
use Thenbsp\Wechat\Event\Location;
use Thenbsp\Wechat\Event\Link;
use Thenbsp\Wechat\Event\EventManager;
use Symfony\Component\EventDispatcher\EventDispatcher;

$handler = function($event) {
    $options = $event->getOptions();
    $request = $event->getRequest();
    var_dump(get_class($event));
    // var_dump($options->toArray());
    // var_dump($request->getContent());
};

$dispatcher = new EventDispatcher();
$dispatcher->addListener(Text::class,  $handler);
$dispatcher->addListener(Image::class, $handler);
$dispatcher->addListener(Voice::class, $handler);
$dispatcher->addListener(Video::class,  $handler);
$dispatcher->addListener(Shortvideo::class, $handler);
$dispatcher->addListener(Location::class, $handler);
$dispatcher->addListener(Link::class, $handler);

$eventManager = new EventManager();
$eventManager->handle($dispatcher);
