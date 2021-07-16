<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 11:45 上午
 */

namespace Easy5G\Chatbot\Server;


use Easy5G\Kernel\Support\Const5G;

class ChinaUnicom extends Client
{
    use Common;

    const TOKEN_URL = '%s/bot/%s/%s/accessToken';

    protected $thirdUrl;
    protected $serviceProvider = Const5G::CU;
}