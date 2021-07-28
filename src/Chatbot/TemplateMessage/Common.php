<?php
/**
 * User: zhouhua
 * Date: 2021/7/5
 * Time: 11:02 上午
 */

namespace Easy5G\Chatbot\TemplateMessage;


use Easy5G\Kernel\Support\ResponseCollection;
use Psr\Http\Message\ResponseInterface;

trait Common
{
    protected function getBatchSendRequestData(array $data):array
    {
    }

    protected function batchSendResponse(ResponseCollection $collect, ResponseInterface $response)
    {
    }
}