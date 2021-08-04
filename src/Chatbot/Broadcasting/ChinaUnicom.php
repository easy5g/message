<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 11:45 上午
 */

namespace Easy5G\Chatbot\Broadcasting;


use Easy5G\Kernel\Support\Const5G;

class ChinaUnicom extends Client
{
    use Common;

    protected $sendUrl = '%s/bot/%s/%s/messages';
    protected $serviceProvider = Const5G::CU;

}