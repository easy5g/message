<?php
/**
 * User: zhouhua
 * Date: 2021/7/5
 * Time: 11:02 上午
 */

namespace Easy5G\Maap\Menu;


use Easy5G\Kernel\Exceptions\BadRequestException;
use Easy5G\Kernel\Exceptions\BadResponseException;
use Easy5G\Maap\Application;

trait Common
{
    /**
     * getCreateUrl
     * @return string
     */
    protected function getCreateUrl()
    {
        if (isset($this->thirdCreateUrl)) {
            return $this->thirdCreateUrl;
        }

        /** @var Application $app */
        $app = $this->app;

        $config = $app->config->get($this->serviceProvider);

        return sprintf(static::CREATE_URL, $config['url'], $config['apiVersion'], $config['chatbotId']);
    }

    /**
     * create
     * @param $buttons
     * @return bool
     */
    public function create($buttons)
    {
        if (json_decode($buttons, true) === false) {
            throw new BadRequestException('Incorrect data structure,buttons must be json');
        }

        /** @var Application $app */
        $app = $this->app;

        $responseContent = $app->httpClient->get($this->getCreateUrl(), [
            'json' => $buttons,

            'headers' => [
                'Authorization' => $app->access_token->getToken(),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Date' => gmdate('D, d M Y H:i:s', time()) . ' GMT',
            ]
        ]);

        $tokenData = json_decode($responseContent, true);

        if (empty($tokenData)) {
            throw new BadResponseException('Incorrect data structure');
        }

        return $tokenData['errorCode'] === 0;
    }
}