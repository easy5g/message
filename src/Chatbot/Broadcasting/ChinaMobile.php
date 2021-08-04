<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 10:03 上午
 */

namespace Easy5G\Chatbot\Broadcasting;


use Easy5G\Kernel\Support\Const5G;
use Easy5G\Kernel\Support\ResponseCollection;
use Psr\Http\Message\ResponseInterface;

class ChinaMobile extends Client
{
    protected $sendUrl = '%s/messaging/group/plain/outbound/%s/requests';

    protected $serviceProvider = Const5G::CM;

    protected function getSendRequestData($message, $sendInfo): array
    {
        return [
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
        $this->mBaseResponse(...func_get_args());
    }
}