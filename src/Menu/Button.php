<?php

namespace Thenbsp\Wechat\Menu;

class Button implements ButtonInterface
{
    /**
     * 菜单名称.
     */
    protected $name;

    /**
     * 菜单类型.
     */
    protected $type;

    /**
     * 菜单值（key/url/media_id => value）.
     */
    protected $value = [];

    /**
     * 菜单类型映射关系.
     */
    protected $mapping = [
        'view' => 'url',
        'click' => 'key',
        'scancode_push' => 'key',
        'scancode_waitmsg' => 'key',
        'pic_sysphoto' => 'key',
        'pic_photo_or_album' => 'key',
        'pic_weixin' => 'key',
        'location_select' => 'key',
        'media_id' => 'media_id',
        'view_limited' => 'media_id',
    ];

    /**
     * 构造方法.
     */
    public function __construct($name, $type, $value)
    {
        if (!array_key_exists($type, $this->mapping)) {
            throw new \InvalidArgumentException(sprintf('Invalid Type: %s', $type));
        }

        $this->name = $name;
        $this->type = $type;
        $this->value = [$this->mapping[$type] => $value];
    }

    /**
     * 菜单数据.
     */
    public function getData()
    {
        $data = [
            'name' => $this->name,
            'type' => $this->type,
        ];

        return array_merge($data, $this->value);
    }
}
