<?php

namespace Thenbsp\Wechat\Message;

use Thenbsp\Wechat\Util\Option;

abstract class Event extends Option
{
    /**
     * 消息事件类型
     */
    const TEXT                      = 'Text';
    const IMAGE                     = 'Image'; 
    const VOICE                     = 'Voice';
    const VIDEO                     = 'Video';
    const SHORTVIDEO                = 'Shortvideo';
    const LOCATION                  = 'Location';
    const LINK                      = 'Link';
    const EVENT_SUBSCRIBE           = 'EventSubscribe';
    const EVENT_UNSUBSCRIBE         = 'EventUnsubscribe';  
    const EVENT_QRCODE_SUBSCRIBE    = 'EventQrcodeSubscribe';
    const EVENT_QRCODE_UNSUBSCRIBE  = 'EventQrcodeUnsubscribe';
    const EVENT_LOCATION            = 'EventLocation';
    const EVENT_CLICK               = 'EventClick';
    const EVENT_VIEW                = 'EventView';

    /**
     * 获取有全部 Type
     */
    public static function getValid()
    {
        $const = new \ReflectionClass(__CLASS__);

        return $const->getConstants();
    }

    /**
     * 检测事件名是有否效
     */
    public static function isValid($eventName)
    {
        return in_array($eventName, self::getValid(), true);
    }
}
