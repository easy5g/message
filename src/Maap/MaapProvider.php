<?php
/**
 * User: zhouhua
 * Date: 2021/6/29
 * Time: 11:08 上午
 */

namespace Easy5G\Maap;


use Easy5G\Kernel\ServiceProvider;

class MaapProvider extends ServiceProvider
{
    public $bindings = [
        'base' => Base\Selector::class,
        'access_token' => Auth\Selector::class
    ];

    /**
     * register 注册服务
     */
    public function register()
    {
        foreach ($this->bindings as $abstract => $bind) {
            $this->app->singletonIf($abstract, function ($app) use ($bind) {
                return (new $bind($app))->register();
            });
        }
    }
}