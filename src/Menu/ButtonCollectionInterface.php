<?php

namespace Thenbsp\Wechat\Menu;

interface ButtonCollectionInterface extends ButtonInterface
{
    /**
     * 添加子菜单
     */
    public function addChild(ButtonInterface $button);

    /**
     * 检测是否有子菜单
     */
    public function hasChild();

    /**
     * 获取子按钮
     */
    public function getChild();
}
