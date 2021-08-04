<?php
/**
 * User: zhouhua
 * Date: 2021/7/5
 * Time: 11:02 上午
 */

namespace Easy5G\Chatbot\Broadcasting;


use Easy5G\Chatbot\Application;
use Easy5G\Chatbot\Structure\Text;
use Easy5G\Kernel\Contracts\MessageInterface;
use Easy5G\Kernel\Support\ResponseCollection;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;

trait Common
{
    /**
     * getSendRequestData
     * @param MessageInterface $message
     * @param array $sendInfo
     * @return array
     */
    protected function getSendRequestData(MessageInterface $message, array $sendInfo): array
    {
        /** @var Application $app */
        $app = $this->app;

        $jsonArr = [
            'messageId' => $sendInfo['messageId'] ?? Uuid::uuid4(),
            'messageList' => $message->getText($this->serviceProvider),
            'destinationAddress' => $sendInfo['destinationAddress'],
            'senderAddress' => $app->config->get($this->serviceProvider . '.chatbotURI'),
            'conversationId' => $sendInfo['conversationId'] ?? Uuid::uuid4(),
            'contributionId' => $sendInfo['contributionId'] ?? Uuid::uuid4(),
            'serviceCapability' => $sendInfo['serviceCapability'] ?? [
                    'capabilityId' => 'ChatbotSA',
                    'version' => '+g.gsma.rcs.botversion=\"#=1\"'
                ],
        ];

        if (isset($message->fallback) && $message->fallback instanceof Text) {
            $jsonArr['smsSupported'] = true;
            $jsonArr['smsContent'] = $message->fallback->getToHttpData();
        }

        isset($sendInfo['inReplyTo']) && $jsonArr['inReplyTo'] = $sendInfo['inReplyTo'];
        isset($sendInfo['storeSupported']) && $jsonArr['storeSupported'] = $sendInfo['storeSupported'];
        isset($sendInfo['trafficType']) && $jsonArr['trafficType'] = $sendInfo['trafficType'];
        isset($sendInfo['imFormat']) && $jsonArr['imFormat'] = $sendInfo['imFormat'];
        isset($sendInfo['reportRequest']) && $jsonArr['reportRequest'] = $sendInfo['reportRequest'];

        return [
            'headers' => [
                'Authorization' => $app->access_token->getToken(),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'json' => $jsonArr
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