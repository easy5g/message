<?php
/**
 * User: zhouhua
 * Date: 2021/7/1
 * Time: 4:29 下午
 */

namespace Easy5G\Chatbot\Menu;


use Easy5G\Chatbot\Application;
use Easy5G\Kernel\BaseClient;
use Easy5G\Kernel\Contracts\ChatbotMenuInterface;
use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Kernel\Support\ResponseCollection;
use Psr\Http\Message\ResponseInterface;

abstract class Client extends BaseClient
{
    /**
     * getCreateRequestData
     * @param ChatbotMenuInterface $buttons
     * @return array
     */
    abstract protected function getCreateRequestData(ChatbotMenuInterface $buttons): array;

    /**
     * createResponse
     * @param ResponseCollection $collect
     * @param ResponseInterface $response
     */
    abstract protected function createResponse(ResponseCollection $collect, ResponseInterface $response);

    /**
     * create
     * @param ChatbotMenuInterface $buttons
     * @return ResponseCollection
     * @throws InvalidConfigException
     */
    public function create(ChatbotMenuInterface $buttons)
    {
        /** @var Application $app */
        $app = $this->app;

        $response = $app->httpClient->post($this->getCurrentUrl('create'), $this->getCreateRequestData($buttons));

        return $this->returnCollect($response, [$this, 'createResponse']);
    }
}