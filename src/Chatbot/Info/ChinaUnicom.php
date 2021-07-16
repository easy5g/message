<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 11:45 上午
 */

namespace Easy5G\Chatbot\Info;


use Easy5G\Kernel\Support\Const5G;

class ChinaUnicom extends Client
{
    use Common;

    protected $queryUrl = '%s/bot/%s/%s/find/chatBotInfo';
    protected $updateUrl = '%s/bot/%s/%s/update/chatBotInfo/optionals';

    protected $serviceProvider = Const5G::CU;
}