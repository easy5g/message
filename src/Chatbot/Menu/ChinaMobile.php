<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 10:03 上午
 */

namespace Easy5G\Chatbot\Menu;


use Easy5G\Kernel\Contracts\ChatbotMenuInterface;
use Easy5G\Kernel\Support\Const5G;
use Easy5G\Kernel\Support\ResponseCollection;
use Psr\Http\Message\ResponseInterface;

class ChinaMobile extends Client
{
    protected $serviceProvider = Const5G::CM;

    protected function getCreateRequestData(ChatbotMenuInterface $buttons): array
    {
        // TODO: Implement getCreateRequestData() method.
    }

    protected function createResponse(ResponseCollection $collect, ResponseInterface $response)
    {
        // TODO: Implement createResponse() method.
    }
}