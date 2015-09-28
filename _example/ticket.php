<?php

require './_example.php';

use Thenbsp\Wechat\Ticket;

$ticket = new Ticket($accessToken);

// 获取 Jsapi Ticket
var_dump('Jsapi Ticket: '.$ticket->getTicket());
echo '<br />';

// 获取 Wxcard Ticket
var_dump('Wxcard Ticket: '.$ticket->getTicket('wx_card'));
