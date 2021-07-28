<?php
/**
 * User: zhouhua
 * Date: 2021/7/1
 * Time: 4:29 下午
 */

namespace Easy5G\Chatbot\Info;


use Easy5G\Chatbot\Application;
use Easy5G\Kernel\BaseClient;
use Easy5G\Kernel\Contracts\InfoInterface;
use Easy5G\Kernel\Exceptions\BadResponseException;
use Easy5G\Kernel\Exceptions\InvalidConfigException;
use Easy5G\Kernel\Exceptions\InvalidInfoException;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\Support\ResponseCollection;
use Psr\Http\Message\ResponseInterface;

abstract class Client extends BaseClient
{
    /**
     * getUpdateRequestData
     * @param InfoInterface $info
     * @return array
     */
    abstract protected function getUpdateRequestData(InfoInterface $info): array;

    /**
     * deleteResponse
     * @param ResponseCollection $collect
     * @param ResponseInterface $response
     * @return mixed
     */
    abstract protected function updateResponse(ResponseCollection $collect, ResponseInterface $response);

    /**
     * update
     * @param InfoInterface|array $info
     * @return ResponseCollection
     * @throws BadResponseException|InvalidISPException|InvalidInfoException|InvalidConfigException
     */
    public function update($info)
    {
        /** @var Application $app */
        $app = $this->app;

        if (is_array($info)) {
            $info = $app->chatbotInfoFactory->create($info);
        } elseif (!$info instanceof InfoInterface) {
            //后续增加移动
            throw new InvalidInfoException('$info must be an array or instanceof Info Easy5G\Chatbot\Structure\Info');
        }

        $response = $app->httpClient->post($this->getCurrentUrl('update'), $this->getUpdateRequestData($info));

        return $this->returnCollect($response, [$this, 'updateResponse']);
    }
}