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
     * info
     * @return Info
     * @throws BindingResolutionException|InvalidISPException
     */
    public function info()
    {
        /** @var Application $app */
        $app = $this->app;

        $responseContent = $app->httpClient->get($this->getCurrentUrl('query'), [
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
     * @return string
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

        return $app->httpClient->post($this->getCurrentUrl('update'), [
            'json' => $info->toJson(),
            'headers' => [
                'Authorization' => $app->access_token->getToken(),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Date' => gmdate('D, d M Y H:i:s', time()) . ' GMT',
            ]
        ]);
    }
}