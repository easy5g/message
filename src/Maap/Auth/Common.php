<?php
/**
 * User: zhouhua
 * Date: 2021/7/5
 * Time: 11:02 上午
 */

namespace Easy5G\Maap\Auth;


use Easy5G\Kernel\Exceptions\BadRequestException;
use Easy5G\Kernel\Exceptions\TokenResponseException;
use Easy5G\Maap\Application;

trait Common
{
    /**
     * getCredentials
     * @return array
     */
    protected function getCredentials(): array
    {
        $config = $this->app->config->get($this->serviceProvider);

        return [
            'appId' => $config['appId'],
            'appKey' => $config['appKey'],
        ];
    }

    /**
     * getRequestTokenUrl 获取请求地址
     * @return string
     */
    protected function getRequestTokenUrl()
    {
        if (isset($this->thirdUrl)) {
            return $this->thirdUrl;
        }

        /** @var Application $app */
        $app = $this->app;

        $config = $app->config->get($this->serviceProvider);

        return sprintf(static::TOKEN_URL, $config['url'], $config['apiVersion'], $config['chatbotId']);
    }

    /**
     * requestToken
     * @return array
     * @throws TokenResponseException
     * @throws BadRequestException
     */
    protected function requestToken(): array
    {
        /** @var Application $app */
        $app = $this->app;

        $responseContent = $app->httpClient->post($this->getRequestTokenUrl(), [
            'json' => $this->getCredentials(),
            'headers' => [
                'Accept' => 'application/json',
                'Date' => gmdate('D, d M Y H:i:s', time()) . ' GMT',
            ]
        ]);

        $tokenData = json_decode($responseContent, true);

        if ($tokenData === false) {
            throw new TokenResponseException('Incorrect data structure');
        }

        if ($tokenData['errorCode'] !== 0) {
            throw new TokenResponseException($tokenData['errorMessage'], $tokenData['errorCode']);
        }

        return [$tokenData['accessToken'], $tokenData['expires']];
    }
}