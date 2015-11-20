<?php

namespace Thenbsp\Wechat\Message\Template;

interface TemplateOptionInterface
{
    /**
     * 检测是否存在指定选项
     */
    public function has($data);

    /**
     * 添加选项
     */
    public function add($data, $value, $color);

    /**
     * 移除指定选项
     */
    public function remove($data);

    /**
     * 转为 Array
     */
    public function toArray();

    /**
     * 转为 JSON
     */
    public function toJSON();

    /**
     * 检测选项上否为空
     */
    public function isEmpty();
}