<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 11:45 上午
 */

namespace Easy5G\Maap\Chatbot;


use Easy5G\Kernel\Support\Const5G;

class ChinaUnicom extends Client
{
    use Common;

    const UPDATE_URL = '%s/bot/%s/%s/update/chatBotInfo/optionals';
    const QUERY_URL = '%s/bot/%s/%s/find/chatBotInfo';

    protected $thirdUpdateUrl;
    protected $thirdQueryUrl;

    protected $serviceProvider = Const5G::CU;
}