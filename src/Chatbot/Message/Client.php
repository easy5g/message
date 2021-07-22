<?php
/**
 * User: zhouhua
 * Date: 2021/7/1
 * Time: 4:29 下午
 */

namespace Easy5G\Chatbot\Message;


use Easy5G\Kernel\BaseClient;

abstract class Client extends BaseClient
{
    abstract public function send($data);
}