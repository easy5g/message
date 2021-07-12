<?php
/**
 * User: zhouhua
 * Date: 2021/6/28
 * Time: 5:28 下午
 */

namespace Easy5G\Kernel;


use Easy5G\Kernel\Cache\CacheManager;
use Unit\Kernel\Log\LogManager;

class ServiceProvider
{
    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;

        $this->registerBaseService();
    }

    public function registerBaseService()
    {
        $this->app->singletonIf(Contracts\CacheInterface::class, CacheManager::class);
        $this->app->alias(Contracts\CacheInterface::class, 'cache');

        $this->app->singletonIf('httpClient',HttpClient::class);
        $this->app->singletonIf('log',LogManager::class);
    }

}
