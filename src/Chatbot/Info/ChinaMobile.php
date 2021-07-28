<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 10:03 上午
 */

namespace Easy5G\Chatbot\Info;


use Easy5G\Kernel\Contracts\InfoInterface;
use Easy5G\Kernel\Support\Const5G;
use Easy5G\Kernel\Support\ResponseCollection;
use Psr\Http\Message\ResponseInterface;

class ChinaMobile extends Client
{
    protected $serviceProvider = Const5G::CM;

    protected function getUpdateRequestData(InfoInterface $info): array
    {
        // TODO: Implement getUpdateRequestData() method.
    }

    protected function updateResponse(ResponseCollection $collect, ResponseInterface $response)
    {
        // TODO: Implement updateResponse() method.
    }
}