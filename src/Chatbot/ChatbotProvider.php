<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 11:08 上午
 */

namespace Easy5G\Chatbot;


use Easy5G\Kernel\ServiceProvider;
use Symfony\Component\HttpFoundation\Request;

class ChatbotProvider extends ServiceProvider
{
    public $singletons = [
        'access_token' => Auth\Selector::class,
        'broadcasting' => Broadcasting\Selector::class,
        'info' => Info\Selector::class,
        'material' => Material\Selector::class,
        'media' => Material\MediaSelector::class,
        'menu' => Menu\Selector::class,
        'server' => Auth\Selector::class,
        'template_message' => TemplateMessage\Selector::class,
    ];

    /**
     * register 注册服务
     */
    public function register()
    {
        foreach ($this->singletons as $abstract => $singleton) {
            $this->app->singletonIf($abstract, function ($app) use ($singleton) {
                return (new $singleton($app))->register();
            });
        }

        $this->app->bind('request', function () {
            return Request::createFromGlobals();
        });
    }
}