<?php
/**
 * User: zhouhua
 * Date: 2021/7/5
 * Time: 11:02 上午
 */

namespace Easy5G\Maap\Chatbot;


use Easy5G\Kernel\Exceptions\InvalidInfoException;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Kernel\Exceptions\BadResponseException;
use Easy5G\Maap\Application;
use Easy5G\Maap\Structure\Info;
use Illuminate\Contracts\Container\BindingResolutionException;

trait Common
{
    /**
     * getInfoUrl 获取请求地址
     * @return string
     */
    protected function getInfoUrl()
    {
        if (isset($this->thirdQueryUrl)) {
            return $this->thirdQueryUrl;
        }

        /** @var Application $app */
        $app = $this->app;

        $config = $app->config->get($this->serviceProvider);

        return sprintf(static::QUERY_URL, $config['url'], $config['apiVersion'], $config['chatbotId']);
    }

    /**
     * getUpdateInfoUrl 获取请求地址
     * @return string
     */
    protected function getUpdateInfoUrl()
    {
        if (isset($this->thirdUpdateUrl)) {
            return $this->thirdUpdateUrl;
        }

        /** @var Application $app */
        $app = $this->app;

        $config = $app->config->get($this->serviceProvider);

        return sprintf(static::UPDATE_URL, $config['url'], $config['apiVersion'], $config['chatbotId']);
    }

    /**
     * info
     * @return Info
     * @throws BindingResolutionException|InvalidISPException
     */
    public function info()
    {
        /** @var Application $app */
        $app = $this->app;

        $responseContent = $app->httpClient->get($this->getInfoUrl(), [
            'headers' => [
                'Authorization' => $app->access_token->getToken(),
                'Accept' => 'application/json',
                'Date' => gmdate('D, d M Y H:i:s', time()) . ' GMT',
            ]
        ]);

        $infoArr = json_decode($responseContent, true);

        if (empty($infoArr)) {
            throw new BadResponseException('Incorrect data structure');
        }

        $info = new Info();

        $info->assignment($infoArr);

        return $info;
    }

    /**
     * updateInfo
     * @param Info|array $info
     * @return bool
     * @throws BindingResolutionException|InvalidISPException|InvalidInfoException|BadResponseException
     */
    public function updateInfo($info)
    {
        if (is_array($info)) {
            $info = new Info($info);
        } elseif (!$info instanceof Info) {
            throw new InvalidInfoException('$info must be an array or instanceof Info Easy5G\Maap\Chatbot\Info');
        }

        /** @var Application $app */
        $app = $this->app;

        $responseContent = $app->httpClient->post($this->getUpdateInfoUrl(), [
            'json' => $info->toJson(),
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