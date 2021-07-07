<?php
/**
 * User: zhouhua
 * Date: 2021/6/28
 * Time: 5:28 下午
 */

namespace Easy5G\Kernel;

use Easy5G\Kernel\Cache\CacheManager;
use Easy5G\Kernel\Config\Repository;
use Illuminate\Container\Container;

/**
 * Class App
 * @package Easy5G\Kernel
 *
 * @property Repository $config
 * @property $this $app
 * @property CacheManager $cache
 * @property HttpClient $httpClient
 */
abstract class App extends Container
{
    public function __construct()
    {
        $this->registerBaseBindings();
    }

    /**
     * hasInstance
     * @return bool
     */
    public static function hasInstance()
    {
        return isset(self::$instance);
    }

    /**
     * registerBaseBindings
     */
    protected function registerBaseBindings()
    {
        static::setInstance($this);

        $this->instance('app', $this);

        $this->instance(self::class, $this);
    }
}