<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 10:03 上午
 */

namespace Easy5G\Chatbot\Broadcasting;


use Easy5G\Chatbot\Structure\File;
use Easy5G\Chatbot\Structure\Text;
use Easy5G\Kernel\Contracts\MessageInterface;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\Support\Const5G;
use Easy5G\Kernel\Support\ResponseCollection;
use Easy5G\Kernel\Support\Xml;
use Psr\Http\Message\ResponseInterface;
use Ramsey\Uuid\Uuid;

class ChinaMobile extends Client
{
    protected $sendUrl = '%s/messaging/group/plain/outbound/%s/requests';

    protected $serviceProvider = Const5G::CM;

    /**
     * getSendRequestData
     * @param MessageInterface $message
     * @param array $sendInfo
     * @return array
     * @throws InvalidISPException
     */
    protected function getSendRequestData(MessageInterface $message,array $sendInfo): array
    {
        $xmlArr = [
            'destinationAddress' => $sendInfo['destinationAddress'],
            'contentType' => $message->getContentType($this->serviceProvider),
            'contentEncoding' => $message->getContentEncoding(),
            'bodyText' => $message->getText($this->serviceProvider),
            'conversationID' => $sendInfo['conversationId'] ?? Uuid::uuid4(),
        ];

        if (isset($message->fallback) && ($message->fallback instanceof Text || $message->fallback instanceof File)) {
            $xmlArr['fallbackSupported'] = true;
            $xmlArr['fallbackContentType'] = $message->fallback->getContentType($this->serviceProvider);
            $xmlArr['fallbackContentEncoding'] = $message->fallback->getContentEncoding();
            $xmlArr['rcsBodyText'] = $message->fallback->getToHttpData();

            if ($message->fallback instanceof File) {
                $xmlArr['fallbackContentType'] .= '+xml';
            }
        }

        isset($sendInfo['inReplyToContributionID']) && $xmlArr['inReplyToContributionID'] = $sendInfo['inReplyToContributionID'];
        isset($sendInfo['storeSupported']) && $xmlArr['storeSupported'] = $sendInfo['storeSupported'];

        return [
            'headers' => [
                'Authorization' => $this->app->access_token->getToken(),
                'Content-Type' => 'application/xml',
            ],
            'body' => Xml::build($xmlArr, ['version' => '1.0', 'encoding' => 'UTF-8'])
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