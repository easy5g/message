<?php
/**
 * User: zhouhua
 * Date: 2021/7/1
 * Time: 4:29 下午
 */

namespace Easy5G\Chatbot\TemplateMessage;


use Easy5G\Chatbot\Application;
use Easy5G\Kernel\BaseClient;
use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\Support\ResponseCollection;
use Illuminate\Contracts\Container\BindingResolutionException;
use Psr\Http\Message\ResponseInterface;

abstract class Client extends BaseClient
{
    /**
     * getBatchSendRequestData
     * @param array $data
     * @return array
     */
    abstract protected function getBatchSendRequestData(array $data): array;

    /**
     * getBatchReplyRequestData
     * @param array $data
     * @return array
     */
    abstract protected function getBatchReplyRequestData(array $data): array;

    /**
     * batchSendResponse
     * @param ResponseCollection $collect
     * @param ResponseInterface $response
     */
    abstract protected function batchSendResponse(ResponseCollection $collect, ResponseInterface $response);

    /**
     * batchReplyResponse
     * @param ResponseCollection $collect
     * @param ResponseInterface $response
     */
    abstract protected function batchReplyResponse(ResponseCollection $collect, ResponseInterface $response);

    /**
     * batchSend
     * @param array $data
     * @return ResponseCollection
     * @throws InvalidConfigException
     */
    public function batchSend(array $data)
    {
        $this->checkBatchData($data, 'send');

        /** @var Application $app */
        $app = $this->app;

        $response = $app->httpClient->post($this->getCurrentUrl('batchSend'), $this->getBatchSendRequestData($data));

        return $this->returnCollect($response, [$this, 'batchSendResponse']);
    }

    /**
     * batchReply
     * @param array $data
     * @return ResponseCollection
     * @throws InvalidConfigException
     */
    public function batchReply(array $data)
    {
        $this->checkBatchData($data, 'reply');

        /** @var Application $app */
        $app = $this->app;

        $response = $app->httpClient->post($this->getCurrentUrl('batchReply'), $this->getBatchReplyRequestData($data));

        return $this->returnCollect($response, [$this, 'batchReplyResponse']);
    }

}