<?php

namespace Thenbsp\Wechat\Message\Entity;

use Thenbsp\Wechat\Message\Entity;

class Article extends Entity
{
    /**
     * 图文列表.
     */
    protected $items = [];

    /**
     * 添加图文.
     */
    public function addItem(ArticleItem $item)
    {
        $this->items[] = $item;
    }

    /**
     * 消息内容.
     */
    public function getBody()
    {
        $body = [];

        foreach ($this->items as $item) {
            $body['item'][] = $item->getBody();
        }

        $count = isset($body['item'])
            ? count($body['item'])
            : 0;

        return ['Articles' => $body, 'ArticleCount' => $count];
    }

    /**
     * 消息类型.
     */
    public function getType()
    {
        return 'news';
    }
}
