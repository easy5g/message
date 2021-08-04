<?php
/**
 * User: zhouhua
 * Date: 2021/7/5
 * Time: 11:02 上午
 */

namespace Easy5G\Chatbot\Broadcasting;


use Easy5G\Kernel\Support\ResponseCollection;
use Psr\Http\Message\ResponseInterface;

trait Common
{
    protected function getSendRequestData(array $data): array
    {
        return  [
            'headers' => [
                'Authorization' => $this->app->access_token->getToken(),
            ],
        ];
    }

    /**
     * sendMessageResponse
     * @param ResponseCollection $collect
     * @param ResponseInterface $response
     */
    protected function sendMessageResponse(ResponseCollection $collect, ResponseInterface $response)
    {
        $this->utBaseResponse(...func_get_args());
    }
}