<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 11:45 上午
 */

namespace Easy5G\Maap\Menu;


use Easy5G\Kernel\Support\Const5G;

class ChinaUnicom extends Client
{
    use Common;

    const CREATE_URL = '%s/bot/%s/%s/update/chatBotInfo/menu';

    protected $thirdCreateUrl;
    protected $serviceProvider = Const5G::CU;
}