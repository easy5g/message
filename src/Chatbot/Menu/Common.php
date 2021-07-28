<?php
/**
 * User: zhouhua
 * Date: 2021/7/5
 * Time: 11:02 上午
 */

namespace Easy5G\Chatbot\Menu;


use Easy5G\Kernel\Contracts\ChatbotMenuInterface;
use Easy5G\Kernel\Support\ResponseCollection;
use Psr\Http\Message\ResponseInterface;

trait Common
{
    /**
     * getCreateRequestData
     * @param ChatbotMenuInterface $buttons
     * @return array
     */
    protected function getCreateRequestData(ChatbotMenuInterface $buttons): array
    {
        return [
            'json' => $buttons->toJson(),
            'headers' => [
                'Authorization' => $this->app->access_token->getToken(),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Date' => gmdate('D, d M Y H:i:s', time()) . ' GMT',
            ]
        ];
    }

    /**
     * createResponse
     * @param ResponseCollection $collect
     * @param ResponseInterface $response
     */
    protected function createResponse(ResponseCollection $collect, ResponseInterface $response)
    {
        $this->utBaseResponse(...func_get_args());
    }
}