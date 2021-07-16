<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 11:45 上午
 */

namespace Easy5G\Chatbot\Menu;


use Easy5G\Kernel\Support\Const5G;

class ChinaUnicom extends Client
{
    use Common;

    protected $thirdCreateUrl = '%s/bot/%s/%s/update/chatBotInfo/menu';
    protected $serviceProvider = Const5G::CU;
}