<?php

namespace Thenbsp\Wechat\Menu;

class ButtonCollection implements ButtonInterface, ButtonCollectionInterface
{
    /**
     * 子菜单不能超过 5 个
     */
    const MAX_COUNT = 5;

    /**
     * 菜单名称
     */
    protected $name;

    /**
     * 子菜单集合
     */
    protected $child = array();

    /**
     * 构造方法
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * 添加子菜单
     */
    public function addChild(ButtonInterface $button)
    {
        if( count($this->child) > (static::MAX_COUNT - 1) ) {
            throw new \InvalidArgumentException(sprintf(
                '子菜单不能超过 %d 个', static::MAX_COUNT
            ));
        }

        array_push($this->child, $button);
    }

    /**
     * 检测是否有子菜单
     */
    public function hasChild()
    {
        return !empty($this->child);
    }

    /**
     * 获取子菜单
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * 获取菜单数据
     */
    public function getData()
    {
        $data = array(
            'name' => $this->name
        );

        if( $this->hasChild() ) {
            foreach($this->child AS $k=>$v) {
                $data['sub_button'][] = $v->getData();
            }
        }

        return $data;
    }
}
