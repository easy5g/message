<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 10:02 上午
 */

namespace Easy5G\Maap\Menu;


use Easy5G\Kernel\Support\Const5G;

class ChinaTelecom extends Client
{
    use Common;

    protected $thirdCreateUrl = '%s/bot/%s/%s/update/chatBotInfo/menu';
    protected $serviceProvider = Const5G::CT;
}