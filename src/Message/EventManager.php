<?php

namespace Thenbsp\Wechat\Message;

use Thenbsp\Wechat\Util\Request;
use Thenbsp\Wechat\Util\Serialize;
use Thenbsp\Wechat\Message\Event;
use Thenbsp\Wechat\Message\Entity;

class EventManager
{
    /**
     * 请求对象
     */
    protected $request;

    /**
     * 请求参数
     */
    protected $_option = array();

    /**
     * 构造方法
     */
    public function __construct()
    {
        $request = Request::createFromGlobals();
        $content = $request->getContent();

        if( !empty($content) ) {
            $this->_option = Serialize::decode($content, 'xml');
        }
    }

    /**
     * 获取请求对象
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * 获取请求原内容
     */
    public function getContent()
    {
        return $this->request->getContent();
    }

    /**
     * 获取当前参数对应事件命名空间
     */
    public function getEventNamespace()
    {
        if( !array_key_exists('MsgType', $this->_option) ) {
            return;
        }

        if( $this->_option['MsgType'] !== 'event' ) {
            return ucfirst($this->_option['MsgType']);
        }

        switch( true ) {
            case ($this->_option['Event'] === 'subscribe') :
                return array_key_exists('EventKey', $this->_option) ?
                    Event::EVENT_QRCODE_UNSUBSCRIBE : Event::EVENT_SUBSCRIBE;
                break;
            case ($this->_option['Event'] === 'unsubscribe') :
                return Event::EVENT_UNSUBSCRIBE;
                break;
            case ($this->_option['Event'] === 'SCAN') :
                return Event::EVENT_QRCODE_SUBSCRIBE;
                break;
            case ($this->_option['Event'] === 'LOCATION') :
                return Event::EVENT_LOCATION;
                break;
            case ($this->_option['Event'] === 'CLICK') :
                return Event::EVENT_CLICK;
                break;
            case ($this->_option['Event'] === 'VIEW') :
                return Event::EVENT_VIEW;
                break;
            default:
                return;
        }
    }

    /**
     * 添加事件监听
     */
    public function on($eventName, $event)
    {
        if( !Event::isValid($eventName) ) {
            throw new \InvalidArgumentException(sprintf('Invalid Event Name: %s', $eventName));
        }

        if( is_array($event) ) {
            $event = array_slice(array_pad($event, 2, null), 0, 2);
        }

        if( is_callable($event) && ($eventName === $this->getEventNamespace()) ) {

            $namespace  = 'Thenbsp\\Wechat\\Message\\Event\\'.$this->getEventNamespace();
            $response   = call_user_func_array($event, array(new $namespace($this->_option)));

            if( $response instanceof Entity ) {
                $response->send();
            }
        }

        return $this;
    }

    /**
     * 魔术方法
     */
    public function __call($method, $args)
    {
        if( mb_substr($method, 0, 2) === 'on' ) {
            $eventName = ucfirst(ltrim($method, 'on'));
            if( Event::isValid($eventName) && !empty($args) ) {
                $this->on($eventName, $args[0]);
            }
        }

        return $this;
    }
}
