<?php
/**
 * User: zhouhua
 * Date: 2021/6/28
 * Time: 5:28 下午
 */

namespace Easy5G\Kernel;


use Easy5G\Kernel\Cache\ApcuCache;
use Easy5G\Kernel\Cache\FileCache;
use GuzzleHttp\Client;
use Symfony\Component\Cache\Adapter\ApcuAdapter;

class ServiceProvider
{
    /** @var App */
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;

        $this->registerBaseService();
    }

    public function registerBaseService()
    {
        if (ApcuAdapter::isSupported()) {
            $this->app->singletonIf('cache', ApcuCache::class);
            $this->app->singletonIf(Contracts\CacheInterface::class, ApcuCache::class);
        } else {
            $this->app->singletonIf('cache', FileCache::class);
            $this->app->singletonIf(Contracts\CacheInterface::class, FileCache::class);
        }

        $this->app->singletonIf(Client::class);
        $this->app->singletonIf('httpClient',HttpClient::class);
    }
}
