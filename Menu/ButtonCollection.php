<?php

namespace Thenbsp\Wechat\Menu;

use Thenbsp\Wechat\Util\Serialize;

class ButtonCollection implements ButtonCollectionInterface
{
    /**
     * 菜单名称
     */
    protected $name;

    /**
     * 子菜单集合
     */
    protected $child = array();

    /**
     * 子菜单不能超过 5 个
     */
    protected $count = 5;

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
        if( count($this->child) > ($this->count - 1) ) {
            throw new \InvalidArgumentException(sprintf(
                '子菜单不能超过 %d 个', $this->count
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
    public function getData($asJSON = false)
    {
        $data = array(
            'name' => $this->name
        );

        if( $this->hasChild() ) {
            foreach($this->child AS $k=>$v) {
                $data['sub_button'][] = $v->getData();
            }
        }

        return $asJSON ? Serialize::encode($data, 'json') : $data;
    }
}
