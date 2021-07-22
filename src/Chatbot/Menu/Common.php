<?php
/**
 * User: zhouhua
 * Date: 2021/7/5
 * Time: 11:02 上午
 */

namespace Easy5G\Chatbot\Menu;


use Easy5G\Kernel\Contracts\ChatbotMenuInterface;
use Easy5G\Kernel\Exceptions\InvalidISPException;
use Easy5G\Chatbot\Application;
use Illuminate\Contracts\Container\BindingResolutionException;

trait Common
{
    /**
     * create
     * @param ChatbotMenuInterface $buttons
     * @return string
     * @throws BindingResolutionException|InvalidISPException
     */
    public function create(ChatbotMenuInterface $buttons)
    {
        /** @var Application $app */
        $app = $this->app;

        return $app->httpClient->post($this->getCurrentUrl('create'), [
            'json' => $buttons->toJson(),
            'headers' => [
                'Authorization' => $app->access_token->getToken(),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Date' => gmdate('D, d M Y H:i:s', time()) . ' GMT',
            ]
        ]);
    }
}