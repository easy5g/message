<?php
/**
 * User: zhouhua
 * Date: 2021/7/1
 * Time: 4:29 下午
 */

namespace Easy5G\Chatbot\Broadcasting;


use Easy5G\Chatbot\Application;
use Easy5G\Kernel\BaseClient;
use Easy5G\Kernel\Contracts\MessageInterface;
use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Kernel\Support\ResponseCollection;
use Psr\Http\Message\ResponseInterface;

abstract class Client extends BaseClient
{
    /**
     * getSendRequestData
     * @param MessageInterface $message
     * @param array $sendInfo
     * @return array
     */
    abstract protected function getSendRequestData(MessageInterface $message,array $sendInfo): array;

    /**
     * sendMessageResponse
     * @param ResponseCollection $collect
     * @param ResponseInterface $response
     */
    abstract protected function sendMessageResponse(ResponseCollection $collect, ResponseInterface $response);

    /**
     * sendMessage
     * @param MessageInterface $message
     * @param array $sendInfo
     * @return ResponseCollection
     * @throws InvalidConfigException
     */
    public function sendMessage(MessageInterface $message,array $sendInfo)
    {
        /** @var Application $app */
        $app = $this->app;

        $response = $app->httpClient->post($this->getCurrentUrl('send'), $this->getSendRequestData($message, $sendInfo));

        $collect = $this->returnCollect($response,[$this,'sendMessageResponse']);

        if ($this instanceof ChinaMobile) {
            $collect->set('contributionId',$sendInfo['contributionID']);
        }

        return $collect;
    }
}