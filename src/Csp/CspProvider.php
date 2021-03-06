<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 11:08 上午
 */

namespace Easy5G\Csp;


use Easy5G\Kernel\ServiceProvider;
use Symfony\Component\HttpFoundation\Request;

class CspProvider extends ServiceProvider
{
    public $singletons = [
        'access_token' => Auth\Selector::class,
        'customer' => Customer\Selector::class,
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