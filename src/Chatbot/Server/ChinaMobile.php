<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 10:03 上午
 */

namespace Easy5G\Chatbot\Server;


use Easy5G\Kernel\Support\Const5G;

class ChinaMobile extends Client
{
    protected $thirdUrl;
    protected $serviceProvider = Const5G::CM;
}